<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelianBarangMasuk;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPermintaanPembelian;
use App\Models\DetailPoPembelian;
use App\Models\Divisi;
use App\Models\PembelianBarangMasuk;
use App\Models\PermintaanPembelian;
use App\Models\PoPembelian;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
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
                for ($i = 0; $i < count($request->aset); $i++) {
                    DetailPermintaanPembelian::create([
                        'permintaan_pembelian_id' => $pp->id,
                        'aset_id' => $request->aset[$i]['aset_id'],
                        'jumlah' => $request->aset[$i]['jumlah'],
                        'harga' => $request->aset[$i]['harga'],
                        'estimasi_harga' => $request->aset[$i]['es_harga'],
                        'via' => $request->aset[$i]['via'],
                        'link' => $request->aset[$i]['link'],
                    ]);
                }
            }

            if (isset($request->terdaftar)) {
                for ($i = 0; $i < count($request->terdaftar); $i++) {
                    DetailPermintaanPembelian::create([
                        'permintaan_pembelian_id' => $pp->id,
                        'part_id' => $request->terdaftar[$i]['part_id'],
                        'bom_id' => $request->terdaftar[$i]['bom_id'],
                        'jumlah' => $request->terdaftar[$i]['jumlah'],
                        'harga' => $request->terdaftar[$i]['harga'],
                        'is_terdaftar' => 1,
                    ]);
                }
            }

            if (isset($request->tdk_terdaftar)) {
                for ($i = 0; $i < count($request->tdk_terdaftar); $i++) {
                    DetailPermintaanPembelian::create([
                        'permintaan_pembelian_id' => $pp->id,
                        'part_id' => $request->tdk_terdaftar[$i]['part_id'],
                        'bom_id' => $request->tdk_terdaftar[$i]['bom_id'],
                        'jumlah' => $request->tdk_terdaftar[$i]['jumlah'],
                        'harga' => $request->tdk_terdaftar[$i]['harga'],
                        'is_terdaftar' => 0,
                    ]);
                }
            }

            return response()->json([
                'data' => 'success'
            ]);
        }
    }

    public function get_data_pp($id)
    {
        $data = array();
        if ($id != 0) {
            $pp = PermintaanPembelian::find($id);

            $data[] = array(
                'id' => $pp->id,
                'no_pp' => $pp->no_pp,
                'tgl_dibutuhkan' => $pp->tgl_dibutuhkan,
                'tgl_diminta' => $pp->tgl_diminta,
                'jenis' => $pp->jenis,
                'tujuan' => $pp->tujuan,
            );

            if ($pp->jenis == 'umum') {
                foreach ($pp->DetailPermintaanPembelian as $key_p => $dp) {
                    $data['aset'][$key_p] = array(
                        'id' => $dp->id,
                        'nama' => $dp->Aset->nama,
                        'jumlah' => $dp->jumlah,
                        'es_harga' => $dp->estimasi_harga,
                        'subtotal' =>  $dp->estimasi_harga *  $dp->jumlah,
                        'link' => $dp->link,
                        'status' => 'proses'
                    );
                }
            } else {
                foreach ($pp->DetailPPterdaftar('true') as $key_p => $dp) {
                    $data['part_terdaftar'][$key_p] = array(
                        'id' => $dp->id,
                        'nama' => $dp->Part->nama,
                        'jumlah' => $dp->jumlah,
                        'es_harga' => $dp->estimasi_harga,
                        'subtotal' =>  $dp->estimasi_harga *  $dp->jumlah,
                        'status' => 'proses'
                    );
                }
                foreach ($pp->DetailPPterdaftar('false') as $key_p => $dp) {
                    $data['part_tidak_terdaftar'][$key_p] = array(
                        'id' => $dp->id,
                        'nama' => $dp->Part->nama,
                        'jumlah' => $dp->jumlah,
                        'es_harga' => $dp->estimasi_harga,
                        'subtotal' =>  $dp->estimasi_harga *  $dp->jumlah,
                        'status' => 'proses'
                    );
                }
            }

            return response()->json(['data' => $data]);
        } else {
            $data = PermintaanPembelian::all();
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
