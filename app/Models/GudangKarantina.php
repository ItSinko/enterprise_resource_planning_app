<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GudangKarantina extends Model
{
    use HasFactory;

    protected $table = 'gdg_karantina';
    protected $fillable = ['gbj_id', 'sparepart_id', 'stok', 'created_by', 'updated_by'];

    function unit()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gbj_id');
    }

    function part()
    {
        return $this->belongsTo(Sparepart::class, 'sparepart_id');
    }

}
