<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoseriBrgJadiLog extends Model
{
    use HasFactory;

    protected $connection = 'erp';
    protected $table = 'noseri_brg_jadi_log';

    protected $fillable = ['noseri_id', 'data_lama', 'data_baru', 'action', 'action_by', 'acc_by', 'status', 'komentar', 'gbj_id', 'remark'];

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
}
