<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoPembelian extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'po_pembelian';

    function PermintaanPembelian()
    {
        return $this->belongsTo(PermintaanPembelian::class, 'permintaan_pembelian_id');
    }
    public function DetailPoPembelian()
    {
        return $this->hasMany(DetailPoPembelian::class);
    }
    public function Ekspedisi()
    {
        return $this->belongsTo(Ekspedisi::class, 'ekspedisi_id');
    }
    public function Supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
