<?php

namespace App\Models;

use App\Models\teknik\BillOfMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $connection = 'erp';
    protected $table = 'produk';
    protected $fillable = ['kelompok_produk_id', 'merk', 'tipe', 'nama', 'nama_coo', 'satuan', 'coo', 'no_akd', 'ket', 'status'];

    function bom()
    {
        return $this->hasMany(BillOfMaterial::class);
    }
    public function PenjualanProduk()
    {
        return $this->belongsToMany(PenjualanProduk::class, 'detail_penjualan_produk')
            ->withPivot('jumlah')
            ->withTimestamps();
    }
    public function GudangBarangJadi()
    {
        return $this->hasMany(GudangBarangJadi::class);
    }
    public function KelompokProduk()
    {
        return $this->belongsTo(KelompokProduk::class, 'kelompok_produk_id');
    }

    public function Satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    function product()
    {
        return $this->belongsTo(Mproduk::class, 'produk_id');
    }
}
