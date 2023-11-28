<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetiRw extends Model
{
    protected $connection = 'erp';
    protected $table = 'peti_rw';
    protected $fillable = ['no_urut','noseri_id','noseri','packer','jadwal_perakitan_rw_id'];

    function User()
    {
        return $this->belongsTo(User::class, 'packer');
    }

}
