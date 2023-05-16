<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanPembelian extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = 'permintaan_pembelian';
    protected $fillable = ['no_pp', 'tujuan', 'jenis', 'tgl_dibutuhkan', 'tgl_diminta', 'divisi_id', 'status_id'];


    public function DetailPermintaanPembelian()
    {
        return $this->hasMany(DetailPermintaanPembelian::class);
    }
    public function DetailPPterdaftar($bool)
    {
        $id = $this->id;
        if ($bool == 'true') {
            $data = DetailPermintaanPembelian::where(['permintaan_pembelian_id' => $id, 'is_terdaftar' => 1])->get();
        } else {
            $data = DetailPermintaanPembelian::where(['permintaan_pembelian_id' => $id, 'is_terdaftar' => 0])->get();
        }

        return $data;
    }
    public function PoPembelian()
    {
        return $this->hasMany(PoPembelian::class);
    }
    public function Divisi()
    {
        return $this->belongsTo(Divisi::class);
    }
    public function Status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
    public function DetailPPBom()
    {
        return $this->hasMany(DetailPPBom::class);
    }
    public function DetailPPAset()
    {
        return $this->hasMany(DetailPPAset::class);
    }
}
