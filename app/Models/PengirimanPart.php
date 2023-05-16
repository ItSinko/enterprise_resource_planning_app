<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengirimanPart extends Model
{
    use HasFactory;
    protected $table = 'pengiriman_part';
    protected $fillable = ['pengiriman_id', 'm_sparepart_id', 'jumlah'];

    public function Sparepart(){
        return $this->belongsTo(Sparepart::class, 'm_sparepart_id');
    }

    public function Pengiriman(){
        return $this->belongsTo(Pengiriman::class, 'pengiriman_id');
    }
}
