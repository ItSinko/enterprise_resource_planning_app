<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\DetailPembelianBarangMasuk;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPenerimaanPoAset;
use App\Models\DetailPermintaanPembelian;
use App\Models\DetailPoPembelian;
use App\Models\DetailPoPembelianAset;
use App\Models\DetailPoPembelianPart;
use App\Models\DetailPPAset;
use App\Models\DetailPPBom;
use App\Models\DetailPPBomPart;
use App\Models\Divisi;
use App\Models\PembelianBarangMasuk;
use App\Models\PenerimaanPoAset;
use App\Models\PermintaanPembelian;
use App\Models\PoPembelian;
use App\Models\teknik\BillOfMaterial;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PembelianController extends Controller
{
    public function get_data_pp()
    {
        // $pp = PermintaanPembelian::all();
        // foreach ($pp as $key_pp => $pp) {
        //     $data[$key_pp] = array(
        //         'id' => $pp->id,
        //         'pp' => $pp->no_pp,
        //         'tujuan' => $pp->tujuan,
        //         'jenis' => $pp->jenis,
        //         'tgl_dibutuhkan' => $pp->tgl_dibutuhkan,
        //         'tgl_diminta' => $pp->tgl_diminta,
        //         'divisi' => $pp->Divisi->nama,
        //         'status' => $pp->Status->nama,
        //     );
        // }
        $data = array();
        $pp = DB::select("
        select pp.id ,pp.no_pp ,pp.tujuan ,pp.jenis ,pp.tgl_dibutuhkan ,pp.tgl_diminta ,ms.nama as status,d.nama as divisi,
        (select sum(dpbp.jumlah) from detail_pp_bom dpb join detail_pp_bom_part dpbp on dpb.id = dpbp.detail_pp_bom_id where dpb.permintaan_pembelian_id = pp.id ) as jumlah_pp,
        (select  coalesce (sum(dppp.jumlah),0) from po_pembelian pp2 join detail_po_pembelian_part dppp  on dppp.po_pembelian_id = pp2.id where pp2.permintaan_pembelian_id = pp.id)
        as jumlah_po,
        (select round( jumlah_po / jumlah_pp * 100)) as status_persen
        from permintaan_pembelian as pp
        join m_status ms on ms.id = pp.status_id
        join divisi d ON d.id = pp.divisi_id
        where pp.jenis = 'part'
        union
        select pp.id ,pp.no_pp ,pp.tujuan ,pp.jenis ,pp.tgl_dibutuhkan ,pp.tgl_diminta ,ms.nama as status ,d.nama as divisi,
        (select sum(dpa.jumlah) from detail_pp_aset dpa where dpa.permintaan_pembelian_id = pp.id) as jumlah_pp,
        (select  coalesce (sum(dppa.jumlah),0) from po_pembelian pp2 join detail_po_pembelian_aset dppa on dppa.po_pembelian_id = pp2.id where pp2.permintaan_pembelian_id = pp.id)
        as jumlah_po,
        (select round( jumlah_po / jumlah_pp * 100)) as status_persen
        from permintaan_pembelian as pp
        join m_status ms on ms.id = pp.status_id
        join divisi d ON d.id = pp.divisi_id
        where pp.jenis = 'umum'");

        foreach ($pp as $key_pp => $pp) {
            $data[$key_pp] = array(
                'id' => $pp->id,
                'pp' => $pp->no_pp,
                'tujuan' => $pp->tujuan,
                'jenis' => $pp->jenis,
                'tgl_dibutuhkan' => $pp->tgl_dibutuhkan,
                'tgl_diminta' => $pp->tgl_diminta,
                'divisi' => $pp->divisi,
                'status' => $pp->status,
                // Change status persen to integer
                'status_persen' => (int) $pp->status_persen,
            );
        }

        return response()->json(['data' => $data]);
    }

    public function update_status_pp(Request $request, $id)
    {

        if ($request->status == 'terima') {
            $pp =  PermintaanPembelian::find($id);
            $pp->status_id = 2;
            $pp->save();
            return response()->json([
                'data' => 'berhasil'
            ]);
        } elseif ($request->status == 'tolak' || $request->status == 'batal') {
            $pp =  PermintaanPembelian::find($id);
            $pp->status_id = 18;
            $pp->save();
            return response()->json([
                'data' => 'berhasil'
            ]);
        }
    }
    public function store_data_pp(Request $request)
    {
        return response()->json([
            'data' => $request->all()
        ]);
        $validator = Validator::make($request->all(), [
            'no_pp' => 'required|unique:permintaan_pembelian,no_pp',
            'tgl_dibutuhkan' => 'required',
            'divisi' => 'required',
            'tujuan' => 'required',
            'jenis' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => $validator->errors()
            ], 500);
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
                        'bom_id' => $request->part[$i]['versi']['value'],
                        'produk_id' => $request->part[$i]['produk']['value'],
                    ]);
                    if (isset($request->part[$i]['detail']['terdaftar'])) {
                        for ($j = 0; $j < count($request->part[$i]['detail']['terdaftar']); $j++) {
                            DetailPPBomPart::create([
                                'detail_pp_bom_id' => $detail_pp_bom->id,
                                'part_id' => $request->part[$i]['detail']['terdaftar'][$j]['id'],
                                'jumlah' => $request->part[$i]['detail']['terdaftar'][$j]['jumlah_kebutuhan'],
                                'harga' => $request->part[$i]['detail']['terdaftar'][$j]['harga']
                            ]);
                        }
                    }
                    if (isset($request->part[$i]['detail']['tidakTerdaftar'])) {
                        for ($j = 0; $j < count($request->part[$i]['detail']['tidakTerdaftar']); $j++) {
                            DetailPPBomPart::create([
                                'detail_pp_bom_id' => $detail_pp_bom->id,
                                'nama' => $request->part[$i]['detail']['tidakTerdaftar'][$j]['nama'],
                                'jumlah' => $request->part[$i]['detail']['tidakTerdaftar'][$j]['jumlah'],
                                'harga' => $request->part[$i]['detail']['tidakTerdaftar'][$j]['harga']
                            ]);
                        }
                    }
                }
            }

            // return response()->json([
            //     'data' => 'success'
            // ]);
        }
    }

    public function delete_data_po($id)
    {
        try {
            //code...
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function delete_data_pp_part($id)
    {
        try {
            //code...
            $part = DetailPPBom::whereHas('PermintaanPembelian', function ($q) use ($id) {
                $q->where('permintaan_pembelian_id', $id);
            })->pluck('id');

            $dpart = DetailPPBomPart::whereIN('detail_pp_bom_id', $part)->get();

            if (count($dpart) > 0) {
                DetailPPBomPart::whereIN('detail_pp_bom_id', $part)->delete();
            }

            if (count($part) > 0) {
                DetailPPBom::where('permintaan_pembelian_id', $id)->delete();
            }

            PermintaanPembelian::find($id)->delete();

            return response()->json([
                'data' => 'success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'data' => 'error'
            ]);
        }
    }
    public function delete_data_pp_umum($id)
    {
        try {
            //code...
            $aset = DetailPPAset::whereHas('PermintaanPembelian', function ($q) use ($id) {
                $q->where('permintaan_pembelian_id', $id);
            })->count();

            if ($aset > 0) {
                DetailPPAset::whereHas('PermintaanPembelian', function ($q) use ($id) {
                    $q->where('permintaan_pembelian_id', $id);
                })->delete();
            }

            PermintaanPembelian::find($id)->delete();

            return response()->json([
                'data' => 'success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function get_data_detail_bom($id)
    {
        $terdaftar = DetailPPBomPart::whereHas('DetailPPBom', function ($q) use ($id) {
            $q->where('id', $id);
        })->whereNotNull('part_id')->get();

        $belum = DetailPPBomPart::whereHas('DetailPPBom', function ($q) use ($id) {
            $q->where('id', $id);
        })->whereNotNull('nama')->get();

        $data = array();

        if (count($terdaftar) > 0) {
            foreach ($terdaftar as $key_bom => $detailpp) {
                $data['terdaftar'][$key_bom] = array(
                    'id' => $detailpp->id,
                    'nama' => $detailpp->Part->nama,
                    'jumlah' => $detailpp->jumlah,
                    'harga' => $detailpp->harga,
                );
            }
        }
        if (count($belum) > 0) {
            foreach ($belum as $key_bom => $detailpp) {
                $data['belum_terdaftar'][$key_bom] = array(
                    'id' => $detailpp->id,
                    'nama' => $detailpp->nama,
                    'jumlah' => $detailpp->jumlah,
                    'harga' => $detailpp->harga,
                );
            }
        }

        return response()->json([
            'data' => $data
        ], 200);
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
        if ($pp->PoPembelian) {
            foreach ($pp->PoPembelian as $key_po => $po) {
                $data['po'][$key_po] = array(
                    'id' => $po->id,
                    'no_po' => $po->no_po,
                    'tgl_po' => $po->tgl_po,
                    'tgl_estimasi_datang' => $po->tgl_estimasi_datang,
                    'tgl_datang' => $po->tgl_datang,
                    'status' => $po->Status->nama,

                );
            }
        }

        return response()->json(['data' => $data]);
    }

    public function update_data_pp(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'tgl_dibutuhkan' => 'required',
            'tgl_diminta' => 'required',
            'tujuan' => 'required',
            'jenis' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => 'gagal'
            ]);
        } else {
            $pp = PermintaanPembelian::find($id);
            $pp->tujuan = $request->tujuan;
            $pp->jenis = $request->jenis;
            $pp->tgl_dibutuhkan = $request->tgl_dibutuhkan;
            $pp->tgl_diminta = $request->tgl_diminta;
            $pp->save();

            if (isset($request->aset)) {
                $aset = DetailPPAset::whereHas('PermintaanPembelian', function ($q) use ($id) {
                    $q->where('permintaan_pembelian_id', $id);
                })->count();

                if ($aset > 0) {
                    DetailPPAset::whereHas('PermintaanPembelian', function ($q) use ($id) {
                        $q->where('permintaan_pembelian_id', $id);
                    })->delete();
                }

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

                $part = DetailPPBom::whereHas('PermintaanPembelian', function ($q) use ($id) {
                    $q->where('permintaan_pembelian_id', $id);
                })->pluck('id');

                $dpart = DetailPPBomPart::whereIN('detail_pp_bom_id', $part)->get();

                if (count($dpart) > 0) {
                    DetailPPBomPart::whereIN('detail_pp_bom_id', $part)->delete();
                }

                if (count($part) > 0) {
                    DetailPPBom::where('permintaan_pembelian_id', $id)->delete();
                }



                if (isset($request->part)) {
                    for ($i = 0; $i < count($request->part); $i++) {
                        $detail_pp_bom =  DetailPPBom::create([
                            'permintaan_pembelian_id' => $pp->id,
                            'bom_id' => $request->part[$i]['versi']['value'],
                            'produk_id' => $request->part[$i]['produk']['value'],
                        ]);
                        if (isset($request->part[$i]['detail']['terdaftar'])) {
                            for ($j = 0; $j < count($request->part[$i]['detail']['terdaftar']); $j++) {
                                DetailPPBomPart::create([
                                    'detail_pp_bom_id' => $detail_pp_bom->id,
                                    'part_id' => $request->part[$i]['detail']['terdaftar'][$j]['id'],
                                    'jumlah' => $request->part[$i]['detail']['terdaftar'][$j]['jumlah_kebutuhan'],
                                    'harga' => $request->part[$i]['detail']['terdaftar'][$j]['harga']
                                ]);
                            }
                        }
                        if (isset($request->part[$i]['detail']['tidakTerdaftar'])) {
                            for ($j = 0; $j < count($request->part[$i]['detail']['tidakTerdaftar']); $j++) {
                                DetailPPBomPart::create([
                                    'detail_pp_bom_id' => $detail_pp_bom->id,
                                    'nama' => $request->part[$i]['detail']['tidakTerdaftar'][$j]['nama'],
                                    'jumlah' => $request->part[$i]['detail']['tidakTerdaftar'][$j]['jumlah'],
                                    'harga' => $request->part[$i]['detail']['tidakTerdaftar'][$j]['harga']
                                ]);
                            }
                        }
                    }
                }

            }

            return response()->json([
                'data' => 'success'
            ]);
        }
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

    public function get_data_po()
    {
        $data = array();
        $po = DB::select("
        select po.id , no_po as po , tgl_po as tgl_diminta , tgl_estimasi_datang , tgl_datang as tgl_kedatangan, e.nama as ekspedisi , s.nama as supplier , k.nama as kurs , d.nama as divisi, ms.nama as status,
        (select sum(dppa.jumlah) from detail_po_pembelian_aset dppa where dppa.po_pembelian_id = po.id ) as jumlah_po,
        (select  coalesce (sum(dppa2.jumlah),0) from detail_penerimaan_po_aset dppa2 join penerimaan_po_aset ppa on dppa2.penerimaan_po_aset_id  = ppa.id
        where ppa.po_pembelian_id  = po.id ) as jumlah_diterima,
        (select round( jumlah_diterima / jumlah_po * 100)) as status_persen
        from po_pembelian po
        join permintaan_pembelian pp on po.permintaan_pembelian_id = pp.id
        join ekspedisi e  on e.id = po.ekspedisi_id
        join supplier s on s.id = po.supplier_id
        join kurs k on k.id = po.kurs_id
        join divisi d on d.id = po.divisi_id
        join m_status ms on ms.id = po.status_id
        where pp.jenis = 'umum'
        ");

        foreach ($po as $key_po => $po) {
            $data[$key_po] = array(
                'id' => $po->id,
                'po' => $po->po,
                'tgl_diminta' => $po->tgl_diminta,
                'tgl_kedatangan' => $po->tgl_kedatangan,
                'supplier' => $po->supplier,
                'divisi' => $po->divisi,
                'status' => $po->status,
                'status_persen' => $po->status_persen
            );
        }
        return response()->json(['data' => $data]);
    }

    public function delete_data_po_part($id)
    {
        try {
            //code...
            $part = DetailPoPembelianPart::whereHas('PoPembelian', function ($q) use ($id) {
                $q->where('po_pembelian_id', $id);
            })->count();

            if ($part > 0) {
                DetailPoPembelianPart::whereHas('PoPembelian', function ($q) use ($id) {
                    $q->where('po_pembelian_id', $id);
                })->delete();
            }

            PoPembelian::find($id)->delete();
            return response()->json([
                'data' => 'success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'data' => 'error'
            ]);
        }
    }
    public function delete_data_po_umum($id)
    {
        try {
            $aset = DetailPoPembelianAset::whereHas('PoPembelian', function ($q) use ($id) {
                $q->where('po_pembelian_id', $id);
            })->count();

            if ($aset > 0) {
                DetailPoPembelianAset::whereHas('PoPembelian', function ($q) use ($id) {
                    $q->where('po_pembelian_id', $id);
                })->delete();
            }
            PoPembelian::find($id)->delete();

            return response()->json([
                'data' => 'success'
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'data' => 'error'
            ]);
        }
    }
    public function update_data_po(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'permintaan_pembelian_id' => 'required',
            'no_po' => 'required|unique:po_pembelian,no_po,' . $id,
            'tgl_po' => 'required',
            'tgl_datang' => 'required',
            'ekspedisi_id' => 'required',
            'supplier_id' => 'required',
            'kurs_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => 'gagal'
            ]);
        } else {

            $po = PoPembelian::find($id);
            $po->no_po = $request->no_po;
            $po->tgl_po = $request->tgl_po;
            $po->tgl_datang = $request->tgl_datang;
            $po->tgl_estimasi_datang = $request->tgl_estimasi_datang;
            $po->ekspedisi_id = $request->ekspedisi_id;
            $po->supplier_id = $request->supplier_id;
            $po->kurs_id = $request->kurs_id;
            $po->save();

            if (isset($request->part)) {
                $part = DetailPoPembelianPart::whereHas('PoPembelian', function ($q) use ($id) {
                    $q->where('po_pembelian_id', $id);
                })->count();

                if ($part > 0) {
                    DetailPoPembelianPart::whereHas('PoPembelian', function ($q) use ($id) {
                        $q->where('po_pembelian_id', $id);
                    })->delete();
                }
                for ($i = 0; $i < count($request->part); $i++) {
                    DetailPoPembelianPart::create([
                        'po_pembelian_id' => $po->id,
                        'detail_pp_bom_part_id' => $request->part[$i]['detail_pp_bom_part_id'],
                        'jumlah' => $request->part[$i]['jumlah'],
                        'harga' => $request->part[$i]['harga'],
                        'ongkir' => $request->part[$i]['ongkir'],
                        'biaya_lain' => $request->part[$i]['biaya_lain'],
                        'konversi' => $request->part[$i]['konversi'],
                    ]);
                }
            }

            if (isset($request->aset)) {
                $aset = DetailPoPembelianAset::whereHas('PoPembelian', function ($q) use ($id) {
                    $q->where('po_pembelian_id', $id);
                })->count();

                if ($aset > 0) {
                    DetailPoPembelianAset::whereHas('PoPembelian', function ($q) use ($id) {
                        $q->where('po_pembelian_id', $id);
                    })->delete();
                }

                for ($i = 0; $i < count($request->aset); $i++) {
                    DetailPoPembelianAset::create([
                        'po_pembelian_id' => $po->id,
                        'detail_pp_aset_id' => $request->aset[$i]['detail_pp_aset_id'],
                        'jumlah' => $request->aset[$i]['jumlah'],
                        'harga' => $request->aset[$i]['harga'],
                        'ongkir' => $request->aset[$i]['ongkir'],
                        'biaya_lain' => $request->aset[$i]['biaya_lain'],
                        'konversi' => $request->aset[$i]['konversi'],
                    ]);
                }
            }

            return response()->json([
                'data' => 'success'
            ]);
        }
    }

    public function terima_po(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'surat_jalan' => 'required',
            'id.*' => 'required',
            'jumlah.*' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => 'gagal'
            ]);
        } else {
            if ($request->aset) {
                $p = PenerimaanPoAset::create([
                    'po_pembelian_id' => $request->po_pembelian_id,
                    'surat_jalan' => $request->surat_jalan,
                    'file' => $request->file
                ]);

                for ($i = 0; $i < count($request->aset); $i++) {
                    $data = DetailPenerimaanPoAset::create([
                        'penerimaan_po_aset_id' => $p->id,
                        'detail_po_pembelian_id' => $request->aset[$i]['id'],
                        'jumlah' => $request->aset[$i]['jumlah']
                    ]);
                }
                return response()->json([
                    'data' => 'success'
                ]);
            }
        }
    }

    public function store_data_po(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'permintaan_pembelian_id' => 'required',
            'no_po' => 'required|unique:po_pembelian,no_po',
            'tgl_po' => 'required',
            'tgl_datang' => 'required',
            'ekspedisi_id' => 'required',
            'supplier_id' => 'required',
            'kurs_id' => 'required',
            'divisi_id' => 'required',
            'status_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => 'gagal'
            ]);
        } else {
            // dd($request->all());
            $po =  PoPembelian::create([
                'permintaan_pembelian_id' => $request->permintaan_pembelian_id,
                'no_po' => $request->no_po,
                'tgl_po' => $request->tgl_po,
                'tgl_datang' => $request->tgl_datang,
                'tgl_estimasi_datang' => $request->tgl_estimasi_datang,
                'ekspedisi_id' => $request->ekspedisi_id,
                'supplier_id' => $request->supplier_id,
                'kurs_id' => $request->kurs_id,
                'divisi_id' => $request->divisi_id,
                'status_id' => $request->status_id,
            ]);


            if (isset($request->aset)) {
                for ($i = 0; $i < count($request->aset); $i++) {
                    DetailPoPembelianAset::create([
                        'po_pembelian_id' => $po->id,
                        'detail_pp_aset_id' => $request->aset[$i]['detail_pp_aset_id'],
                        'jumlah' => $request->aset[$i]['jumlah'],
                        'harga' => $request->aset[$i]['harga'],
                        'ongkir' => $request->aset[$i]['ongkir'],
                        'biaya_lain' => $request->aset[$i]['biaya_lain'],
                        'konversi' => $request->aset[$i]['konversi'],
                    ]);
                }
            }

            if (isset($request->part)) {
                for ($i = 0; $i < count($request->part); $i++) {
                    DetailPoPembelianPart::create([
                        'po_pembelian_id' => $po->id,
                        'detail_pp_bom_part_id' => $request->part[$i]['detail_pp_bom_part_id'],
                        'jumlah' => $request->part[$i]['jumlah'],
                        'harga' => $request->part[$i]['harga'],
                        'ongkir' => $request->part[$i]['ongkir'],
                        'biaya_lain' => $request->part[$i]['biaya_lain'],
                        'konversi' => $request->part[$i]['konversi'],
                    ]);
                }
            }

            return response()->json([
                'data' => 'success'
            ]);
        }
    }

    public function get_detail_po($id)
    {
        $po = PoPembelian::find($id);
        $data['pp_header'] = array(
            'nopo' => $po->PermintaanPembelian->no_pp,
            'jenis_barang' => $po->PermintaanPembelian->jenis,
            'divisi' => $po->PermintaanPembelian->Divisi->nama,
            'tgl_dibutuhkan' => $po->PermintaanPembelian->tgl_dibutuhkan,
            'tgl_diminta' => $po->PermintaanPembelian->tgl_dibutuhkan,
            'tujuan' => $po->PermintaanPembelian->tujuan,

        );
        $data['po_header'] = array(
            'nopo' => $po->no_po,
            'tgl_po' => $po->tgl_po,
            'tgl_datang' => $po->tgl_datang,
            'ekspedisi' => $po->Ekspedisi->nama
        );
        $data['supplier_header'] = array(
            'nama' => $po->Supplier->nama,
            'alamat' => $po->Supplier->alamat,
            'email' => $po->Supplier->email,
            'telp' => $po->Supplier->telp,
        );

        if ($po->DetailPoPart) {
            $part = DetailPoPembelianPart::with(['DetailPPBomPart.Part', 'DetailPPBomPart.DetailPPBom.Produk'])->where('po_pembelian_id', $id)->get();

            foreach ($part as $key => $d) {
                $data['data'][$key] = array(
                    'id' => $d->id,
                    'nama' => $d->DetailPPBomPart->part_id == null ? $d->DetailPPBomPart->nama : $d->DetailPPBomPart->Part->nama,
                    // 'nama' => $d->DetailPPBomPart->part_id == null ? $d->DetailPPBomPart->nama : $d->DetailPPBomPart->Part->nama,
                    'jumlah' => $d->jumlah,
                    'harga' => $d->harga,
                    'ongkir' => $d->ongkir,
                    'biaya_lain' => $d->biaya_lain,
                    'konversi' => $d->konversi,
                    'produk_id' => $d->DetailPPBomPart->DetailPPBom->Produk->nama
                );
            }
        }
        if ($po->DetailPoAset) {
            $asset = DetailPoPembelianAset::with(['DetailPPAset'])->where('po_pembelian_id', $id)->get();
            foreach ($asset as $key => $d) {
                $data['data'][$key] = array(
                    'id' => $d->id,
                    'nama' => $d->DetailPPAset->Aset->nama,
                    'jumlah' => $d->jumlah,
                    'harga' => $d->harga,
                    'ongkir' => $d->ongkir,
                    'biaya_lain' => $d->biaya_lain,
                    'konversi' => $d->konversi,
                );
            }
        }



        return response()->json(['data' => $data]);
    }

    public function update_status_po(Request $request, $id)
    {

        if ($request->status == 'terima') {
            $po =  PoPembelian::find($id);
            $po->status_id = 2;
            $po->save();
            return response()->json([
                'data' => 'berhasil'
            ]);
        } elseif ($request->status == 'tolak' || $request->status == 'batal') {
            $po =  PoPembelian::find($id);
            $po->status_id = 18;
            $po->save();
            return response()->json([
                'data' => 'berhasil'
            ]);
        }
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
