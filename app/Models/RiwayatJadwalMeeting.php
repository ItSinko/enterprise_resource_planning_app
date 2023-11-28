<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatJadwalMeeting extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'riwayat_meeting';
    protected $fillable = ['meeting_id','isi','ket','user_id'];

    public function JadwalMeeting()
    {
        return $this->belongsTo(JadwalMeeting::class,'meeting_id');
    }

}
