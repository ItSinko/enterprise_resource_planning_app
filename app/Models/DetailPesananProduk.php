<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananProduk extends Model
{
    protected $connection = 'erp';
    protected $table = 'detail_pesanan_produk';
    protected $fillable = ['detail_pesanan_id', 'gudang_barang_jadi_id', 'checked_by', 'status_cek'];
    public $timestamps = false;

    public function GudangBarangJadi()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gudang_barang_jadi_id');
    }
    public function DetailPesanan()
    {
        return $this->belongsTo(DetailPesanan::class, 'detail_pesanan_id');
    }
    public function NoseriDetailPesanan()
    {
        return $this->hasMany(NoseriDetailPesanan::class);
    }
    public function DetailLogistik()
    {
        return $this->hasMany(DetailLogistik::class, 'detail_pesanan_produk_id');
    }
    public function TFProduksiDetail()
    {
        return $this->hasMany(TFProduksiDetail::class, 'detail_pesanan_produk_id');
    }

    public function getJumlahProgress(){
        $id = $this->id;
        $data = DetailPesananProduk::where('id', $id)->addSelect(['count_gudang' => function($q){
            $q->selectRaw('count(t_gbj_noseri.id)')
                ->from('t_gbj_noseri')
                ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->whereColumn('t_gbj_detail.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                ->limit(1);
        }, 'count_qc' => function($q){
            $q->selectRaw('count(noseri_detail_pesanan.id)')
                ->from('noseri_detail_pesanan')
                ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                ->where('status', 'ok')
                ->limit(1);
        }, 'count_log' => function($q){
            $q->selectRaw('count(noseri_logistik.id)')
                ->from('noseri_logistik')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->whereColumn('noseri_detail_pesanan.detail_pesanan_produk_id', 'detail_pesanan_produk.id')
                ->limit(1);
        }, 'count_jumlah' => function($q){
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
            ->from('detail_pesanan')
            ->join('detail_penjualan_produk', 'detail_pesanan.penjualan_produk_id', '=', 'detail_penjualan_produk.penjualan_produk_id')
            ->join('gdg_barang_jadi', 'gdg_barang_jadi.produk_id', '=', 'detail_penjualan_produk.produk_id')
            ->whereColumn('detail_pesanan.id', 'detail_pesanan_produk.detail_pesanan_id')
            ->whereColumn('gdg_barang_jadi.id', 'detail_pesanan_produk.gudang_barang_jadi_id')
            ->limit(1);
        }])->with('GudangBarangJadi.Produk')->first();

        return $data;
    }

    public function getJumlahPesanans()
    {
        $id = 1;
        $jumlah = 0;
        $data = DetailPesananProduk::where('detail_pesanan_id', $id)->get();
        foreach ($data as $d) {
            $jumlah += $d->jumlah;
        }
        return $jumlah;
    }

    public function getJumlahKirim()
    {
        $id = $this->id;
        $jumlah = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
            $q->where('id', $id);
        })->count();
        return $jumlah;
    }

    public function getJumlahPesanan()
    {
        $id = $this->id;
        $produk_id = $this->GudangBarangJadi->produk_id;
        $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();
        $jumlah = 0;
        foreach ($s as $i) {
            foreach ($i->PenjualanProduk->Produk as $j) {
                if ($j->id == $produk_id) {
                    $jumlah = $i->jumlah * $j->pivot->jumlah;
                }
            }
        }
        return $jumlah;
    }

    public function getJumlahCek()
    {
        $id = $this->id;
        $jumlah = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('id', $id);
        })->count();
        return $jumlah;
    }

    function status()
    {
        return $this->belongsTo(Status::class, 'status_cek');
    }

    public function LaporanQcProduk($hasil, $tgl_awal, $tgl_akhir)
    {
        $id = $this->id;
        $res = "";
        if ($hasil != "semua") {
            $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where([['status', '=', $hasil], ['detail_pesanan_produk_id', '=', $id]])->get();
        } else {
            $res = NoseriDetailPesanan::whereBetween('tgl_uji', [$tgl_awal, $tgl_akhir])->where('detail_pesanan_produk_id', $id)->get();
        }
        return $res;
    }
}
