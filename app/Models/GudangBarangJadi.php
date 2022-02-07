<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;
use App\Models\NoseriBarangJadi;
use Spatie\Activitylog\Traits\LogsActivity;

class GudangBarangJadi extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "gdg_barang_jadi";
    protected $fillable = ['produk_id', 'nama', 'stok', 'ruang', 'updated_by', 'created_by'];
    protected static $logName = 'GBJ - Produk';
    protected static $logAttributes = ['produk_id', 'nama', 'stok', 'deskripsi', 'user.nama', 'user1.nama'];
    public function noseri()
    {
        return $this->hasMany(NoseriBarangJadi::class, 'gdg_barang_jadi_id');
    }

    function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    function user1()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    function history()
    {
        return $this->hasMany(GudangBarangJadiHis::class, 'gdg_brg_jadi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    public function DetailPesananProduk()
    {
        return $this->hasMany(DetailPesananProduk::class);
    }
    function Layout()
    {
        return $this->belongsTo(Layout::class, 'layout_id');
    }

    function satuan()
    {
        return $this->belongsTo(Satuan::class, 'satuan_id');
    }

    public function DetailEkatalog()
    {
        return $this->belongsToMany(DetailEkatalog::class, 'detail_ekatalog_produk')
            ->withPivot('jumlah');
    }

    function TrxProduk()
    {
        return $this->hasOne(TFProduksiDetail::class, 'gdg_brg_jadi_id');
    }

    public function getJumlahPermintaanPesanan($jenis, $status)
    {
        $jumlah = 0;
        if ($jenis == "ekatalog") {
            $id = $this->id;
            $produk_id = $this->produk_id;
            // $s = DetailPesananProduk::where('gudang_barang_jadi_id', $id)->whereHas('DetailPesanan.Pesanan', function ($q) {
            //     $q->whereNotIn('log_id', ['10']);
            // })->whereHas('DetailPesanan.Pesanan.Ekatalog', function ($q) use ($status) {
            //     $q->where('status', $status);
            // })->get();
            $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('gudang_barang_jadi_id', $id);
            })->whereHas('Pesanan', function ($q) {
                $q->whereIn('log_id', ['7']);
            })->whereHas('Pesanan.Ekatalog', function ($q) use ($status) {
                $q->where('status', $status);
            })->get();
            foreach ($s as $i) {
                foreach ($i->PenjualanProduk->Produk as $j) {
                    if ($j->id == $produk_id) {
                        $jumlah =  $jumlah + ($i->jumlah * $j->pivot->jumlah);
                    }
                }
            }
        } else if ($jenis == "ekatalog_po") {
            $id = $this->id;
            $produk_id = $this->produk_id;
            // $s = DetailPesananProduk::where('gudang_barang_jadi_id', $id)->whereHas('DetailPesanan.Pesanan', function ($q) {
            //     $q->whereNotIn('log_id', ['10']);
            // })->whereHas('DetailPesanan.Pesanan.Ekatalog', function ($q) use ($status) {
            //     $q->where('status', $status);
            // })->get();
            $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('gudang_barang_jadi_id', $id);
            })->whereHas('Pesanan', function ($q) {
                $q->whereNotIn('log_id', ['7', '10']);
            })->whereHas('Pesanan.Ekatalog', function ($q) use ($status) {
                $q->whereNotIn('status', ['batal']);
            })->get();
            foreach ($s as $i) {
                foreach ($i->PenjualanProduk->Produk as $j) {
                    if ($j->id == $produk_id) {
                        $jumlah =  $jumlah + ($i->jumlah * $j->pivot->jumlah);
                    }
                }
            }
        } else if ($jenis == "spa") {
            $id = $this->id;
            $produk_id = $this->produk_id;
            // $s = DetailPesananProduk::where('gudang_barang_jadi_id', $id)->whereHas('DetailPesanan.Pesanan', function ($q) {
            //     $q->whereNotIn('log_id', ['10']);
            // })->has('DetailPesanan.Pesanan.Spa')->get();

            $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('gudang_barang_jadi_id', $id);
            })->whereHas('Pesanan', function ($q) {
                $q->whereNotIn('log_id', ['10']);
            })->has('Pesanan.Spa')->get();
            $jumlah = 0;
            foreach ($s as $i) {
                foreach ($i->PenjualanProduk->Produk as $j) {
                    if ($j->id == $produk_id) {
                        $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
                    }
                }
            }
        } else if ($jenis == "spb") {
            $id = $this->id;
            $produk_id = $this->produk_id;
            // $s = DetailPesananProduk::where('gudang_barang_jadi_id', $id)->whereHas('DetailPesanan.Pesanan', function ($q) {
            //     $q->whereNotIn('log_id', ['10']);
            // })->has('DetailPesanan.Pesanan.Spa')->get();

            $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('gudang_barang_jadi_id', $id);
            })->whereHas('Pesanan', function ($q) {
                $q->whereNotIn('log_id', ['10']);
            })->has('Pesanan.Spb')->get();
            $jumlah = 0;
            foreach ($s as $i) {
                foreach ($i->PenjualanProduk->Produk as $j) {
                    if ($j->id == $produk_id) {
                        $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
                    }
                }
            }
        } else if ($jenis == "spb") {
            $id = $this->id;
            $produk_id = $this->produk_id;
            // $s = DetailPesananProduk::where('gudang_barang_jadi_id', $id)->whereHas('DetailPesanan.Pesanan', function ($q) {
            //     $q->whereNotIn('log_id', ['10']);
            // })->has('DetailPesanan.Pesanan.Spa')->get();

            $s = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('gudang_barang_jadi_id', $id);
            })->whereHas('pesanan', function ($qq) {
                $qq->whereNotIn('log_id', ['10']);
            })->has('Pesanan.Spb')->get();
            $jumlah = 0;
            // foreach ($s as $z) {
            foreach ($s as $i) {
                foreach ($i->PenjualanProduk->Produk as $j) {
                    if ($j->id == $produk_id) {
                        $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
                    }
                }
            }
            // }
        }
        return $jumlah;
    }

    public function getJumlahTransferPesanan($jenis)
    {
        $jumlah = 0;
        $id = $this->id;
        if ($jenis == "ekatalog") {
            $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($id) {
                $q->where('gdg_brg_jadi_id', $id);
            })->whereHas('detail.header.pesanan', function ($q) {
                $q->whereNotIn('log_id', ['7', '10']);
            })->whereHas('detail.header.pesanan.Ekatalog', function ($q) {
                $q->whereNotIn('status', ['batal']);
            })->count();
        } else if ($jenis == "spa") {
            $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($id) {
                $q->where('gdg_brg_jadi_id', $id);
            })->whereHas('detail.header.pesanan', function ($q) {
                $q->whereNotIn('log_id', ['10']);
            })->has('detail.header.pesanan.Spa')->count();
        } else if ($jenis == "spb") {
            $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($id) {
                $q->where('gdg_brg_jadi_id', $id);
            })->whereHas('detail.header.pesanan', function ($q) {
                $q->whereNotIn('log_id', ['10']);
            })->has('detail.header.pesanan.Spb')->count();
        }
        return $jumlah;
    }

    public function getJumlahCekPesanan()
    {
        // $jumlah = 0;
        $id = $this->id;
        // if ($jenis == "ekatalog") {
        //     $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($id) {
        //         $q->where('gdg_brg_jadi_id', $id);
        //     })->whereHas('detail.header.pesanan', function ($q) {
        //         $q->whereNotIn('log_id', ['7', '10']);
        //     })->whereHas('detail.header.pesanan.Ekatalog', function ($q) {
        //         $q->whereNotIn('status', ['batal']);
        //     })->count();
        // } else if ($jenis == "spa") {
        //     $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($id) {
        //         $q->where('gdg_brg_jadi_id', $id);
        //     })->whereHas('detail.header.pesanan', function ($q) {
        //         $q->whereNotIn('log_id', ['10']);
        //     })->has('detail.header.pesanan.Spa')->count();
        // } else if ($jenis == "spb") {
        //     $jumlah = NoseriTGbj::where('jenis', 'keluar')->whereHas('detail', function ($q) use ($id) {
        //         $q->where('gdg_brg_jadi_id', $id);
        //     })->whereHas('detail.header.pesanan', function ($q) {
        //         $q->whereNotIn('log_id', ['10']);
        //     })->has('detail.header.pesanan.Spb')->count();
        // }
        $jumlah = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->doesntHave('NoseriDetailLogistik')->whereHas('DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['10']);
        })->count();
        return $jumlah;
    }

    public function getJumlahKirimPesanan()
    {
        $id = $this->id;
        $jumlah = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['10']);
        })->count();
        return $jumlah;
    }
}
