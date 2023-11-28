<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HasilNotulen extends Model
{
    use HasFactory;
    protected $connection = 'erp_meeting';
    protected $table = 'hasil_notulen';
    protected $fillable = ['meeting_id','pic_id','uraian','hasil','verif_id','ket','checked_at'];


}
