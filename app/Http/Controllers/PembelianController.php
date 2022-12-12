<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelianBarangMasuk;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPermintaanPembelian;
use App\Models\DetailPoPembelian;
use App\Models\PembelianBarangMasuk;
use App\Models\PermintaanPembelian;
use App\Models\PoPembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function get_data_pp($id)
    {
        // $data = array();
        // if ($id != 0) {
        //     $pp = DetailPermintaanPembelian::where('permintaan_pembelian_id', $id)->get();
        //     foreach ($pp as $key_p => $p) {
        //         if ($p->jenis == 'part') {
        //             $nama = $p->BillOfMaterial->nama;
        //             $produk = $p->BillOfMaterial->Produk->nama;

        //             $data[$key_p] = array(
        //                 'id' => $p->id,
        //                 'nama' => $nama,
        //                 'produk' => $produk,

        //             );

        //             foreach ($p->DetailPermintaanPembelianProduk as $key_dpp => $dpp) {

        //                 $data[$key_p]['part'][$key_dpp] = array(
        //                     'id' => $dpp->id,
        //                     'nama' => $dpp->detail_bom_id == '' ? $dpp->Sparepart->nama : $dpp->DetailBillOfMaterial->Sparepart->nama,
        //                     'jumlah' => $dpp->jumlah,
        //                     'harga' => $dpp->harga,
        //                     'status' => $dpp->detail_bom_id == '' ? 'tidak terdaftar' : 'terdaftar',
        //                 );
        //             }
        //         } else {
        //             $nama = $p->Aset->nama;

        //             $data[] = array(
        //                 'id' => $p->id,
        //                 'nama' => $nama,
        //             );
        //         }
        //     }
        // } else {
        //     $data = PermintaanPembelian::all();
        // }

        // return response()->json(['data' => $data]);
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

        // $dpp = DetailPermintaanPembelian::with('DetailPermintaanPembelianProduk')->where('permintaan_pembelian_id', 1)->get();
        // foreach ($dpp as $key_a => $d) {
        //     $data[$key_a] = array(
        //         'id' => $d->bom_id
        //     );
        //     foreach ($d->DetailPermintaanPembelianProdukPO() as $key_b => $e) {
        //         $data[$key_a]['detail'][$key_b] = array(
        //             'id' => $e->id
        //         );
        //     }
        // }

        // return response()->json(['data' => $data]);

        $dpp = PoPembelian::find(1);


        $data[] = array(
            'id' => $dpp->id,
            'nopo' => $dpp->no_po
        );

        foreach ($dpp as $d) {
        }

        return response()->json(['data' => $data]);
    }
}
