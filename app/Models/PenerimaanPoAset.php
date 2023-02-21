<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanPoAset extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'penerimaan_po_aset';
    protected $fillable = ['po_pembelian_id', 'surat_jalan', 'file'];


    public function PoPembelian()
    {
        return $this->belongsTo(PoPembelian::class, 'po_pembelian_id');
    }
}
