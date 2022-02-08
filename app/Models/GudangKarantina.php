<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class GudangKarantina extends Model
{
    use HasFactory;

    protected $table = 't_gk';
    // protected $fillable = ['date_in', 'date_out', 'dari', 'ke', 'deskripsi', 'created_by', 'updated_by'];
    // protected static $logName = 'Gudang Karantina';
    // protected static $logAttributes = ['from.nama', 'to.nama', 'user_create.nama', 'user_update.nama', 'deskripsi'];

    function detail() {
        return $this->belongsToMany(GudangKarantinaDetail::class, 'gk_id');
    }
    function from() {
        return $this->belongsTo(Divisi::class, 'dari');
    }

    function to() {
        return $this->belongsTo(Divisi::class, 'ke');
    }

    function user_create()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function user_update()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


}
