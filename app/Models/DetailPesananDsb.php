<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPesananDsb extends Model
{
    protected $connection = 'erp';
    protected $table = 'detail_pesanan_dsb';
    protected $fillable = ['pesanan_id', 'penjualan_produk_id', 'jumlah', 'harga', 'ongkir','ppn'];

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }
    public function PenjualanProduk()
    {
        return $this->belongsTo(PenjualanProduk::class, 'penjualan_produk_id');
    }
    public function DetailPesananProdukDsb()
    {
        return $this->hasMany(DetailPesananProdukDsb::class);
    }
    public function NoseriDsb()
    {
        return $this->hasMany(NoseriDsb::class,'detail_pesanan_dsb');
    }
}
