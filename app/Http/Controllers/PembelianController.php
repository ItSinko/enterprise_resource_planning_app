<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelianBarangMasuk;
use App\Models\DetailPenerimaanBarang;
use App\Models\DetailPermintaanPembelian;
use App\Models\PembelianBarangMasuk;
use App\Models\PermintaanPembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function get_data_pp($id)
    {
        $data = array();
        if ($id != 0) {
            $pp = DetailPermintaanPembelian::where('permintaan_pembelian_id', $id)->get();
            foreach ($pp as $key_p => $p) {
                if ($p->jenis == 'part') {
                    $nama = $p->BillOfMaterial->nama;
                    $produk = $p->BillOfMaterial->Produk->nama;

                    $data[$key_p] = array(
                        'id' => $p->id,
                        'nama' => $nama,
                        'produk' => $produk,

                    );

                    foreach ($p->DetailPermintaanPembelianProduk as $key_dpp => $dpp) {

                        $data[$key_p]['part'][$key_dpp] = array(
                            'id' => $dpp->id,
                            'nama' => $dpp->detail_bom_id == '' ? $dpp->Sparepart->nama : $dpp->DetailBillOfMaterial->Sparepart->nama,
                            'jumlah' => $dpp->jumlah,
                            'harga' => $dpp->harga,
                            'status' => $dpp->detail_bom_id == '' ? 'tidak terdaftar' : 'terdaftar',
                        );
                    }
                } else {
                    $nama = $p->Aset->nama;

                    $data[] = array(
                        'id' => $p->id,
                        'nama' => $nama,
                    );
                }
            }
        } else {
            $data = PermintaanPembelian::all();
        }

        return response()->json(['data' => $data]);
    }
}
