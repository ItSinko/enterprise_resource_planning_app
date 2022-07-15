<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriPerbaikan extends Model
{
    use HasFactory;

    protected $table = 'tbl_noseri_gk';
    protected $fillable = ['gk_id', 'noseri_id', 'is_aktif', 'is_ready', 'status', 'created_by', 'updated_by'];

    function noseri()
    {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_id');
    }

    function perbaikan()
    {
        return $this->hasMany(NoseriLog::class);
    }
}
