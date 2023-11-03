<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Produk;
use App\Models\NoseriBarangJadi;

class GudangBarangJadi extends Model
{
    use HasFactory;
    protected $connection = 'erp';
    protected $table = "gdg_barang_jadi";
    protected $fillable = ['produk_id', 'nama', 'stok', 'ruang', 'updated_by', 'created_by', 'stok_siap', 'satuan_id'];

    public function noseri()
    {
        return $this->hasMany(NoseriBarangJadi::class, 'gdg_barang_jadi_id');
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
    public function DetailPesananProdukDsb()
    {
        return $this->hasMany(DetailPesananProdukDsb::class);
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
        $s = 0;
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
            })->sum('jumlah');
            // foreach ($s as $i) {
            //     foreach ($i->PenjualanProduk->Produk as $j) {
            //         if ($j->id == $produk_id) {
            //             $jumlah =  $jumlah + ($i->jumlah * $j->pivot->jumlah);
            //         }
            //     }
            // }
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
            })->sum('jumlah');
            // foreach ($s as $i) {
            //     foreach ($i->PenjualanProduk->Produk as $j) {
            //         if ($j->id == $produk_id) {
            //             $jumlah =  $jumlah + ($i->jumlah * $j->pivot->jumlah);
            //         }
            //     }
            // }
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
            })->has('Pesanan.Spa')->sum('jumlah');
            // $jumlah = 0;
            // foreach ($s as $i) {
            //     foreach ($i->PenjualanProduk->Produk as $j) {
            //         if ($j->id == $produk_id) {
            //             $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
            //         }
            //     }
            // }
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
            })->has('Pesanan.Spb')->sum('jumlah');
            // $jumlah = 0;
            // foreach ($s as $i) {
            //     foreach ($i->PenjualanProduk->Produk as $j) {
            //         if ($j->id == $produk_id) {
            //             $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
            //         }
            //     }
            // }
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
            })->has('Pesanan.Spb')->sum('jumlah');
            // $jumlah = 0;
            // // foreach ($s as $z) {
            // foreach ($s as $i) {
            //     foreach ($i->PenjualanProduk->Produk as $j) {
            //         if ($j->id == $produk_id) {
            //             $jumlah = $jumlah + ($i->jumlah * $j->pivot->jumlah);
            //         }
            //     }
            // }
            // // }
        }
        return $s;
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
            $q->whereNotIn('log_id', ['7', '10']);
        })->count();
        return $jumlah;
    }

    public function getJumlahKirimPesanan()
    {
        $id = $this->id;

        $jumlah = NoseriDetailLogistik::whereHas('NoseriDetailPesanan.DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan', function ($q) {
            $q->whereNotIn('log_id', ['7', '10']);
        })->count();
        return $jumlah;
    }

    function get_sum_noseri()
    {
        $id = $this->id;
        $d = NoseriBarangJadi::whereHas('gudang', function ($q) use ($id) {
            $q->where('gdg_barang_jadi_id', $id);
        })->where('is_aktif', 1)->count();
        return $d;
    }

    function get_sum_seri_siap()
    {
        $id = $this->id;
        $d = NoseriBarangJadi::whereHas('gudang', function ($q) use ($id) {
            $q->where('gdg_barang_jadi_id', $id);
        })->where('is_aktif', 1)->where('is_ready', 0)->count();
        return $d;
    }
    public function Stok()
    {
        $id = $this->id;
        // $jumlah = DB::table('noseri_barang_jadi')
        //             ->where(['noseri_barang_jadi.gdg_barang_jadi_id'=>$id,
        //             'noseri_barang_jadi.is_aktif' => 1,
        //             'noseri_barang_jadi.is_ready' => 0,
        //             'noseri_barang_jadi.is_change' => 1,
        //             'noseri_barang_jadi.is_delete' => 0
        //             ])
        //             ->count();

        $data = GudangBarangJadi::addSelect(['count_barang' => function ($query) {
            $query->selectRaw('count(noseri_barang_jadi.id)')
                ->from('noseri_barang_jadi')
                ->where('noseri_barang_jadi.is_ready', '0')
                ->where('noseri_barang_jadi.is_aktif', '1')
                ->whereColumn('noseri_barang_jadi.gdg_barang_jadi_id', 'gdg_barang_jadi.id')
                ->limit(1);
        }, 'count_ekat_sepakat' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
                ->limit(1);
        }, 'count_ekat_nego' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
                ->limit(1);
        }, 'count_ekat_draft' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id in ("7")  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                ->limit(1);
        }, 'count_ekat_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                ->limit(1);
        }, 'count_spa_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                ->limit(1);
        }, 'count_spb_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                ->limit(1);
        }])->with('Produk')->find($id);

        $jumlahdiminta = intval($data->count_ekat_sepakat) + intval($data->count_ekat_nego) + intval($data->count_ekat_draft) + intval($data->count_ekat_po) + intval($data->count_spa_po) + intval($data->count_spb_po);
        $jumlahstok = intval($data->count_barang);
        $hasil = $jumlahstok - $jumlahdiminta;

        return $data->count_barang;
    }
}
