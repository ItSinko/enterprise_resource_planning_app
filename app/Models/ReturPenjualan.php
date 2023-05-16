<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\kesehatan\Karyawan;

class ReturPenjualan extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "retur_penjualan";

    protected $fillable = ['no_retur', 'retur_penjualan_id', 'tgl_retur', 'jenis', 'keterangan', 'karyawan_id', 'pesanan_id', 'no_pesanan', 'customer_id', 'state_id', 'pic', 'telp_pic'];

    public function Customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function Karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function Pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function State()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function ReturPenjualanProduk()
    {
        return $this->hasMany(ReturPenjualanProduk::class);
    }

    public function ReturPenjualanPart()
    {
        return $this->hasMany(ReturPenjualanPart::class);
    }

    public function ReturPenjualanParent()
    {
        return $this->hasMany(ReturPenjualan::class, 'retur_penjualan_id');
    }

    public function ReturPenjualanChild()
    {
        return $this->belongsTo(ReturPenjualan::class, 'retur_penjualan_id');
    }

    function TFProduksi(){
        return $this->hasOne(TFProduksi::class);
    }

    public function Pengiriman(){
        return $this->hasMany(Pengiriman::class, 'retur_penjualan_id');
    }
}
