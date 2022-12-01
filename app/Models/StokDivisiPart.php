<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokDivisiPart extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'stok_divisi_part';
    protected $fillable = ['part_id', 'divisi_id'];

    public function Divisi()
    {
        return $this->belongsTo(Divisi::class, 'divisi_id', 'id');
    }

    public function Sparepart()
    {
        return $this->belongsTo(Sparepart::class, 'part_id', 'id');
    }
    public function DetailStokDivisiPart()
    {
        return $this->hasMany(DetailStokDivisiPart::class, 'stok_divisi_part_id', 'id');
    }
}
