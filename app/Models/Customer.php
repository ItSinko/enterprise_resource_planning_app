<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'erp';
    protected $table = 'customer';
    protected $fillable = ['id_provinsi', 'nama', 'telp', 'email', 'alamat', 'npwp', 'ktp', 'batas', 'pic', 'ket', 'izin_usaha', 'nama_pemilik', 'modal_usaha', 'hasil_penjualan'];

    public function Spa()
    {
        return $this->hasMany(Spa::class);
    }
    public function Spb()
    {
        return $this->hasMany(Spb::class);
    }
    public function Ekatalog()
    {
        return $this->hasMany(Ekatalog::class);
    }
    public function Provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'id_provinsi');
    }
    public function RencanaPenjualan()
    {
        return $this->hasMany(RencanaPenjualan::class);
    }

    public function sumSubtotal()
    {
        $id = $this->id;
        $s = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($id) {
            $q->where('customer_id', $id);
        })->get();
        $total = 0;
        foreach ($s as $i) {
            $total +=  $i->jumlah *  $i->harga;
        }
        return $total;
    }
    public function ReturPenjualan()
    {
        return $this->hasMany(ReturPenjualan::class);
    }
    public function Pengiriman()
    {
        return $this->hasMany(Pengiriman::class, 'created_by');
    }
}
