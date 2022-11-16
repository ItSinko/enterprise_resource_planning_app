<?php

namespace App\Models;


use App\Models\teknik\DetailBillOfMaterial;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satuan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'm_satuan';
    protected $fillable = ['nama'];

    function detail_bom()
    {
        return $this->hasMany(DetailBillOfMaterial::class);
    }
    function sparepart()
    {
        return $this->hasMany(Sparepart::class);
    }
    function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
