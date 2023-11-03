<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailProdukRw extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_produks_rw';
    protected $fillable = ['produk_parent_id','produk_id'];

    public function Parent ()
    {
        return $this->belongsTo(Produk::class,'produk_parent_id');
    }
    public function Item ()
    {
        return $this->belongsTo(Produk::class,'produk_id');
    }
}
