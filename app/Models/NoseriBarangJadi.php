<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriBarangJadi extends Model
{
    use HasFactory;

    protected $table = "tbl_noseri";

    protected $fillable = ['gdg_barang_jadi_id', 'jadwal_id', 'noseri', 'layout_id', 'dari', 'is_aktif', 'is_ready', 'is_rakit', 'is_repair', 'is_change', 'is_delete', 'log_id', 'created_by', 'updated_by'];

    function from()
    {
        return $this->belongsTo(Divisi::class, 'dari');
    }

    function gudang()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_barang_jadi_id');
    }

    function NoseriTGbj()
    {
        return $this->hasMany(NoseriTGbj::class);
    }

    function layout()
    {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    function perakitan()
    {
        return $this->belongsTo(JadwalPerakitan::class, 'jadwal_id');
    }

    function noseri_log()
    {
        return $this->hasOne(NoseriBrgJadiLog::class, 'noseri_id');
    }

    function posisi()
    {
        return $this->belongsTo(Divisi::class, 'log_id');
    }

}
