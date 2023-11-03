<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalRakitNoseriRw extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "jadwal_rakit_noseri_rw";

    protected $fillable = ['jadwal_id', 'noseri','noseri_id', 'status', 'date_in', 'waktu_tf', 'created_at', 'updated_at', 'no_bppb','th_seri','bln_seri','kedatangan_seri','urutan'];

    function header()
    {
        return $this->belongsTo(JadwalPerakitanRw::class, 'jadwal_id');
    }
}
