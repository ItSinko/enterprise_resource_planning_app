<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartPenggantiPerbaikan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'part_pengganti_perbaikan';
    protected $fillable = ['m_sparepart_id', 'perbaikan_id', 'jumlah'];

    public function Sparepart(){
        return $this->belongsTo(Sparepart::class, 'm_sparepart_id');
    }

    public function Perbaikan(){
        return $this->belongsTo(Perbaikan::class, 'perbaikan_id');
    }
}
