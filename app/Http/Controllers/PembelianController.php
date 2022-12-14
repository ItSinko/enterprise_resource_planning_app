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
use Illuminate\Http\Request;

class PembelianController extends Controller
{
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
