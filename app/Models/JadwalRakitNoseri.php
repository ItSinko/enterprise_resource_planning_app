<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRakitNoseri extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "jadwal_rakit_noseri";

    protected $fillable = ['unit','th','bln','kedatangan','urutan','jadwal_id', 'noseri', 'status', 'date_in', 'waktu_tf', 'created_at', 'updated_at', 'no_bppb'];

    function header()
    {
        return $this->belongsTo(JadwalPerakitan::class, 'jadwal_id');
    }
}
