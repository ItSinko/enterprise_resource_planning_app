<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPPBomPart extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'detail_pp_bom_part';
    protected $fillable = ['detail_pp_bom_id', 'part_id', 'nama', 'jumlah', 'harga'];

    public function Part()
    {
        return $this->belongsTo(Sparepart::class, 'part_id');
    }
    public function DetailPPBom()
    {
        return $this->belongsTo(DetailPPBom::class, 'detail_pp_bom_id', 'id');
    }
    public function DetailPOPembelianPart()
    {
        return $this->hasMany(DetailPOPembelianPart::class);
    }
}
