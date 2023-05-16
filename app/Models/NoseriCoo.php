<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriCoo extends Model
{
    protected $connection = 'erp';
    protected $table = 'noseri_coo';
    protected $fillable = ['no_coo', 'nama', 'jabatan', 'noseri_logistik_id', 'ket', 'tgl_kirim', 'catatan', 'tahun'];

    public function NoseriDetailLogistik()
    {
        return $this->belongsTo(NoseriDetailLogistik::class, 'noseri_logistik_id');
    }
}
