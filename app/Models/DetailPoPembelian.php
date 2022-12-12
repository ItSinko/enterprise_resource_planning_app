<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPoPembelian extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_po_pembelian';


    public function PoPembelian()
    {
        return $this->belongsTo(PoPembelian::class, 'po_pembelian_id');
    }

    public function Aset()
    {
        return $this->belongsTo(DetailPermintaanPembelian::class, 'aset_id');
    }
}
