<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanNoseri extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'pengiriman_noseri';
    protected $fillable = ['pengiriman_id', 'noseri_barang_jadi_id'];

    public function NoseriBarangJadi(){
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_barang_jadi_id');
    }

    public function Pengiriman(){
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id');
    }
}
