<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananProdukDsb extends Model
{
    protected $connection = 'erp';
    protected $table = 'detail_pesanan_produk_dsb';
    protected $fillable = ['detail_pesanan_dsb_id', 'gudang_barang_jadi_id'];
    public $timestamps = false;

    public function GudangBarangJadi()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gudang_barang_jadi_id');
    }
    public function DetailPesananDsb()
    {
        return $this->belongsTo(DetailPesananDsb::class, 'detail_pesanan_dsb_id');
    }
    public function getJumlahPesanan()
    {
        $id = $this->id;
        $produk_id = $this->GudangBarangJadi->produk_id;
        $s = DetailPesananDsb::whereHas('DetailPesananProdukDSB', function ($q) use ($id) {
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
}
