<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriBarangJadi extends Model
{
    use HasFactory;

    protected $connection = 'erp';
    protected $table = "noseri_barang_jadi";

    protected $fillable = ['is_aktif', 'is_ready', 'is_delete', 'used_by', 'layout_id', 'gdg_barang_jadi_id', 'dari', 'noseri', 'jenis', 'created_by', 'is_change'];

    function from()
    {
        return $this->belongsTo(Divisi::class, 'dari');
    }
    function to()
    {
        return $this->belongsTo(Divisi::class, 'ke');
    }
    function gudang()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_barang_jadi_id');
    }
    function NoseriTGbj()
    {
        return $this->hasMany(NoseriTGbj::class, 'noseri_id');
    }

    function layout()
    {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'used_by');
    }

    function noseri_log()
    {
        return $this->hasOne(NoseriBrgJadiLog::class, 'noseri_id');
    }
}
