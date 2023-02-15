<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPoPembelianAset extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_po_pembelian_aset';
    protected $fillable = ['po_pembelian_id', 'detail_pp_aset_id', 'jumlah', 'harga', 'ongkir', 'biaya_lain', 'konversi'];



    public function PoPembelian()
    {
        return $this->belongsTo(PoPembelian::class, 'po_pembelian_id');
    }

    public function DetailPPAset()
    {
        return $this->belongsTo(DetailPPAset::class, 'detail_pp_aset_id');
    }
}
