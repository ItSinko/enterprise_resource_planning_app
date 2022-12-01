<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotNumber extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'lot_number';
    protected $fillable = ['number'];

    public function PenerimaanBarang()
    {
        return $this->hasMany(PenerimaanBarang::class, 'lot_id', 'id');
    }
    public function DetailStokDivisiPart()
    {
        return $this->hasMany(DetailStokDivisiPart::class, 'lot_id', 'id');
    }
}
