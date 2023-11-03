<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeriDetailRw extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'seri_detail_rw';
    protected $fillable = ['urutan','noseri_id','noseri','isi','packer'];
}
