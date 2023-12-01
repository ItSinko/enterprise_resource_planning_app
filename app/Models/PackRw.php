<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackRw extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'pack_rw';
    protected $fillable = ['pack_rw_head_id','noseri_id','noseri','user_id'];
}
