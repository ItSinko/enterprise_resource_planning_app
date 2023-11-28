<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalMeeting extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'jadwal_meeting';
    protected $fillable = ['urutan', 'judul', 'tgl_meeting','mulai','selesai','lokasi','status','notulen','moderator','deskripsi'];

    public function PesertaMeeting()
    {
        return $this->hasMany(PesertaMeeting::class,'meeting_id');
    }

    public function RiwayatJadwalMeeting()
    {
        return $this->hasMany(RiwayatJadwalMeeting::class,'meeting_id');
    }

}
