<?php

namespace App\Models\inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_log';
    protected $connection = 'erp';
    protected $fillable = ['tipe', 'header','subjek', 'response', 'user_id'];
}
