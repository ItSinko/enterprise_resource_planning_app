<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\DetailPembelianBarangMasuk;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPermintaanPembelian;
use App\Models\DetailPoPembelian;
use App\Models\DetailPPAset;
use App\Models\DetailPPBom;
use App\Models\DetailPPBomPart;
use App\Models\Divisi;
use App\Models\PembelianBarangMasuk;
use App\Models\PermintaanPembelian;
use App\Models\PoPembelian;
use App\Models\teknik\BillOfMaterial;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    public function get_data_pp()
    {
        $data = array();
        $pp = PermintaanPembelian::all();
        foreach ($pp as $key_pp => $pp) {
            $data[$key_pp] = array(
                'id' => $pp->id,
                'pp' => $pp->no_pp,
                'tujuan' => $pp->tujuan,
                'jenis' => $pp->jenis,
                'tgl_dibutuhkan' => $pp->tgl_dibutuhkan,
                'tgl_diminta' => $pp->tgl_diminta,
                'divisi' => $pp->Divisi->nama,
                'status' => $pp->Status->nama,
            );
        }

        return response()->json(['data' => $data]);
    }

    public function ubah_pp($jenis, $id)
    {

        if ($jenis == 'terima') {
            $pp =  PermintaanPembelian::find($id);
            $pp->status_id = 2;
            $pp->save();
            return response()->json([
                'data' => 'berhasil'
            ]);
        } elseif ($jenis == 'tolak' || $jenis == 'batal') {
            $pp =  PermintaanPembelian::find($id);
            $pp->status_id = 18;
            $pp->save();
            return response()->json([
                'data' => 'berhasil'
            ]);
        } elseif ($jenis == 'hapus') {

            $dbom = DetailPermintaanPembelian::whereHas('PermintaanPembelian', function ($q) use ($id) {
                $q->where('permintaan_pembelian_id', $id);
            })->get();

            if (count($dbom) > 0) {
                $deldbom = DetailPermintaanPembelian::whereHas('PermintaanPembelian', function ($q) use ($id) {
                    $q->where('permintaan_pembelian_id', $id);
                })->delete();
                if (!$deldbom) {
                    $bool = false;
                }
            }

            PermintaanPembelian::where('id', $id)->delete();
            return response()->json([
                'data' => 'berhasil'
            ]);
        }
    }
    public function store_data_pp(Request $request)
    {
        //dd($request->all());
        $validator = Validator::make($request->all(), [
            'no_pp' => 'required|unique:permintaan_pembelian,no_pp',
            'tgl_dibutuhkan' => 'required',
            'divisi' => 'required',
            'tujuan' => 'required',
            'jenis' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => 'gagal'
            ]);
        } else {
            $pp = PermintaanPembelian::create([
                'no_pp' => $request->no_pp,
                'tujuan' => $request->tujuan,
                'jenis' => $request->jenis,
                'tgl_dibutuhkan' => $request->tgl_dibutuhkan,
                'tgl_diminta' => $request->tgl_diminta,
                'divisi_id' => $request->divisi,
                'status_id' => 11
            ]);

            if (isset($request->aset)) {
                if (isset($request->aset[0]['terdaftar'])) {
                    for ($i = 0; $i < count($request->aset[0]['terdaftar']); $i++) {
                        DetailPPAset::create([
                            'permintaan_pembelian_id' => $pp->id,
                            'aset_id' => $request->aset[0]['terdaftar'][$i]['aset_id'],
                            'jumlah' => $request->aset[0]['terdaftar'][$i]['jumlah'],
                            'harga' => $request->aset[0]['terdaftar'][$i]['harga'],
                            'estimasi_harga' => $request->aset[0]['terdaftar'][$i]['estimasi_harga'],
                            'via' => $request->aset[0]['terdaftar'][$i]['via'],
                            'link' => $request->aset[0]['terdaftar'][$i]['link'],
                        ]);
                    }
                }
                if (isset($request->aset[0]['tidak_terdaftar'])) {
                    for ($i = 0; $i < count($request->aset[0]['tidak_terdaftar']); $i++) {
                        $aset = Aset::create([
                            'daftar_perkiraan_id' => $request->aset[0]['tidak_terdaftar'][$i]['daftar_perkiraan_id'],
                            'nama' => $request->aset[0]['tidak_terdaftar'][$i]['nama'],
                            'merk'  => $request->aset[0]['tidak_terdaftar'][$i]['merk']
                        ]);
                        DetailPPAset::create([
                            'permintaan_pembelian_id' => $pp->id,
                            'aset_id' => $aset->id,
                            'jumlah' => $request->aset[0]['tidak_terdaftar'][$i]['jumlah'],
                            'harga' => $request->aset[0]['tidak_terdaftar'][$i]['harga'],
                            'estimasi_harga' => $request->aset[0]['tidak_terdaftar'][$i]['estimasi_harga'],
                            'via' => $request->aset[0]['tidak_terdaftar'][$i]['via'],
                            'link' => $request->aset[0]['tidak_terdaftar'][$i]['link'],
                        ]);
                    }
                }
            }

            if (isset($request->part)) {
                for ($i = 0; $i < count($request->part); $i++) {
                    $detail_pp_bom =  DetailPPBom::create([
                        'permintaan_pembelian_id' => $pp->id,
                        'bom_id' => $request->part[$i]['bom_id'],
                        'produk_id' => $request->part[$i]['produk_id'],
                    ]);
                    if (isset($request->part[$i]['terdaftar'])) {
                        for ($j = 0; $j < count($request->part[$i]['terdaftar']); $j++) {
                            DetailPPBomPart::create([
                                'detail_pp_bom_id' => $detail_pp_bom->id,
                                'part_id' => $request->part[$i]['terdaftar'][$j]['part_id'],
                                'jumlah' => $request->part[$i]['terdaftar'][$j]['jumlah'],
                                'harga' => $request->part[$i]['terdaftar'][$j]['harga']
                            ]);
                        }
                    }
                    if (isset($request->part[$i]['tidak_terdaftar'])) {
                        for ($j = 0; $j < count($request->part[$i]['tidak_terdaftar']); $j++) {
                            DetailPPBomPart::create([
                                'detail_pp_bom_id' => $detail_pp_bom->id,
                                'nama' => $request->part[$i]['tidak_terdaftar'][$j]['nama'],
                                'jumlah' => $request->part[$i]['tidak_terdaftar'][$j]['jumlah'],
                                'harga' => $request->part[$i]['tidak_terdaftar'][$j]['harga']
                            ]);
                        }
                    }
                }
            }

            return response()->json([
                'data' => 'success'
            ]);
        }
    }

    public function get_data_detail_bom($id)
    {
        $detailbom = DetailPPBom::find($id);
        foreach ($detailbom->DetailPPBomPart as $key_bom => $detailpp) {
            $data[$key_bom] = array(
                'id' => $detailpp->id,
            );
        }
        return response()->json(['data' => $data]);
    }


    public function get_data_detail_pp($id)
    {

        $pp = PermintaanPembelian::find($id);
        $data = array(
            'id' => $pp->id,
            'no_pp' => $pp->no_pp,
            'tgl_dibutuhkan' => $pp->tgl_dibutuhkan,
            'tgl_diminta' => $pp->tgl_diminta,
            'jenis' => $pp->jenis,
            'tujuan' => $pp->tujuan,

        );

        if ($pp->DetailPPAset) {
            foreach ($pp->DetailPPAset as $key_aset => $aset) {
                $data['aset'][$key_aset] = array(
                    'id' => $aset->id,
                    'nama' => $aset->Aset->nama,
                    'jumlah' => $aset->jumlah,
                    'harga' => $aset->harga,
                    'estimasi_harga' => $aset->estimasi_harga,
                    'subtotal' => $aset->harga *  $aset->jumlah,
                    'link' => $aset->via == 'online' ? $aset->link : 'offline',
                );
            }
        }

        if ($pp->DetailPPBom) {
            foreach ($pp->DetailPPBom as $key_bom => $bom) {
                $data['part'][$key_bom] = array(
                    'id' => $bom->id,
                    'kode' => $bom->Bom->kode,
                    'nama' => $bom->Bom->nama,
                );
            }
        }

        return response()->json(['data' => $data]);
    }

    public function get_nourut($divisi_id)
    {

        try {
            $divisi = Divisi::find($divisi_id);
            $check = PermintaanPembelian::where('divisi_id', $divisi_id)->get();
            $urut = 1;
            $divisi = Divisi::find($divisi_id);

            if (count($check) > 0) {
                $urut =  count($check) + 1;
                $no = $urut . '/PP/' . strtoupper($divisi->kode) . '/' . $this->getMonth() . '/' . $this->getYear();
            } else {
                $no = $urut . '/PP/' . strtoupper($divisi->kode) . '/' . $this->getMonth() . '/' . $this->getYear();
            }
            return response()->json(['data' => $no]);
        } catch (Exception $th) {
            return response()->json(['data' => 'error']);
        }
    }

    public function get_data_po($id)
    {
        if ($id != 0) {
            $data = PoPembelian::with(['Supplier', 'Ekspedisi'])->where('permintaan_pembelian_id', $id)->get();
        } else {
            $data = PoPembelian::with(['Supplier', 'Ekspedisi'])->get();
        }

        return response()->json(['data' => $data]);
    }
    public function get_detail_po($id)
    {
        $dpp = PoPembelian::find(1);
        $data[] = array(
            'id' => $dpp->id,
            'nopo' => $dpp->no_po
        );

        foreach ($dpp as $d) {
        }

        return response()->json(['data' => $data]);
    }
    function toRomawi($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }
    function getMonth()
    {
        $m = Carbon::now()->format('m');
        return $this->toRomawi($m);
    }
    function getYear()
    {
        return  Carbon::now()->format('Y');
    }
}
