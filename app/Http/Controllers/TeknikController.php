<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\teknik\BillOfMaterial;
use App\Models\teknik\DetailBillOfMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TeknikController extends Controller
{
    public function bom_detail($id)
    {
        return view('page.teknik.bom.detail', ['id' => $id]);
    }

    public function bom_data_produk($id)
    {
        return view('page.teknik.bom.data.produk', ['id' => $id]);
    }

    public function get_data_bom()
    {
        $produk = Produk::whereHas('bom')->get();
        $data = array();

        foreach ($produk as $key_produk => $p) {
            $data[$key_produk] = array(
                'produk' => $p->nama,
                'detail' => array()
            );

            foreach ($p->bom as $key_bom => $b) {
                $data[$key_produk]['detail'][$key_bom] = array(
                    'id' => $b->id,
                    'kode' => $b->kode,
                    'nama' => $b->nama,
                    'status' => $b->is_aktif == 1 ? 'Aktif' : 'Tidak Aktif',
                    'tahun_pembuatan' => $b->created_at->format("Y")
                );
            }
        }
        return response()->json(['data' => $data]);
    }

    public function get_detail_bom($id)
    {
        $bom = BillOfMaterial::where('id', $id)->get();
        $data = array();
        foreach ($bom as $key_bom => $b) {
            $data[$key_bom] = array(
                'header' => array(
                    'produk' => $b->Produk->nama,
                    'nama_bom' => $b->nama,
                    'kode_bom' => $b->kode,
                    'tahun_pembuatan' => $b->created_at->format("Y") ,
                    'status' => $b->is_aktif == 1 ? 'Aktif' : 'Tidak Aktif',
                ),
                'data_tabel' => array(),
            );

            foreach ($b->detail_bom as $key_detail_bom => $d) {
                $data[$key_bom]['data_tabel'][$key_detail_bom] = array(
                    'id' => $d->id,
                    'kode' => $d->Sparepart->kode,
                    'nama' => $d->Sparepart->nama,
                    'jumlah' => $d->jumlah
                );
            }
        }
        return response()->json(['data' => $data]);
    }
}
