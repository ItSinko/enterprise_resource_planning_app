<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilMeeting extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'hasil_meeting';
    protected $fillable = ['meeting_id','isi'];
}
