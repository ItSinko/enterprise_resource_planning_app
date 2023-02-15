<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPoPembelianPart extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_po_pembelian_part';
    protected $fillable = ['po_pembelian_id', 'detail_pp_bom_part_id', 'jumlah', 'harga', 'ongkir', 'biaya_lain', 'konversi'];

    public function PoPembelian()
    {
        return $this->belongsTo(PoPembelian::class, 'po_pembelian_id');
    }

    public function DetailPPBomPart()
    {
        return $this->belongsTo(DetailPPBomPart::class, 'detail_pp_bom_part_id');
    }
}
