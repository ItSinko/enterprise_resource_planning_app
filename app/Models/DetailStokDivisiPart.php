<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailStokDivisiPart extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_stok_divisi_part';
    protected $fillable = ['stok_divisi_part_id', 'lot_id', 'stok_order', 'stok_onhand'];

    public function LotNumber()
    {
        return $this->belongsTo(LotNumber::class, 'lot_id', 'id');
    }
    public function StokDivisiPart()
    {
        return $this->belongsTo(StokDivisiPart::class, 'stok_divisi_part_id', 'id');
    }
    public function TFProduksi()
    {
        return $this->hasMany(TFProduksi::class);
    }
}
