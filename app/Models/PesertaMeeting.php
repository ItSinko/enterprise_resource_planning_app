<?php

namespace App\Models;

use App\Models\kesehatan\Karyawan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesertaMeeting extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'peserta_meeting';
    protected $fillable = ['meeting_id','karyawan_id','status','ket'];


    public function JadwalMeeting()
    {
        return $this->belongsTo(JadwalMeeting::class, 'meeting_id');
    }

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
