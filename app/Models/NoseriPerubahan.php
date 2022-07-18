<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriPerubahan extends Model
{
    use HasFactory;

    protected $table = 'tbl_noseri_perubahan';

    protected $fillable = ['gdg_brg_jadi_id','noseri_id', 'data_lama', 'data_baru', 'action', 'action_by', 'acc_by', 'status', 'komentar', 'remark'];

    function noseri()
    {
        return $this->belongsTo(NoseriBarangJadi::class, 'noseri_id');
    }

    function serii()
    {
        return $this->hasMany(NoseriBarangJadi::class, 'gdg_barang_jadi_id')->where('gdg_barang_jadi_id', GudangBarangJadi::class)->groupBy('gdg_barang_jadi_id');
    }

    function actionn()
    {
        return $this->belongsTo(User::class, 'action_by');
    }

    function acc()
    {
        return $this->belongsTo(User::class, 'acc_by');
    }

    function gudang()
    {
        return $this->belongsTo(GudangBarangJadi::class, 'gdg_brg_jadi_id');
    }
}
