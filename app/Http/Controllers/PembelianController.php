<?php

namespace App\Http\Controllers;

use App\Models\DetailPembelianBarangMasuk;
use App\Models\DetailPenerimaanBarang;
use App\Models\PembelianBarangMasuk;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function get_data_sparepart($id)
    {
        $detail = DetailPenerimaanBarang::where('m_sparepart_id', $id)->get();
        foreach ($detail as $key_part => $d) {
            $data[$key_part] = array(
                'id' => $d->id,
                'no_lot' => $d->PenerimaanBarang->LotNumber->number,
                'jumlah' => $d->jumlah
            );
        }
        return response()->json([
            'data' => $data
        ]);
    }
}
