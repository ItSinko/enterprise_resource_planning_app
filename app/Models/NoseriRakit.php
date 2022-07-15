<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriRakit extends Model
{
    use HasFactory;

    protected $table = "tbl_noseri_rakit";

    protected $fillable = ['noseri_id', 'status', 'date_in', 'waktu_tf', 'created_at', 'updated_at', 'transfer_by'];

    function serii()
    {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_id');
    }
}
