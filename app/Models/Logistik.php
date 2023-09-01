<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logistik extends Model
{
    protected $connection = 'erp';
    protected $table = 'logistik';
    //protected $fillable = ['ekspedisi_id', 'status_id', 'nosurat', 'noresi', 'tgl_kirim', 'nama_pengirim',];
     protected $fillable = ['ekspedisi_id', 'status_id', 'nosurat', 'noresi', 'tgl_kirim', 'nama_pengirim','nama_up','telp_up','tujuan_pengiriman','alamat_pengiriman','kemasan','dimensi','ekspedisi_terusan','ket'];

    public function Ekspedisi()
    {
        return $this->belongsTo(Ekspedisi::class, 'ekspedisi_id');
    }

    public function State()
    {
        return $this->belongsTo(State::class, 'status_id');
    }

    public function DetailLogistik()
    {
        return $this->hasMany(DetailLogistik::class);
    }

    public function DetailLogistikPart()
    {
        return $this->hasMany(DetailLogistikPart::class);
    }
}
