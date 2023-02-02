<?php

namespace App\Models\inventory;;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Target extends Model
{
    use HasFactory;

    protected $table = 'target_kalibrasi';
    protected $connection = 'erp_kalibrasi';
    protected $primaryKey = 'id_target_kalibrasi';
    protected $fillable = ['user_id','target','progres','created_by'];
}
