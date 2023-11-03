<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PenjualanProduk extends Model
{
    protected $connection = 'erp';
    protected $table = 'penjualan_produk';
    protected $fillable = ['nama_alias', 'nama', 'harga', 'jenis', 'status', 'is_aktif'];

    public function DetailPesanan()
    {
        return $this->hasMany(DetailPesanan::class);
    }
    public function DetailPesananDsb()
    {
        return $this->hasMany(DetailPesananDsb::class);
    }
    public function Produk()
    {
        return $this->belongsToMany(Produk::class, 'detail_penjualan_produk')
            ->withPivot('jumlah')
            ->orderBy('id', 'ASC');
    }

    public function DetailRencanPenjualan()
    {
        return $this->hasMany(DetailRencanaPenjualan::class);
    }

}
