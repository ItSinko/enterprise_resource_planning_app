<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenerimaanPoAset extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_penerimaan_po_aset';
    protected $fillable = ['penerimaan_po_aset_id', 'detail_po_pembelian_id', 'jumlah'];


    public function DetailPoPembelianAset()
    {
        return $this->belongsTo(DetailPoPembelianAset::class, 'detail_po_pembelian_id');
    }

    public function PenerimaanPoAset()
    {
        return $this->belongsTo(PenerimaanPoAset::class, 'penerimaan_po_aset_id');
    }
}
