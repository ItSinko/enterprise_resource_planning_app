<?php

namespace App\Http\Controllers;
use App\Exports\LaporanAfterSalesRetur;
use App\Models\Pesanan;
use App\Models\Logistik;
use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\DetailLogistikPart;
use App\Models\DetailPesananPart;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriDetailPesanan;
use App\Models\Ekatalog;
use App\Models\ReturPenjualan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Customer;
use App\Models\GudangBarangJadi;
use App\Models\KaryawanPerbaikan;
use App\Models\kesehatan\Karyawan;
use App\Models\NoseriBarangJadi;
use App\Models\State;
use App\Models\NoseriTGbj;
use App\Models\NoseriPerbaikan;
use App\Models\PartPenggantiPerbaikan;
use App\Models\Pengiriman;
use App\Models\PengirimanNoseri;
use App\Models\PengirimanPart;
use App\Models\Perbaikan;

use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use TGbjNoseri;

class AfterSalesController extends Controller
{
    public function get_data_so()
    {
        $datas = DetailPesanan::with(['Pesanan.Ekatalog.Customer.Provinsi', 'Pesanan.Spa.Customer.Provinsi', 'Pesanan.Spb.Customer.Provinsi', 'PenjualanProduk'])->orderBy('id', 'desc')
        ->addSelect(['tgl_kirim' => function($q){
            $q->selectRaw('logistik.tgl_kirim')
            ->from('logistik')
            ->leftJoin('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
            ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
            ->limit(1);
        }, 'count_qc' => function($q){
            $q->selectRaw('count(noseri_detail_pesanan.id)')
            ->from('noseri_detail_pesanan')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
            ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
            ->where('noseri_detail_pesanan.status', '=', 'ok')
            ->limit(1);
        }, 'count_log' => function($q){
            $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
            ->from('noseri_logistik')
            ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
            ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
            ->whereColumn('detail_pesanan_produk.detail_pesanan_id', 'detail_pesanan.id')
            ->limit(1);
        }])->havingRaw('count_log > 0')->get();

        $datas1 = DetailPesananPart::with(['Pesanan.Ekatalog.Customer.Provinsi', 'Pesanan.Spa.Customer.Provinsi', 'Pesanan.Spb.Customer.Provinsi', 'Sparepart'])->orderBy('id', 'desc')
        ->addSelect(['tgl_kirim' => function($q){
            $q->selectRaw('logistik.tgl_kirim')
            ->from('logistik')
            ->leftJoin('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
            ->whereColumn('detail_logistik_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
            ->limit(1);
        }, 'count_qc' => function($q){
            $q->selectRaw('sum(outgoing_pesanan_part.jumlah_ok)')
            ->from('outgoing_pesanan_part')
            ->whereColumn('outgoing_pesanan_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
            ->limit(1);
        }, 'count_log' => function($q){
            $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
            ->from('detail_logistik_part')
            ->whereColumn('detail_logistik_part.detail_pesanan_part_id', 'detail_pesanan_part.id')
            ->limit(1);
        }])->havingRaw('count_log > 0')->get();

        $data = $datas->merge($datas1);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('nama_produk', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    // $id = array();
                    // $detail_produk = DetailPesananProduk::where('detail_pesanan_id', $data->id)->get();
                    // foreach ($detail_produk as $d) {
                    //     if ($d->gudangbarangjadi->nama == '') {
                    //         $id[] = $d->gudangbarangjadi->produk->nama;
                    //     } else {
                    //         $id[] = $d->gudangbarangjadi->produk->nama.' '.$d->gudangbarangjadi->nama;
                    //     }
                    // }
                    // return implode(', ', $data->DetailPesananProduk->GudangBarangJadi->Produk->nama);
                    return $data->PenjualanProduk->nama;
                } else {
                    return $data->Sparepart->nama;
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
                // $id = $data->id;
                // if (isset($data->DetailPesananProduk)) {
                //     $l = Logistik::whereHas('DetailLogistik.DetailPesananProduk', function ($q) use ($id) {
                //         $q->where('detail_pesanan_id', $id);
                //     })->selectRaw("min(tgl_kirim) as tgl_kirim")->first();
                //     return Carbon::createFromFormat('Y-m-d', $l->tgl_kirim)->format('d-m-Y');
                // } else {
                //     $l = Logistik::whereHas('DetailLogistikPart.DetailPesananPart', function ($q) use ($id) {
                //         $q->where('id', $id);
                //     })->selectRaw("min(tgl_kirim) as tgl_kirim")->first();
                //     return Carbon::createFromFormat('Y-m-d', $l->tgl_kirim)->format('d-m-Y');
                // }
                // $arr = array();
                // foreach ($l as $k) {
                //     $arr[] = Carbon::createFromFormat('Y-m-d', $k->tgl_kirim)->format('d-m-Y');
                // };

                // return implode(", ", $arr);
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->nama;
                } else {
                    return $data->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('alamat', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->alamat;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->alamat;
                } else {
                    return $data->Pesanan->Spb->Customer->alamat;
                }
            })
            ->addColumn('provinsi', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Provinsi->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->Provinsi->nama;
                } else {
                    return $data->Pesanan->Spb->Customer->Provinsi->nama;
                }
            })
            ->addColumn('telepon', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->telp;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->telp;
                } else {
                    return $data->Pesanan->Spb->Customer->telp;
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                $hitung = floor((($data->count_log / $data->count_qc) * 100));
                            if($hitung > 0){
                                $datas = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                            } else {
                                $datas = '<div class="progress">
                                    <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                            }
                return $datas;
            })
            ->addColumn('keterangan', function ($data) {
                return "-";
            })
            ->addColumn('button', function ($data) {
                $name = explode('/', $data->Pesanan->so);
                $jenis = "";
                if (isset($data->DetailPesananProduk)) {
                    $jenis = "produk";
                } else {
                    $jenis = "part";
                }
                return '<a href="' . route('as.so.detail', ['id' => $data->id, 'jenis' => $jenis]) . '"><button type="button" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></button></a>';
                // $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
                // return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                // <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                //     <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name[1]]) . '">
                //         <button class="dropdown-item" type="button">
                //             <i class="fas fa-eye"></i>
                //             Detail
                //         </button>
                //     </a>
                //     <a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
                //         <button class="dropdown-item" type="button">
                //             <i class="fas fa-file"></i>
                //             Laporan PDF
                //         </button>
                //     </a>
                // </div>';
            })
            ->rawColumns(['status', 'button'])
            ->make(true);
        // return datatables()->of($data)
        //     ->addIndexColumn()
        //     ->addColumn('so', function ($data) {
        //         return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so;
        //     })
        //     ->addColumn('tgl_kirim', function ($data) {
        //         return  Carbon::createFromFormat('Y-m-d', $data->tgl_kirim)->format('d-m-Y');
        //     })
        //     ->addColumn('nama_customer', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         if ($name[1] == 'EKAT') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->nama;
        //         } elseif ($name[1] == 'SPA') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->nama;
        //         } else {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->nama;
        //         }
        //     })
        //     ->addColumn('alamat', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         if ($name[1] == 'EKAT') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->alamat;
        //         } elseif ($name[1] == 'SPA') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->alamat;
        //         } else {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->alamat;
        //         }
        //     })
        //     ->addColumn('provinsi', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         if ($name[1] == 'EKAT') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Provinsi->nama;
        //         } elseif ($name[1] == 'SPA') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->Provinsi->nama;
        //         } else {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->Provinsi->nama;
        //         }
        //     })
        //     ->addColumn('telepon', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         if ($name[1] == 'EKAT') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Ekatalog->Customer->telp;
        //         } elseif ($name[1] == 'SPA') {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spa->Customer->telp;
        //         } else {
        //             return $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->Spb->Customer->telp;
        //         }
        //     })
        //     ->addColumn('status', function ($data) {
        //         if ($data->status_id  == "10") {
        //             return '<span class="badge blue-text">' . $data->State->nama . '</span>';
        //         } else if ($data->status_id  == "11") {
        //             return '<span class="badge red-text">' . $data->State->nama . '</span>';
        //         }
        //     })
        //     ->addColumn('keterangan', function ($data) {
        //         return "-";
        //     })
        //     ->addColumn('button', function ($data) {
        //         $name = explode('/', $data->DetailLogistik->DetailPesananProduk->DetailPesanan->Pesanan->so);
        //         return '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
        //         <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //             <a href="' . route('logistik.pengiriman.detail', ['id' => $data->id, 'jenis' => $name[1]]) . '">
        //                 <button class="dropdown-item" type="button">
        //                     <i class="fas fa-eye"></i>
        //                     Detail
        //                 </button>
        //             </a>
        //             <a href="' . route('logistik.pengiriman.print', ['id' => $data->id]) . '" target="_blank">
        //                 <button class="dropdown-item" type="button">
        //                     <i class="fas fa-file"></i>
        //                     Laporan PDF
        //                 </button>
        //             </a>
        //         </div>';
        //     })
        //     ->rawColumns(['status', 'button'])
        //     ->make(true);
    }
    public function get_detail_so($id, $jenis)
    {
        $d = "";
        $status = "";
        if ($jenis == "produk") {
            $d = DetailPesanan::find($id);

            $jumlahkirim = NoseriDetailLogistik::whereHas('DetailLogistik.DetailPesananProduk.DetailPesanan', function ($q) use ($id) {
                $q->where('id', $id);
            })->count();

            $jumlahseri = NoseriDetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('detail_pesanan_id', $id);
            })->count();

            if ($jumlahkirim >= $jumlahseri) {
                $status = '<span class="badge green-text">Selesai</span>';
            } else if ($jumlahkirim < $jumlahseri) {
                $status = '<span class="badge yellow-text">Terkirim Sebagian</span>';
            }
        } else {
            $d = DetailPesananPart::find($id);
            $jumlahkirim = 0;
            if (isset($d->DetailLogistikPart)) {
                $jumlahkirim = $d->jumlah;
            } else {
                $jumlahkirim = 0;
            }

            if ($jumlahkirim == $d->jumlah) {
                $status = '<span class="badge green-text">Selesai</span>';
            } else if ($jumlahkirim < $d->jumlah) {
                $status = '<span class="badge yellow-text">Terkirim Sebagian</span>';
            }
        }

        return view('page.as.so.detail', ['id' => $id, 'jenis' => $jenis, 'd' => $d, 'status' => $status]);
    }

    public function get_detail_pengiriman($id, $jenis)
    {
        $data = "";
        if ($jenis == "produk") {
            $data = DetailLogistik::whereHas('DetailPesananProduk', function ($q) use ($id) {
                $q->where('detail_pesanan_id', $id);
            })->whereHas('Logistik', function ($q) {
                $q->whereIn('status_id', ['10']);
            })->orderBy('id', 'desc')->get();
        } else {
            $data = DetailLogistikPart::whereHas('DetailPesananPart', function ($q) use ($id) {
                $q->where('id', $id);
            })->whereHas('Logistik', function ($q) {
                $q->whereIn('status_id', ['10']);
            })->orderBy('id', 'desc')->get();
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no', function ($data) {
                if (isset($data->Logistik)) {
                    return $data->Logistik->nosurat;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_kirim', function ($data) {
                if (isset($data->Logistik)) {
                    return  Carbon::createFromFormat('Y-m-d', $data->Logistik->tgl_kirim)->format('d-m-Y');
                } else {
                    return '';
                }
            })
            ->addColumn('pengirim', function ($data) {
                if (isset($data->Logistik)) {
                    if ($data->Logistik->nama_pengirim == "") {
                        return $data->Logistik->ekspedisi['nama'];
                    } else {
                        return $data->Logistik->nama_pengirim;
                    }
                } else {
                    return '';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    return $data->DetailPesananProduk->GudangBarangJadi->Produk->nama;
                } else {
                    return $data->DetailPesananPart->Sparepart->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    $c = NoseriDetailLogistik::where('detail_logistik_id', $data->id)->count();
                } else {
                    $c = $data->DetailPesananPart->jumlah;
                }
                return $c;
            })
            ->addColumn('button', function ($data) {
                return '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-id="' . $data->id . '">
                <div><i class="fas fa-eye"></i></div>
            </a>';
            })
            ->rawColumns(['checkbox', 'button', 'status'])
            ->make(true);
    }

    public function get_data_so_belum_kirim()
    {
        $data = Pesanan::addSelect(['cjumlahpart' => function($q){
            $q->selectRaw('sum(detail_pesanan_part.jumlah)')
            ->from('detail_pesanan_part')
            ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }, 'cjumlahkirim' => function($q){
            $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
            ->from('detail_logistik_part')
            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
            ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }])->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->orderBy('tgl_po', 'desc')->havingRaw('cjumlahpart > 0 AND cjumlahkirim <= 0')->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis', function ($data) {
                if (isset($data->Spa)) {
                    return '<span class="orange-text badge">SPA</span>';
                }
                else if (isset($data->Spb)) {
                    return '<span class="blue-text badge">SPB</span>';
                }
            })
            ->addColumn('so', function ($data) {

                if (!empty($data->so)) {
                        return $data->so;
                    } else {
                        return '-';
                    }
            })
            ->addColumn('nopo', function ($data) {
                    if (!empty($data->no_po)) {
                        return $data->no_po;
                    } else {
                        return '-';
                    }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                $hitung = floor((($data->cjumlahkirim / $data->cjumlahpart) * 100));
                            if($hitung > 0){
                                $datas = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                            }else{
                                $datas = '<div class="progress">
                                    <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                            }
                return $datas;

            })
            ->addColumn('tglpo', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->Spa)) {
                    return $data->Spa->Customer->nama;
                }
                else if (isset($data->Spb)) {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('button', function ($data) {
                $return = "";
                if (isset($data->Spa)) {
                    $return .= '<a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->Spa->id) . '"  data-id="' . $data->Spa->id . '" >
                        <button class="btn btn-outline-primary btn-sm" type="button">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                    </a>';
                }
                else if (isset($data->Spb)) {
                    $return .= '<a data-toggle="modal" data-target="spb" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spb',  $data->Spb->id) . '"  data-id="' . $data->Spb->id . '" >
                    <button class="btn btn-outline-primary btn-sm" type="button">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                    </a>';
                }
                return $return;
            })
            ->rawColumns(['button', 'status', 'jenis'])
            ->make(true);
    }

    public function get_data_so_selesai_kirim()
    {
        $data = Pesanan::addSelect(['cjumlahpart' => function($q){
            $q->selectRaw('sum(detail_pesanan_part.jumlah)')
            ->from('detail_pesanan_part')
            ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }, 'cjumlahkirim' => function($q){
            $q->selectRaw('sum(detail_logistik_part.jumlah)')
            ->from('detail_logistik_part')
            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
            ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
        }])->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->havingRaw('cjumlahkirim > 0')->orderBy('tgl_po', 'desc')->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis', function ($data) {
                if (isset($data->Spa)) {
                    return '<span class="orange-text badge">SPA</span>';
                }
                else if (isset($data->Spb)) {
                    return '<span class="blue-text badge">SPB</span>';
                }
            })
            ->addColumn('so', function ($data) {
                if (!empty($data->so)) {
                        return $data->so;
                    } else {
                        return '-';
                    }
            })
            ->addColumn('nopo', function ($data) {
                    if (!empty($data->no_po)) {
                        return $data->no_po;
                    } else {
                        return '-';
                    }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                $hitung = floor((($data->cjumlahkirim / $data->cjumlahpart) * 100));
                            if($hitung > 0){
                                $datas = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                            }else{
                                $datas = '<div class="progress">
                                    <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                            }
                return $datas;
            })
            ->addColumn('tglpo', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->Spa)) {
                    return $data->Spa->Customer->nama;
                }
                else if (isset($data->Spb)) {
                    return $data->Spb->Customer->nama;
                }
            })
            ->addColumn('button', function ($data) {
                $return = "";
                if (isset($data->Spa)) {
                    $return .= '<a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->Spa->id) . '"  data-id="' . $data->Spa->id . '" >
                    <button class="btn btn-outline-primary btn-sm" type="button">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                    </a>';
                }
                else if (isset($data->Spb)) {
                    $return .= '<a data-toggle="modal" data-target="spb" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spb',  $data->Spb->id) . '"  data-id="' . $data->Spb->id . '" >
                    <button class="btn btn-outline-primary btn-sm" type="button">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                    </a>';
                }
                return $return;
            })
            ->rawColumns(['button', 'status', 'jenis'])
            ->make(true);
    }


    public function show_retur()
    {
        return view('page.as.retur.show');
    }

    public function get_data_retur()
    {
        $data = ReturPenjualan::with('Pesanan', 'Customer', 'Karyawan', 'State', 'ReturPenjualanChild')
        ->addSelect(['count_noseri' => function($q){
            $q->selectRaw('count(t_gbj_noseri.id)')
            ->from('t_gbj_noseri')
            ->join('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            ->where('t_gbj.jenis', '=', 'masuk')
            ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
         }, 'count_perbaikan' => function($q){
            $q->selectRaw('coalesce(count(noseri_perbaikan.id), 0)')
            ->from('noseri_perbaikan')
            ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
            ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
         }, 'count_part' => function($q){
            $q->selectRaw('coalesce(SUM(t_gbj_detail.qty), 0)')
            ->from('t_gbj_detail')
            ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            ->where('t_gbj.jenis', 'masuk')
            ->whereNotNull('t_gbj_detail.m_sparepart_id')
            ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
        }, 'count_kirim_part' => function($q){
            $q->selectRaw('coalesce(SUM(pengiriman_part.jumlah), 0)')
            ->from('pengiriman_part')
            ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_part.pengiriman_id')
            ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
        }, 'count_perbaikan_karantina' => function($q){
            $q->selectRaw('coalesce(count("noseri_perbaikan.id"), 0)')
            ->from('noseri_perbaikan')
            ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
            ->where('noseri_perbaikan.m_status_id', '=', '2')
            ->whereNull('noseri_perbaikan.noseri_pengganti_id')
            ->whereIn('noseri_perbaikan.tindak_lanjut', ['karantina'])
            ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
        }, 'count_pengiriman' => function($q){
            $q->selectRaw('coalesce(count("pengiriman_noseri.id"),0)')
            ->from('pengiriman_noseri')
            ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
            ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
        }])
        ->get();
        return response()->json(['data' => $data]);
    }

    public function get_data_detail_retur(Request $r)
    {
        $d = ReturPenjualan::where('id', $r->id)->with('Pesanan', 'TFProduksi.detail.noseri')->first();
        $data = array();
        $data['id'] = $d->id;
        if($d->pesanan_id != null) {
            $data['no_pesanan'] = $d->Pesanan->no_po;
        }
        else if($d->retur_penjualan_id != null) {
            $data['no_pesanan'] = $d->ReturPenjualanChild->no_retur;
        }
        else {
            $data['no_pesanan'] = $d->no_pesanan;
        }
        $data['karyawan_id'] = $d->karyawan_id != null ? $d->Karyawan->nama : $d->pic;
        $data['telp_pic'] = $d->telp_pic != null ? '('.$d->telp_pic.')' : "";
        $data['customer'] = $d->Customer->nama;
        $data['alamat'] = $d->Customer->alamat;
        $data['telp'] = $d->Customer->telp;
        $data['no_retur'] = $d->no_retur;
        $data['tgl_retur'] = $d->tgl_retur;
        $data['jenis'] = $d->jenis;
        $data['keterangan'] = $d->keterangan != "" ? $d->keterangan : '-';
        $data['status'] = $d->State->nama;
        $data['produk'] = array();
        $id = $r->id;
        if($d->TFProduksi != null || $d->ReturPenjualanProduk != null){
            $produk = TFProduksiDetail::whereHas('header', function($q) use($id){
                $q->where([['retur_penjualan_id', '=', $id], ['jenis', '=', 'masuk']]);
            })->get();
            foreach($produk as $key => $prd){
                $data['produk'][$key]['nama'] = $prd->gdg_brg_jadi_id != null ? $prd->produk->Produk->nama." ".$prd->produk->nama : $prd->Sparepart->nama;
                $data['produk'][$key]['jenis'] = $prd->gdg_brg_jadi_id != null ? 'Produk' : 'Part';
                $data['produk'][$key]['jumlah'] = $prd->qty;
                $data['produk'][$key]['no_seri'] = array();
                if($prd->noseri != null){
                    foreach($prd->noseri as $keys => $noseri){
                        $data['produk'][$key]['no_seri'][$keys] = $noseri->seri->noseri;
                    }
                }
            }
            // echo json_encode($produk);
        }
        return response()->json($data);
    }

    public function get_no_retur_exist(Request $r)
    {
        $data = NULL;
        if($r->id != "0"){
            $data = ReturPenjualan::where('no_retur', $r->no_retur)->whereNotIn('id', [$r->id])->count();
        }else{
            $data = ReturPenjualan::where('no_retur', '=', $r->no_retur)->count();
        }

        return response()->json($data);
    }

    public function detail_retur()
    {
        return view('page.as.retur.detail');
    }

    public function create_retur()
    {
        return view('page.as.retur.create');
    }

    public function store_retur(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'tgl_retur' => 'required',
            'pilih_jenis_retur' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Periksa Kembali Form Anda');
        }else{
            $customer_id = "";
            $pesanan_id = NULL;
            $no_pesanan = NULL;
            $retur_id = NULL;

            $karyawan_id = NULL;
            $pic = NULL;

            if($r->karyawan_id != ""){
                $karyawan_id = $r->karyawan_id;
                $pic = NULL;
            }
            else{
                $pic = $r->pic_peminjaman;
                $karyawan_id = NULL;
            }

            if($r->customer_id != ""){
                $customer_id = $r->customer_id;
            }
            else{
                $c = Customer::create([
                    'id_provinsi' => '35',
                    'nama' => $r->customer_nama,
                    'alamat' => $r->alamat,
                    'telp' => $r->telepon
                ]);
                if($c){
                    $customer_id = $c->id;
                }
            }
            if($r->no_transaksi_ref != "no_retur" && $r->no_transaksi_ref != "sj_retur"){
                if($r->pesanan_id != ""){
                    $pesanan_id = $r->pesanan_id;
                }else{
                    $no_pesanan = $r->no_transaksi;
                }
            }
            else{
                if($r->pesanan_id != ""){
                    $retur_id = $r->pesanan_id;
                }else{
                    $no_pesanan = $r->no_transaksi;
                }
            }
            $retur = $this->no_bm($r->tgl_retur);
            $cr = ReturPenjualan::create([
                'no_retur' => $retur,
                'tgl_retur' => $r->tgl_retur,
                'jenis' => $r->pilih_jenis_retur,
                'keterangan' => $r->keterangan,
                'pesanan_id' => $pesanan_id,
                'retur_penjualan_id' => $retur_id,
                'no_pesanan' => $no_pesanan,
                'customer_id' => $customer_id,
                'karyawan_id' => $karyawan_id,
                'pic' => $pic,
                'telp_pic' => $r->telp_pic,
                'state_id' => '4'
            ]);

            $bool = true;
            $tes = NULL;
            if($cr){
                $tg = TFProduksi::create([
                    'dari' => Auth::user()->Karyawan->divisi_id,
                    'ke' => '13',
                    'deskripsi' => NULL,
                    'status_id' => NULL,
                    'pesanan_id' => NULL,
                    'retur_penjualan_id' => $cr->id,
                    'tgl_keluar' => NULL,
                    'tgl_masuk' => $r->tgl_retur,
                    'state_id' => NULL,
                    'jenis' => 'masuk',
                    'created_by' => Auth::user()->id
                ]);
                if(in_array('produk', $r->pilih_jenis_barang)){
                    if($pesanan_id != NULL || $retur_id != NULL){
                        if($tg){
                            foreach($r->produk_id as $key => $produk){
                                $no_seri = json_decode($r->no_seri_select[$key]);
                                $tgd = TFProduksiDetail::create([
                                    't_gbj_id' => $tg->id,
                                    'detail_pesanan_produk_id' => NULL,
                                    'gdg_brg_jadi_id' => $produk,
                                    'qty' => count($no_seri),
                                    'jenis' => 'masuk',
                                    'status_id' => NULL,
                                    'state_id' => NULL,
                                    'created_by' => Auth::user()->id,
                                ]);
                                if($tgd){
                                    foreach($no_seri as $keys => $noseri){

                                        $tgn = NoseriTGbj::create([
                                            't_gbj_detail_id' => $tgd->id,
                                            'noseri_id' => $noseri->id,
                                            'layout_id' => NULL,
                                            'status_id' => NULL,
                                            'state_id' => NULL,
                                            'jenis' => 'masuk',
                                            'created_by' => Auth::user()->id,
                                        ]);
                                        if(!$tgn){
                                            $bool = false;
                                        }
                                        else{
                                            $noseri_retur = NoseriBarangJadi::find($noseri->id);
                                            $noseri_retur->used_by = NULL;
                                            $noseri_retur->is_ready = '0';
                                            $noseri_retur->is_aktif = '0';
                                            $noseri_retur->jenis = 'MASUK';
                                            $u = $noseri_retur->save();
                                            if(!$u){
                                                $bool = false;
                                            }
                                        }
                                    }
                                }
                                else{
                                    $bool = false;
                                }
                            }
                        }
                        else{
                            $bool = false;
                        }

                    } else if($no_pesanan != NULL){

                        foreach($r->produk_id as $key => $produk){
                            $no_seri = json_decode($r->no_seri_select[$key]);
                            $tgd = TFProduksiDetail::create([
                                't_gbj_id' => $tg->id,
                                'detail_pesanan_produk_id' => NULL,
                                'gdg_brg_jadi_id' => $produk,
                                'qty' => count($r->no_seri_select[$key]),
                                'jenis' => 'masuk',
                                'status_id' => NULL,
                                'state_id' => NULL,
                                'created_by' => Auth::user()->id,
                            ]);

                            if($tgd){
                                foreach($no_seri as $keys => $noseri){
                                    $snoseri = NoseriBarangJadi::where('noseri', $noseri->id)->first();
                                    if($snoseri != null){
                                        $tgn = NoseriTGbj::create([
                                            't_gbj_detail_id' => $tgd->id,
                                            'noseri_id' => $snoseri->id,
                                            'layout_id' => NULL,
                                            'status_id' => NULL,
                                            'state_id' => NULL,
                                            'jenis' => 'masuk',
                                            'created_by' => Auth::user()->id,
                                        ]);


                                        if(!$tgn){
                                            $bool = false;
                                        }
                                        else{
                                            $noseri_retur = NoseriBarangJadi::find($snoseri->id);
                                            $noseri_retur->used_by = NULL;
                                            $noseri_retur->is_ready = '0';
                                            $noseri_retur->is_aktif = '0';
                                            $noseri_retur->jenis = 'MASUK';
                                            $u = $noseri_retur->save();
                                            if(!$u){
                                                $bool = false;
                                            }
                                        }
                                    }else{
                                        $nbj = NoseriBarangJadi::create([
                                            'gdg_barang_jadi_id' => $produk,
                                            'dari' => Auth::user()->Karyawan->divisi_id,
                                            'noseri' => $noseri,
                                            'layout_id' => NULL,
                                            'jenis' => 'MASUK',
                                            'is_ready' => '0',
                                            'used_by' => NULL,
                                            'is_aktif' => '0',
                                            'keterangan' => NULL,
                                            'created_by' => Auth::user()->Karyawan->id,
                                            'is_change' => 1,
                                            'is_delete' => 0
                                        ]);
                                        if($nbj){
                                            $tgn = NoseriTGbj::create([
                                                't_gbj_detail_id' => $tgd->id,
                                                'noseri_id' => $nbj->id,
                                                'layout_id' => NULL,
                                                'status_id' => NULL,
                                                'state_id' => NULL,
                                                'jenis' => 'masuk',
                                                'created_by' => Auth::user()->id
                                            ]);
                                            if(!$tgn){
                                                $bool = false;
                                            }
                                        }else{
                                            $bool = false;
                                        }
                                    }
                                }
                            }
                            else{
                                $bool = false;
                            }
                        }
                    }
                }

                if(in_array('part', $r->pilih_jenis_barang)){
                    foreach($r->part_id as $key => $part){
                        $tgd = TFProduksiDetail::create([
                            't_gbj_id' => $tg->id,
                            'detail_pesanan_produk_id' => NULL,
                            'gdg_brg_jadi_id' => NULL,
                            'm_sparepart_id' => $part,
                            'qty' => $r->part_jumlah[$key],
                            'jenis' => 'masuk',
                            'status_id' => NULL,
                            'state_id' => NULL,
                            'created_by' => Auth::user()->id,
                        ]);

                        if(!$tgd){
                            $bool = false;
                        }
                    }
                }

                if ($bool == true) {
                    return redirect()->back()->with('success', 'Berhasil menambahkan Retur');
                } else if ($bool == false) {
                    return redirect()->back()->with('error', 'Gagal menambahkan Retur');
                }
            }else{
                return redirect()->back()->with('error', 'Gagal menambahkan Retur');
            }
        }
    }

    public function edit_retur($id)
    {
        $data = ReturPenjualan::find($id);
        $tesproduk = TFProduksiDetail::whereNotNull('gdg_brg_jadi_id')->whereHas('header', function($q) use($id){
            $q->where([['retur_penjualan_id', '=', $id], ['jenis', '=', 'masuk']]);
        })->get();
        $produk = array();

        foreach($tesproduk as $key => $c){
            $produk[$key] = array('id' => $c->gdg_brg_jadi_id,
                'produk' => $c->produk->produk->nama ." ". $c->produk->nama,
                'jumlah' => count($c->seri),
                'seri' => array(),
                'allseri' => array()
            );

            foreach($c->seri as $keys => $d){
                $produk[$key]['seri'][$keys] = array('id' => $data->pesanan_id != NULL || $data->retur_penjualan_id != NULL ? $d->NoseriBarangJadi->id : $d->NoseriBarangJadi->noseri, 'text' => $d->NoseriBarangJadi->noseri);
            }

            if($data->pesanan_id != NULL){
                $pesanan_id = $data->pesanan_id;
                $gdg_brg_id = $c->gdg_brg_jadi_id;
                $arr = NoseriTGbj::whereHas('detail.header', function($q) use($pesanan_id){
                    $q->where('pesanan_id', '=', $pesanan_id);
                })->whereHas('detail', function($q) use($gdg_brg_id){
                    $q->where('gdg_brg_jadi_id', $gdg_brg_id);
                })->get();
                foreach($arr as $keyz => $arrs){
                    $produk[$key]['allseri'][$keyz] = array('id' => $arrs->noseri_id, 'noseri' => $arrs->NoseriBarangJadi->noseri);
                }
            }else if($data->retur_penjualan_id != NULL){
                $retur_id = $data->retur_penjualan_id;
                $gdg_brg_id = $c->gdg_brg_jadi_id;
                $arr = NoseriTGbj::whereHas('detail.header', function($q) use($retur_id){
                    $q->where('retur_penjualan_id', '=', $retur_id);
                })->whereHas('detail', function($q) use($gdg_brg_id){
                    $q->where('gdg_brg_jadi_id', $gdg_brg_id);
                })->get();
                foreach($arr as $keyz => $arrs){
                    $produk[$key]['allseri'][$keyz] = array('id' => $arrs->noseri_id, 'noseri' => $arrs->NoseriBarangJadi->noseri);
                }
            }
        }

        $part = TFProduksiDetail::whereNotNull('m_sparepart_id')->whereHas('header', function($q) use($id){
            $q->where([['retur_penjualan_id', '=', $id], ['jenis', '=', 'masuk']]);
        })->get();
        $prd = json_encode($produk);
        return view('page.as.retur.edit', ['id' => $id, 'data' => $data, 'produk' => $prd, 'part' => $part]);
    }

    public function update_retur(Request $r, $id)
    {
        $validator = Validator::make($r->all(), [
            'tgl_retur' => ['required'],
            'pilih_jenis_retur' => ['required']
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Periksa Kembali Form Anda');
        }else{
            $customer_id = "";
            $pesanan_id = NULL;
            $retur_id = NULL;
            $no_pesanan = NULL;
            $karyawan_id = NULL;
            $pic = NULL;

            if($r->karyawan_id != ""){
                $karyawan_id = $r->karyawan_id;
                $pic = NULL;
            }
            else{
                $pic = $r->pic_peminjaman;
                $karyawan_id = NULL;
            }
            if($r->customer_id != ""){
                $customer_id = $r->customer_id;
            }
            else{
                $c = Customer::create([
                    'id_provinsi' => '35',
                    'nama' => $r->customer_nama,
                    'alamat' => $r->alamat,
                    'telp' => $r->telepon
                ]);
                if($c){
                    $customer_id = $c->id;
                }
            }

            if($r->no_transaksi_ref != "no_retur" && $r->no_transaksi_ref != "sj_retur"){
                if($r->pesanan_id != ""){
                    $pesanan_id = $r->pesanan_id;
                }else{
                    $no_pesanan = $r->no_transaksi;
                }
            }
            else{
                if($r->pesanan_id != ""){
                    $retur_id = $r->pesanan_id;
                }else{
                    $no_pesanan = $r->no_transaksi;
                }
            }

            $u = ReturPenjualan::find($id);
            $u->tgl_retur = $r->tgl_retur;
            $u->jenis = $r->pilih_jenis_retur;
            $u->keterangan = $r->keterangan;
            $u->pesanan_id = $pesanan_id;
            $u->retur_penjualan_id = $retur_id;
            $u->no_pesanan = $no_pesanan;
            $u->customer_id = $customer_id;
            $u->karyawan_id = $karyawan_id;
            $u->pic = $pic;
            $u->telp_pic = $r->telp_pic;
            $up = $u->save();


            $bool = true;
            $tes = NULL;
            if($up){
                $deltgn = NoseriTGbj::whereHas('detail.header', function($q) use($id){
                    $q->where('retur_penjualan_id', '=', $id);
                })->count();
                if($deltgn > 0){

                    $noseri_tg = NoseriTGbj::whereHas('detail.header', function($q) use($id){
                        $q->where('retur_penjualan_id', '=', $id);
                    })->get();

                    foreach($noseri_tg as $i){
                        $un = NoseriBarangJadi::find($i->noseri_id);
                        $un->is_ready = '1';
                        $un->is_aktif = '0';
                        $un->jenis = 'KELUAR';
                        $un->used_by = $pesanan_id != NULL ? $pesanan_id : $no_pesanan;
                        $un->save();
                    }

                    NoseriTGbj::whereHas('detail.header', function($q) use($id){
                        $q->where('retur_penjualan_id', '=', $id);
                    })->delete();
                }

                $deltgd = TFProduksiDetail::whereHas('header', function($q) use($id){
                    $q->where('retur_penjualan_id', '=', $id);
                })->count();
                if($deltgd > 0){
                    TFProduksiDetail::whereHas('header', function($q) use($id){
                        $q->where('retur_penjualan_id', '=', $id);
                    })->delete();
                }
                $tg = TFProduksi::where('retur_penjualan_id', '=', $id)->first();
                if(in_array('produk', $r->pilih_jenis_barang)){
                    if($pesanan_id != NULL || $retur_id != NULL){
                        if($tg){
                            foreach($r->produk_id as $key => $produk){

                                $no_seri = json_decode($r->no_seri_select[$key]);
                                $tgd = TFProduksiDetail::create([
                                    't_gbj_id' => $tg->id,
                                    'detail_pesanan_produk_id' => NULL,
                                    'gdg_brg_jadi_id' => $produk,
                                    'qty' => count($no_seri),
                                    'jenis' => 'masuk',
                                    'status_id' => NULL,
                                    'state_id' => NULL,
                                    'created_by' => Auth::user()->id,
                                ]);

                                if($tgd){
                                    foreach($no_seri as $keys => $noseri){
                                        $tgn = NoseriTGbj::create([
                                            't_gbj_detail_id' => $tgd->id,
                                            'noseri_id' => $noseri->id,
                                            'layout_id' => NULL,
                                            'status_id' => NULL,
                                            'state_id' => NULL,
                                            'jenis' => 'masuk',
                                            'created_by' => Auth::user()->id,
                                        ]);
                                        if(!$tgn){
                                            $bool = false;
                                        }else{
                                            $noseri_retur = NoseriBarangJadi::find($noseri->id);
                                            $noseri_retur->used_by = NULL;
                                            $noseri_retur->is_ready = '0';
                                            $noseri_retur->is_aktif = '0';
                                            $noseri_retur->jenis = 'MASUK';
                                            $u = $noseri_retur->save();
                                            if(!$u){
                                                $bool = false;
                                            }
                                        }
                                    }
                                }
                                else{
                                    $bool = false;
                                }
                            }
                        }
                        else{
                            $bool = false;
                        }

                    } else if($no_pesanan != NULL) {
                        foreach($r->produk_id as $key => $produk){
                            $no_seri = json_decode($r->no_seri_select[$key]);
                            $tgd = TFProduksiDetail::create([
                                't_gbj_id' => $tg->id,
                                'detail_pesanan_produk_id' => NULL,
                                'gdg_brg_jadi_id' => $produk,
                                'qty' => count($no_seri),
                                'jenis' => 'masuk',
                                'status_id' => NULL,
                                'state_id' => NULL,
                                'created_by' => Auth::user()->id,
                            ]);

                            if($tgd){
                                foreach($no_seri as $keys => $noseri){
                                    $snoseri = NoseriBarangJadi::where('noseri', $noseri->id)->first();
                                    if($snoseri != null){
                                        $tgn = NoseriTGbj::create([
                                            't_gbj_detail_id' => $tgd->id,
                                            'noseri_id' => $snoseri->id,
                                            'layout_id' => NULL,
                                            'status_id' => NULL,
                                            'state_id' => NULL,
                                            'jenis' => 'masuk',
                                            'created_by' => Auth::user()->id,
                                        ]);
                                        if(!$tgn){
                                            $bool = false;
                                        }
                                    }else{
                                        $nbj = NoseriBarangJadi::create([
                                            'gdg_barang_jadi_id' => $produk,
                                            'dari' => Auth::user()->Karyawan->divisi_id,
                                            'noseri' => $noseri,
                                            'layout_id' => NULL,
                                            'jenis' => 'MASUK',
                                            'is_ready' => 0,
                                            'used_by' => NULL,
                                            'is_aktif' => 0,
                                            'keterangan' => NULL,
                                            'created_by' => Auth::user()->Karyawan->id,
                                            'is_change' => 1,
                                            'is_delete' => 0
                                        ]);
                                        if($nbj){
                                            $tgn = NoseriTGbj::create([
                                                't_gbj_detail_id' => $tgd->id,
                                                'noseri_id' => $nbj->id,
                                                'layout_id' => NULL,
                                                'status_id' => NULL,
                                                'state_id' => NULL,
                                                'jenis' => 'masuk',
                                                'created_by' => Auth::user()->id,
                                            ]);
                                            if(!$tgn){
                                                $bool = false;
                                            }
                                        }else{
                                            $bool = false;
                                        }
                                    }
                                }
                            }
                            else{
                                $bool = false;
                            }
                        }
                    }
                }

                if(in_array('part', $r->pilih_jenis_barang)){
                    foreach($r->part_id as $key => $part){
                        $tgd = TFProduksiDetail::create([
                            't_gbj_id' => $tg->id,
                            'detail_pesanan_produk_id' => NULL,
                            'gdg_brg_jadi_id' => NULL,
                            'm_sparepart_id' => $part,
                            'qty' => $r->part_jumlah[$key],
                            'jenis' => 'masuk',
                            'status_id' => NULL,
                            'state_id' => NULL,
                            'created_by' => Auth::user()->id,
                        ]);

                        if(!$tgd){
                            $bool = false;
                        }
                    }
                }

                if ($bool == true) {
                    return redirect()->back()->with('success', 'Berhasil mengubah data Retur');
                } else if ($bool == false) {
                    return redirect()->back()->with('error', 'Gagal mengubah data Retur');
                }
            }else{
                // return redirect()->back()->with('error', "no: ".$r->no_retur.", tgl: ".$r->tgl_retur.", jenis: ".$r->pilih_jenis_retur.", pesanan: ".$pesanan_id.", no_pesanan: ".$no_pesanan.", customer_id: ".$customer_id." karyawan_id: ".$r->karyawan_id);
                return redirect()->back()->with('error', "Gagal melakukan pembaruan Data");
            }
        }
    }

    public function get_list_so_selesai($jenis, Request $r){
        $data = NULL;
        if($jenis == "so"){
            $a = Pesanan::has('DetailPesanan.DetailPesananProduk.DetailLogistik')->with(['Ekatalog', 'Spa', 'Spb'])->where('so', 'LIKE', '%'.$r->term.'%')->selectRaw('id, so as nama');
            $data = Pesanan::has('DetailPesananPart.DetailLogistikPart')->with(['Ekatalog', 'Spa', 'Spb'])->where('so', 'LIKE', '%'.$r->term.'%')->selectRaw('id, so as nama')->union($a)->get();
        }
        else if($jenis == "po"){
            $a = Pesanan::has('DetailPesanan.DetailPesananProduk.DetailLogistik')->with(['Ekatalog', 'Spa', 'Spb'])->where('no_po', 'LIKE', '%'.$r->term.'%')->selectRaw('id, no_po as nama');
            $data = Pesanan::has('DetailPesananPart.DetailLogistikPart')->with(['Ekatalog', 'Spa', 'Spb'])->where('no_po', 'LIKE', '%'.$r->term.'%')->selectRaw('id, no_po as nama')->union($a)->get();
        }
        else if($jenis == "no_akn"){
            $data = Ekatalog::has('Pesanan.DetailPesanan.DetailPesananProduk.DetailLogistik')->where('no_paket', 'LIKE', '%'.$r->term.'%')->selectRaw('pesanan_id as id, no_paket as nama')->get();
        }
        else if($jenis == "no_retur"){
            $data = ReturPenjualan::has('Pengiriman')->where('no_retur', 'LIKE', '%'.$r->term.'%')->whereIn('state_id', ['10'])->selectRaw('id as id, no_retur as nama')->get();
        }
        else if($jenis == "sj_retur"){
            $data = Pengiriman::whereNotNull('no_pengiriman')->where('no_pengiriman', 'LIKE', '%'.$r->term.'%')->whereIn('m_state_id', ['10'])->selectRaw('retur_penjualan_id as id, no_pengiriman as nama')->get();
        }
        else{
            $dataproduk = Logistik::join('detail_logistik', 'detail_logistik.logistik_id', '=', 'logistik.id')
            ->join('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
            ->join('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
            ->where('logistik.nosurat', 'LIKE', '%'.$r->term.'%')
            ->selectRaw('pesanan.id as id, logistik.nosurat as nama')->get();

            $datapart = Logistik::join('detail_logistik_part', 'detail_logistik_part.logistik_id', '=', 'logistik.id')
            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan_part.pesanan_id')
            ->where('nosurat', 'LIKE', '%'.$r->term.'%')
            ->selectRaw('pesanan.id as id, logistik.nosurat as nama')->get();

            $data = $dataproduk->merge($datapart);
            // Logistik::where('logistik.nosurat', 'LIKE', '%'.$r->term.'%')->addSelect(['id' => function($q){
            //     $q->selectRaw('pesanan.id')
            //     ->from('pesanan')
            //     ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
            //     ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
            //     ->leftJoin('detail_logistik', 'detail_logistik.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
            //     ->whereColumn('detail_logistik.logistik_id', 'logistik.id')
            //     ->limit(1);
            // }, 'id' => function($q){
            //     $q->selectRaw('pesanan.id')
            //     ->from('pesanan')
            //     ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
            //     ->leftJoin('detail_logistik_part', 'detail_logistik_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
            //     ->whereColumn('detail_logistik_part.logistik_id', 'logistik.id')
            //     ->limit(1);
            // }])->selectRaw('nosurat as nama')->get();
        }


        echo json_encode($data);
    }

    public function get_list_so_selesai_paket($id, $jenis, Request $r){
        $data_produk = array();
        $data_part = array();
        if($jenis == "jual"){
            $datas = DetailPesananProduk::whereHas('DetailPesanan', function($q)use($id){
                $q->where('pesanan_id', $id);
            })->has('DetailLogistik')->with('GudangBarangJadi.Produk')->whereHas('GudangBarangJadi.Produk', function($q) use($r){
                $q->where('nama', 'LIKE', '%'.$r->term.'%');
            })->get();


            foreach($datas as $key_produk => $produk){
                $data_produk[$key_produk] = array(
                'id' => $produk->GudangBarangJadi->id,
                'nama' => $produk->GudangBarangJadi->Produk->nama." ".$produk->GudangBarangJadi->nama,
                'no_seri' => array());
                foreach($produk->NoseriDetailPesanan as $key_no_seri => $noseri){
                    $data_produk[$key_produk]['no_seri'][$key_no_seri] = array(
                        'id' => $noseri->NoseriTGbj->NoseriBarangJadi->id,
                        'text' => $noseri->NoseriTGbj->NoseriBarangJadi->noseri
                    );
                }
            }

            $datap = DetailPesananPart::where('pesanan_id', $id)->with('Sparepart')->whereHas('Sparepart', function($q) use($r){
                $q->where('nama', 'LIKE', '%'.$r->term.'%');
            })->get();

            foreach($datap as $key_part => $part){
                $data_part[$key_part] = array(
                    'id' => $part->Sparepart->id,
                    'nama' => $part->Sparepart->nama,
                    'jumlah' => $part->jumlah
                );
            }
        }
        else{
            $datas = GudangBarangJadi::whereHas('noseri.PengirimanNoseri.Pengiriman', function($q) use($id){
                $q->where('retur_penjualan_id', $id);
            })->whereHas('Produk', function($q) use($r){
                $q->where('nama', 'LIKE', '%'.$r->term.'%');
            })->get();

            foreach($datas as $key_produk => $produk){
                $data_produk[$key_produk] = array(
                'id' => $produk->id,
                'nama' => $produk->Produk->nama." ".$produk->nama,
                'no_seri' => array());

                $no_seri = PengirimanNoseri::whereHas('Pengiriman', function($q) use($id){
                    $q->where('retur_penjualan_id', $id);
                })->whereHas('NoseriBarangJadi', function($q) use($produk){
                    $q->where('gdg_barang_jadi_id', $produk->id);
                })->get();

                foreach($no_seri as $key_no_seri => $noseri){
                    $data_produk[$key_produk]['no_seri'][$key_no_seri] = array(
                        'id' => $noseri->NoseriBarangJadi->id,
                        'text' => $noseri->NoseriBarangJadi->noseri
                    );
                }
            }

            $datap = PengirimanPart::whereHas('Pengiriman', function($q) use($id){
                $q->where('retur_penjualan_id', $id);
            })->with('Sparepart')->whereHas('Sparepart', function($q) use($r){
                $q->where('nama', 'LIKE', '%'.$r->term.'%');
            })->get();

            foreach($datap as $key_part => $part){
                $data_part[$key_part] = array(
                    'id' => $part->Sparepart->id,
                    'nama' => $part->Sparepart->nama,
                    'jumlah' => $part->jumlah
                );
            }
        }

        return response()->json(['produk' => $data_produk, 'part' => $data_part]);
    }

    public function get_list_so_selesai_paket_produk($id, $jenis, Request $r){
        $data = DetailPesananProduk::where('detail_pesanan_id', $id)->has('DetailLogistik')->with('GudangBarangJadi.Produk')->whereHas('GudangBarangJadi.Produk', function($q) use($r){
            $q->where('nama', 'LIKE', '%'.$r->term.'%');
        })->get();
        echo json_encode($data);
    }

    public function get_detail_so_retur($id, $jenis){
        $data = NULL;
        if($jenis == "jual"){
            $ekat = Ekatalog::where('pesanan_id', $id)->with(['Pesanan', 'Customer.Provinsi'])->first();
            $spa = Spa::where('pesanan_id', $id)->with(['Pesanan', 'Customer.Provinsi'])->first();
            $spb = Spb::where('pesanan_id', $id)->with(['Pesanan', 'Customer.Provinsi'])->first();

            if($ekat){
                $data = $ekat;
            }
            if($spa){
                $data = $spa;
            }
            if($spb){
                $data = $spb;
            }
        }else{
            $data = ReturPenjualan::where('id', $id)->with(['Customer.Provinsi'])->first();
        }

        echo json_encode($data);
    }
    public function get_all_retur(Request $r){
        $d = ReturPenjualan::where('no_retur', 'LIKE', '%'.$r->input('term', '').'%')
             ->whereNotIn('state_id', ['10'])
             ->whereNotIn('jenis', ['none'])
             ->has('TFProduksi.detail.produk')
             ->with('TFProduksi.detail.produk', 'Customer')
             ->addSelect(['count_noseri' => function($q){
                $q->selectRaw('count(t_gbj_noseri.id)')
                ->from('t_gbj_noseri')
                ->join('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->where('t_gbj.jenis', '=', 'masuk')
                ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
             }, 'count_perbaikan' => function($q){
                $q->selectRaw('coalesce(count(noseri_perbaikan.id), 0)')
                ->from('noseri_perbaikan')
                ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
                ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
             }])
             ->havingRaw('count_perbaikan < count_noseri')
             ->get();
        $data = array();
        foreach($d as $key => $i){
            $pesanan = NULL;
            if($i->pesanan_id != null) {
                $pesanan = $i->Pesanan->no_po;
            }
            else if($i->retur_penjualan_id != null){
                $pesanan = $i->ReturPenjualanChild->no_retur;
            }
            else{
                $pesanan = $i->no_pesanan;
            }
            $data[$key] = array(
                'id' => $i->id,
                'no_retur' => $i->no_retur,
                'tgl_retur' => $i->tgl_retur,
                'keterangan' => $i->keterangan,
                'jenis' => $i->jenis,
                'tgl_retur' => $i->tgl_retur,
                'no_pesanan' => $pesanan,
                'customer' => $i->Customer->nama,
                'customer_id' => $i->Customer->id,
                'alamat' => $i->Customer->alamat,
                'telp' => $i->Customer->telp != null ? $i->Customer->telp : '-',
                'produk' => array()
            );
            $id = $i->id;
            $p = TFProduksiDetail::whereHas('header', function($q) use($id){
                $q->where([['retur_penjualan_id', '=', $id], ['jenis', '=', 'masuk']]);
            })->addSelect(['count_noseri' => function($q){
                $q->selectRaw('count(t_gbj_noseri.id)')
                ->from('t_gbj_noseri')
                ->whereColumn('t_gbj_noseri.t_gbj_detail_id', 't_gbj_detail.id');
            }, 'count_perbaikan' => function($q) use($id){
                $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
                ->from('noseri_perbaikan')
                ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
                ->where('perbaikan.retur_id', $id)
                ->whereColumn('perbaikan.gdg_barang_jadi_id', 't_gbj_detail.gdg_brg_jadi_id');
            }])->havingRaw('count_perbaikan < count_noseri')->get();
            foreach($p as $keys => $j){
                $data[$key]['produk'][$keys] = array(
                    'id' => $j->gdg_brg_jadi_id,
                    'nama_produk' => $j->produk->Produk->nama.' '.$j->produk->nama,
                );
            }
        }

        return response()->json($data);
    }

    public function data_perbaikan()
    {
        $p = Perbaikan::addSelect(['count_perbaikan' => function($q){
            $q->selectRaw('coalesce(count("noseri_perbaikan.id"), 0)')
            ->from('noseri_perbaikan')
            ->whereColumn('noseri_perbaikan.perbaikan_id', 'perbaikan.id');
        }, 'count_perbaikan_karantina' => function($q){
            $q->selectRaw('coalesce(count("noseri_perbaikan.id"), 0)')
            ->from('noseri_perbaikan')
            ->where('noseri_perbaikan.m_status_id', '=', '2')
            ->whereNull('noseri_pengganti_id')
            ->whereIn('noseri_perbaikan.tindak_lanjut', ['karantina'])
            ->whereColumn('noseri_perbaikan.perbaikan_id', 'perbaikan.id');
        }, 'count_perbaikan_non_karantina' => function($q){
            $q->selectRaw('coalesce(count("noseri_perbaikan.id"), 0)')
            ->from('noseri_perbaikan')
            ->where('noseri_perbaikan.m_status_id', '=', '2')
            ->whereNotIn('noseri_perbaikan.tindak_lanjut', ['karantina'])
            ->whereColumn('noseri_perbaikan.perbaikan_id', 'perbaikan.id');
        }, 'count_perbaikan_pengganti' => function($q){
            $q->selectRaw('coalesce(count("noseri_perbaikan.id"), 0)')
            ->from('noseri_perbaikan')
            ->where('noseri_perbaikan.m_status_id', '=', '2')
            ->whereNotNull('noseri_pengganti_id')
            ->whereIn('noseri_perbaikan.tindak_lanjut', ['karantina'])
            ->whereColumn('noseri_perbaikan.perbaikan_id', 'perbaikan.id');
        }, 'count_pengiriman' => function($q){
            $q->selectRaw('coalesce(count("pengiriman_noseri.id"),0)')
            ->from('pengiriman_noseri')
            ->join('noseri_perbaikan', 'noseri_perbaikan.noseri_barang_jadi_id', '=', 'pengiriman_noseri.noseri_barang_jadi_id')
            ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
            ->whereColumn('noseri_perbaikan.perbaikan_id', 'perbaikan.id')
            ->whereColumn('pengiriman.retur_penjualan_id', 'perbaikan.retur_id');
        }, 'count_pengiriman_pengganti' => function($q){
            $q->selectRaw('coalesce(count("pengiriman_noseri.id"),0)')
            ->from('pengiriman_noseri')
            ->join('noseri_perbaikan', 'noseri_perbaikan.noseri_pengganti_id', '=', 'pengiriman_noseri.noseri_barang_jadi_id')
            ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
            ->whereColumn('noseri_perbaikan.perbaikan_id', 'perbaikan.id')
            ->whereColumn('pengiriman.retur_penjualan_id', 'perbaikan.retur_id');
        }])->get();
        $data = array();

        foreach($p as $key => $res){
            $data[$key] = array(
                'id' => $res->id,
                'no_perbaikan' => $res->no_perbaikan,
                'tanggal' => $res->tanggal,
                'keterangan' => $res->keterangan != null ? $res->keterangan : '-',
                'count_perbaikan' => $res->count_perbaikan,
                'count_perbaikan_karantina' => $res->count_perbaikan_karantina,
                'count_perbaikan_non_karantina' => $res->count_perbaikan_non_karantina,
                'count_perbaikan_pengganti' => $res->count_perbaikan_pengganti,
                'count_pengiriman' => $res->count_pengiriman,
                'count_pengiriman_pengganti' => $res->count_pengiriman_pengganti,
                'produk_id' => $res->GudangBarangJadi->id,
                'produk' => $res->GudangBarangJadi->Produk->nama." ".$res->GudangBarangJadi->nama,
                'karyawan' => array(),
                'customer' => array(
                    'nama' => $res->ReturPenjualan->Customer->nama,
                    'alamat' => $res->ReturPenjualan->Customer->alamat,
                    'telp' => $res->ReturPenjualan->Customer->telepon,
                ),
                'retur' => array(
                    'no' => $res->ReturPenjualan->no_retur,
                    'tgl' => $res->ReturPenjualan->tgl_retur,
                    'jenis' => $res->ReturPenjualan->jenis,
                    'keterangan' => $res->ReturPenjualan->keterangan != null ? $res->ReturPenjualan->keterangan : '-',
                ),
                'status' => $res->State->nama,
            );
            foreach($res->KaryawanPerbaikan as $keys => $r){
                $data[$key]['karyawan'][$keys] = $r->Karyawan->nama;
            }
        }

        return response()->json(['data' => $data]);
    }

    public function detail_noseri_perbaikan($id)
    {
        $n = NoseriPerbaikan::where('perbaikan_id', $id)->get();
        $data = array();

        foreach($n as $key => $r){
            $data[$key] = array(
                'id' => $r->id,
                'perbaikan_id' => $id,
                'no_seri' => $r->NoseriBarangJadi->noseri,
                'tindak_lanjut' => $r->tindak_lanjut,
                'noseri_pengganti_id' => $r->noseri_pengganti_id != NULL ? $r->NoseriPengganti->noseri : NULL,
                'status' => $r->status->nama
            );
        }

        return response()->json(['data' => $data]);
    }

    public function detail_part_pengganti($id){
        $p = PartPenggantiPerbaikan::where('perbaikan_id', $id)->get();
        $data = array();

        foreach($p as $key => $r){
            $data[$key] = array(
                'id' => $r->id,
                'part' => $r->Sparepart->nama,
                'jumlah' => $r->jumlah
            );
        }

        return response()->json(['data' => $data]);
    }

    public function store_perbaikan(Request $r){
        $validator = Validator::make($r->all(), [
            'tgl_perbaikan' => 'required',
            'no_retur' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Periksa Kembali Form Anda');
        } else {
            $no_pbk = $this->no_pbk($r->tgl_perbaikan);
            $bool = true;
            $c = Perbaikan::create([
                'retur_id' => $r->no_retur,
                'no_perbaikan' => $no_pbk,
                'gdg_barang_jadi_id' => $r->produk_id,
                'tanggal' => $r->tgl_perbaikan,
                'keterangan' => $r->keterangan,
                'm_state_id' => '5'
            ]);
            $tgbj_id = NULL;
            $tgbj = TFProduksi::where([['retur_penjualan_id', '=', $r->no_retur], ['jenis', '=', 'keluar']])->first();

            if($tgbj != NULL){
                $tgbj_id = $tgbj->id;
            }else{
                $ct = TFProduksi::create([
                    'dari' => '13',
                    'ke' => Auth::user()->Karyawan->divisi_id,
                    'deskripsi' => NULL,
                    'status_id' => NULL,
                    'pesanan_id' => NULL,
                    'retur_penjualan_id' => $r->no_retur,
                    'tgl_keluar' => $r->tgl_perbaikan,
                    'tgl_masuk' => NULL,
                    'state_id' => NULL,
                    'jenis' => 'keluar',
                    'created_by' => Auth::user()->id
                ]);
                $tgbj_id = $ct->id;
            }

            $tgbjdetail_id = NULL;
            $tgbjdetail = TFProduksiDetail::where([['t_gbj_id', '=', $tgbj_id], ['gdg_brg_jadi_id', '=', $r->produk_id]])->first();

            if($tgbjdetail != NULL){
                $tgbjdetail_id = $tgbjdetail->id;
            }else{
                $ctd = TFProduksiDetail::create([
                    't_gbj_id' => $tgbj_id,
                    'detail_pesanan_produk_id' => NULL,
                    'gdg_brg_jadi_id' => $r->produk_id,
                    'qty' => NULL,
                    'jenis' => 'keluar',
                    'status_id' => NULL,
                    'state_id' => NULL,
                    'created_by' => Auth::user()->id,
                ]);
                $tgbjdetail_id = $ctd->id;
            }

            if($c){
                foreach($r->operator as $key => $kary){
                    $k = KaryawanPerbaikan::create([
                        'perbaikan_id' => $c->id,
                        'karyawan_id' => $kary
                    ]);

                    if(!$k){
                        $bool = false;
                    }
                }

                foreach($r->no_seri_id as $key => $noseri){
                    $n = NoseriPerbaikan::create([
                        'noseri_barang_jadi_id' => $noseri,
                        'perbaikan_id' => $c->id,
                        'tindak_lanjut' => $r->tindak_lanjut[$key],
                        'm_status_id' => '1',
                        'noseri_pengganti_id' => NULL
                    ]);

                    if(!$n){
                        $bool = false;
                    }
                    else
                    {
                        if($r->tindak_lanjut[$key] != "karantina"){
                            NoseriPerbaikan::where('id', $n->id)->update([
                                'm_status_id' => '2'
                            ]);
                            $tgn = NoseriTGbj::create([
                                't_gbj_detail_id' => $tgbjdetail_id,
                                'noseri_id' => $noseri,
                                'layout_id' => NULL,
                                'status_id' => NULL,
                                'state_id' => NULL,
                                'jenis' => 'keluar',
                                'created_by' => Auth::user()->id,
                            ]);
                            if(!$tgn){
                                $bool = false;
                            }else{
                                $un = NoseriBarangJadi::find($noseri);
                                $un->is_ready = '1';
                                $un->dari = '8';
                                $un->ke = '15';
                                $un->jenis = 'KELUAR';
                                $un->used_by = "ReturID: ".$r->no_retur;
                                $un->save();
                            }
                        }else{
                            NoseriPerbaikan::where('id', $n->id)->update([
                                'm_status_id' => '1'
                            ]);
                        }
                    }
                }

                if($r->part_id != null){
                    foreach($r->part_id as $key => $part){
                        $p = PartPenggantiPerbaikan::create([
                            'm_sparepart_id' => $part,
                            'perbaikan_id' => $c->id,
                            'jumlah' => $r->part_jumlah[$key]
                        ]);
                        if(!$p){
                            $bool = false;
                        }else{
                            $ctd = TFProduksiDetail::create([
                                't_gbj_id' => $tgbj_id,
                                'detail_pesanan_produk_id' => NULL,
                                'm_sparepart_id' => $part,
                                'qty' => $r->part_jumlah[$key],
                                'jenis' => 'keluar',
                                'status_id' => NULL,
                                'state_id' => NULL,
                                'created_by' => Auth::user()->id,
                            ]);
                        }
                    }
                }
            }
            if ($bool == true) {
                return redirect()->back()->with('success', 'Berhasil menambahkan Perbaikan');
            } else if ($bool == false) {
                return redirect()->back()->with('error', 'Gagal menambahkan Perbaikan');
            }
        }
    }

    public function get_noseri_pengganti($id){
        $noseri = NoseriBarangJadi::where([['gdg_barang_jadi_id', '=', $id], ['jenis', '=', 'MASUK']])->whereHas('NoseriTGbj.detail.header', function($q){
            $q->where('ke', '=', '8')->whereNull('retur_penjualan_id');
        })->get();

        $data = array();
        foreach($noseri as $key => $r){
            $data[$key] = array(
                'id' => $r->id,
                'noseri' => $r->noseri
            );
        }

        return response()->json($data);
    }

    public function save_switch_noseri(Request $r){
        $bool = true;

        $n = NoseriPerbaikan::find($r->noseri_perbaikan_id);
        $n->noseri_pengganti_id = $r->noseri_id;
        $n->m_status_id = '2';
        $n->save();

        $retur_id = $n->Perbaikan->retur_id;
        $gdg_barang_jadi_id = $n->Perbaikan->gdg_barang_jadi_id;
        $tg = TFProduksi::where([
            ['retur_penjualan_id','=',$retur_id],
            ['jenis','=','keluar']
        ])->first();

        $tgd = NULL;
        $tgdc = TFProduksiDetail::where([
            ['t_gbj_id', '=', $tg->id],
            ['gdg_brg_jadi_id', '=', $gdg_barang_jadi_id]
        ])->count();

        if($tgdc > 0){
            $tgd = TFProduksiDetail::where([
                ['t_gbj_id', '=', $tg->id],
                ['gdg_brg_jadi_id', '=', $gdg_barang_jadi_id]
            ])->first();
        }else{
            $tgd = TFProduksiDetail::create([
                't_gbj_id' => $tg->id,
                'detail_pesanan_produk_id' => NULL,
                'gdg_brg_jadi_id' => $gdg_barang_jadi_id,
                'm_sparepart_id' => NULL,
                'qty' => '1',
                'jenis' => 'keluar',
                'status_id' => NULL,
                'state_id' => NULL,
                'created_by' => Auth::user()->id,
            ]);
        }

        $tgn = NoseriTGbj::create([
            't_gbj_detail_id' => $tgd->id,
            'noseri_id' => $r->noseri_id,
            'layout_id' => NULL,
            'status_id' => NULL,
            'state_id' => NULL,
            'jenis' => 'keluar',
            'created_by' => Auth::user()->id,
        ]);

        if($tgn){
            $un = NoseriBarangJadi::find($r->noseri_id);
            $un->is_ready = '1';
            $un->is_aktif = '0';
            $un->dari = '8';
            $un->ke = '15';
            $un->jenis = 'KELUAR';
            $un->used_by = "ReturID: ".$retur_id;
            $un->save();

            $uk = NoseriBarangJadi::find($n->noseri_barang_jadi_id);
            $uk->is_ready = '0';
            $uk->is_aktif = '1';
            $un->dari = '8';
            $un->ke = '13';
            $uk->jenis = 'MASUK';
            $uk->used_by = NULL;
            $uk->save();
        }else{
            $bool = false;
        }

        if ($bool == true) {
            return response()->json(['data' =>  'success']);
        } else {
            return response()->json(['data' =>  'error']);
        }
    }

    public function done_karantina_noseri(Request $r){
        $n = NoseriPerbaikan::find($r->id);
        $n->m_status_id = '2';
        $u = $n->save();

        $uk = NoseriBarangJadi::find($n->noseri_barang_jadi_id);
        $uk->is_ready = '0';
        $uk->is_aktif = '1';
        $uk->dari = '8';
        $uk->ke = '13';
        $uk->jenis = 'MASUK';
        $uk->used_by = NULL;
        $uk->save();

        $krm = ReturPenjualan::where('id', $n->Perbaikan->retur_id)->addSelect(['count_noseri' => function($q){
            $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
            ->from('t_gbj_noseri')
            ->join('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            ->where('t_gbj.jenis', '=', 'masuk')
            ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
        },'count_perbaikan_karantina' => function($q){
            $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
            ->from('noseri_perbaikan')
            ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
            ->where('noseri_perbaikan.m_status_id', '=', '2')
            ->where('noseri_perbaikan.noseri_pengganti_id', '=', NULL)
            ->where('noseri_perbaikan.tindak_lanjut', '=', 'karantina')
            ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
        }, 'count_kirim_noseri' => function($q){
            $q->selectRaw('coalesce(COUNT(pengiriman_noseri.id), 0)')
            ->from('pengiriman_noseri')
            ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
            ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
        }, 'count_part' => function($q){
            $q->selectRaw('coalesce(SUM(t_gbj_detail.qty), 0)')
            ->from('t_gbj_detail')
            ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            ->where('t_gbj.jenis', 'masuk')
            ->whereNotNull('t_gbj_detail.m_sparepart_id')
            ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
        }, 'count_kirim_part' => function($q){
            $q->selectRaw('coalesce(SUM(pengiriman_part.jumlah), 0)')
            ->from('pengiriman_part')
            ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_part.pengiriman_id')
            ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
        }])->first();

        if($krm){
            if($krm->count_noseri > 0 && $krm->count_part > 0){
                $count_all = $krm->count_kirim_noseri + $krm->count_perbaikan_karantina;
                if($krm->count_noseri <= $count_all && $krm->count_part <= $krm->count_kirim_part){
                    $retur = ReturPenjualan::find($n->Perbaikan->retur_id);
                    $retur->state_id = '10';
                    $retur->save();
                }
            }else{
                if($krm->count_noseri > 0){
                    $count_all = $krm->count_kirim_noseri + $krm->count_perbaikan_karantina;
                    if($krm->count_noseri <= $count_all){
                        $retur = ReturPenjualan::find($n->Perbaikan->retur_id);
                        $retur->state_id = '10';
                        $retur->save();
                    }
                }else{
                    if($krm->count_part <= $krm->count_kirim_part){
                        $retur = ReturPenjualan::find($n->Perbaikan->retur_id);
                        $retur->state_id = '10';
                        $retur->save();
                    }
                }
            }
        }
        else{
            $bool = false;
        }

        if($u){
            return response()->json(['res' => 'success', 'msg' => 'Berhasil dikirim ke karantina']);
        }else{
            return response()->json(['res' => 'error', 'msg' => 'Gagal dikirim ke karantina']);
        }
    }

    public function get_no_perbaikan(Request $r){
        $data = 0;
        if($r->id == "0"){
            $data = Perbaikan::where('no_perbaikan', $r->nomor)->count();
        }
        else{
            $data = Perbaikan::where('no_perbaikan', $r->nomor)->whereNotIn('id', [$r->id])->count();
        }
        return response()->json($data);
    }

    public function edit_perbaikan($id){
        $data = Perbaikan::find($id);
        $retur = $data->retur_id;
        $n = NoseriPerbaikan::where('perbaikan_id', $id)->whereDoesntHave('NoseriBarangJadi.PengirimanNoseri.Pengiriman', function($q) use($retur){
            $q->where('retur_penjualan_id', '=', $retur);
        })->get();
        $noseri = array();

        foreach($n as $key => $r){
            $noseri[$key] = array(
                'id' => $r->noseri_barang_jadi_id,
                'no_seri' => $r->NoseriBarangJadi->noseri,
                'tindak_lanjut' => $r->tindak_lanjut,
                'status' => $r->status->nama
            );
        }

        $karyawan = Karyawan::all();
        $kar = KaryawanPerbaikan::where('perbaikan_id', $id)->pluck('karyawan_id');
        return view('page.as.perbaikan.edit', ['id' => $id, 'data' => $data, 'k' => $kar, 'karyawan' => $karyawan, 'noseri' => $noseri]);
    }

    public function update_perbaikan(Request $r, $id){
        $validator = Validator::make($r->all(), [
            'tgl_perbaikan' => ['required'],
        ]);
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Periksa Kembali Form Anda');
        } else {
            $bool = true;
            $p = Perbaikan::find($id);
            $retur = $p->retur_id;

            $dk = KaryawanPerbaikan::where('perbaikan_id', '=', $id)->count();
            if($dk > 0){
                $deletek = KaryawanPerbaikan::where('perbaikan_id', '=', $id)->delete();
            }

            $dn = NoseriPerbaikan::where('perbaikan_id', '=', $id)->whereDoesntHave('NoseriBarangJadi.PengirimanNoseri.Pengiriman', function($q) use($retur){
                $q->where('retur_penjualan_id', '=', $retur);
            })->get();
            if(count($dn) > 0){
                $arraynoseri = array();
                foreach($dn as $key => $i){
                    $arraynoseri[$key] = $i->noseri_barang_jadi_id;
                    $un = NoseriBarangJadi::find($i->noseri_barang_jadi_id);
                    $un->is_ready = '0';
                    $un->is_aktif = '0';
                    $un->jenis = 'MASUK';
                    $un->used_by = NULL;
                    $un->save();
                }
                $del_tgbj_noseri = NoseriTGbj::whereIn('noseri_id', $arraynoseri)->whereHas('detail.header', function($q) use($retur){
                    $q->where([['retur_penjualan_id', '=', $retur], ['jenis', '=', 'keluar']]);
                })->delete();
                $deleten = NoseriPerbaikan::where('perbaikan_id', '=', $id)->whereDoesntHave('NoseriBarangJadi.PengirimanNoseri.Pengiriman', function($q) use($retur){
                    $q->where('retur_penjualan_id', '=', $retur);
                })->delete();
            }

            $dp = PartPenggantiPerbaikan::where('perbaikan_id', '=', $id)->get();
            if(count($dp) > 0){
                foreach($dp as $key => $i){
                    $del_tgbj_detail = TFProduksiDetail::where([['m_sparepart_id', '=', $i->m_sparepart_id], ['qty', '=', $i->jumlah]])->whereHas('header', function($q) use($retur){
                        $q->where([['retur_penjualan_id', '=', $retur], ['jenis', '=', 'keluar']]);
                    })->delete();
                }

                $deletep = PartPenggantiPerbaikan::where('perbaikan_id', '=', $id)->delete();
            }

            $p->gdg_barang_jadi_id = $r->produk_id;
            $p->tanggal = $r->tgl_perbaikan;
            $p->keterangan = $r->keterangan;
            $u = $p->save();

            if($u){
                $tgbj = TFProduksi::where([['retur_penjualan_id', '=', $retur], ['jenis', '=', 'keluar']])->first();
                $tgbj_id = $tgbj->id;
                $tgbjdetail_id = NULL;
                $tgbjdetail = TFProduksiDetail::where([['t_gbj_id', '=', $tgbj_id], ['gdg_brg_jadi_id', '=', $r->produk_id]])->first();

                if($tgbjdetail != NULL){
                    $tgbjdetail_id = $tgbjdetail->id;
                }else{
                    $ctd = TFProduksiDetail::create([
                        't_gbj_id' => $tgbj_id,
                        'detail_pesanan_produk_id' => NULL,
                        'gdg_brg_jadi_id' => $r->produk_id,
                        'qty' => NULL,
                        'jenis' => 'keluar',
                        'status_id' => NULL,
                        'state_id' => NULL,
                        'created_by' => Auth::user()->id,
                    ]);
                    $tgbjdetail_id = $ctd->id;
                }
                foreach($r->operator as $key => $kary){
                    $k = KaryawanPerbaikan::create([
                        'perbaikan_id' => $id,
                        'karyawan_id' => $kary
                    ]);

                    if(!$k){
                        $bool = false;
                    }
                }

                foreach($r->no_seri_id as $key => $noseri){
                    $n = NoseriPerbaikan::create([
                        'noseri_barang_jadi_id' => $noseri,
                        'perbaikan_id' => $id,
                        'tindak_lanjut' => $r->tindak_lanjut[$key],
                        'm_status_id' => '1',
                        'noseri_pengganti_id' => NULL
                    ]);

                    if(!$n){
                        $bool = false;
                    }
                    else
                    {
                        if($r->tindak_lanjut[$key] != "karantina"){
                            NoseriPerbaikan::where('id', $n->id)->update([
                                'm_status_id' => '2'
                            ]);
                            $tgn = NoseriTGbj::create([
                                't_gbj_detail_id' => $tgbjdetail_id,
                                'noseri_id' => $noseri,
                                'layout_id' => NULL,
                                'status_id' => NULL,
                                'state_id' => NULL,
                                'jenis' => 'keluar',
                                'created_by' => Auth::user()->id,
                            ]);
                            if(!$tgn){
                                $bool = false;
                            } else{
                                $un = NoseriBarangJadi::find($noseri);
                                $un->is_ready = '1';
                                $un->jenis = 'KELUAR';
                                $un->used_by = "ReturID: ".$p->retur_id;
                                $un->save();
                            }
                        }else{
                            NoseriPerbaikan::where('id', $n->id)->update([
                                'm_status_id' => '1'
                            ]);
                        }
                    }
                }

                if($r->part_id != null){
                    foreach($r->part_id as $key => $part){
                        $p = PartPenggantiPerbaikan::create([
                            'm_sparepart_id' => $part,
                            'perbaikan_id' => $id,
                            'jumlah' => $r->part_jumlah[$key]
                        ]);
                        if(!$p){
                            $bool = false;
                        }else{
                            $ctd = TFProduksiDetail::create([
                                't_gbj_id' => $tgbj_id,
                                'detail_pesanan_produk_id' => NULL,
                                'm_sparepart_id' => $part,
                                'qty' => $r->part_jumlah[$key],
                                'jenis' => 'keluar',
                                'status_id' => NULL,
                                'state_id' => NULL,
                                'created_by' => Auth::user()->id,
                            ]);
                        }
                    }
                }
            }

            if ($bool == true) {
                return redirect()->back()->with('success', 'Berhasil mengupdate Perbaikan');
            } else if ($bool == false) {
                return redirect()->back()->with('error', 'Gagal mengupdate Perbaikan');
            }
        }
    }

    public function produk_noseri_retur(Request $r){
        $data = array();
        $produk = $r->produk_id;
        $retur = $r->retur_id;
        $perbaikan = $r->id;

        $res = NoseriBarangJadi::whereHas('NoseriTGbj.detail', function($q) use($produk){
            $q->where('gdg_brg_jadi_id', $produk);
        })->whereHas('NoseriTGbj.detail.header', function($q) use($retur){
            $q->where('retur_penjualan_id', $retur);
        })->whereDoesntHave('PengirimanNoseri.Pengiriman', function($q) use($retur){
            $q->where('retur_penjualan_id', $retur);
        })->whereDoesntHave('NoseriPerbaikan', function($q) use($perbaikan){
            $q->where('perbaikan_id', '!=', $perbaikan);
        })->get();

        foreach($res as $key => $i){
            $data[$key] = array('id' => $i->id,
            'noseri' => $i->noseri);
        }

        return response()->json($data);
    }

    public function produk_noseri_non_perbaikan(Request $r){
        $data = array();
        $produk = $r->produk_id;
        $retur = $r->retur_id;

        $res = NoseriBarangJadi::whereHas('NoseriTGbj.detail', function($q) use($produk){
            $q->where('gdg_brg_jadi_id', $produk);
        })->whereHas('NoseriTGbj.detail.header', function($q) use($retur){
            $q->where('retur_penjualan_id', $retur);
        })->whereDoesntHave('NoseriPerbaikan.Perbaikan', function($q) use($retur){
            $q->where('retur_id', $retur);
        })->get();

        foreach($res as $key => $i){
            $data[$key] = array('id' => $i->id,
            'noseri' => $i->noseri);
        }

        return response()->json($data);
    }

    public function data_pengiriman(){
        $p = Pengiriman::all();
        $data = array();
        foreach($p as $key => $i){
            $data[$key] = array(
                'id' => $i->id,
                'no_pengiriman' => $i->no_pengiriman != NULL ? $i->no_pengiriman : NULL,
                'tanggal' => $i->tanggal != NULL ? $i->tanggal : NULL,
                'no_retur' => $i->ReturPenjualan->no_retur,
                'customer_id' => $i->customer_id != NULL ? $i->Customer->nama : $i->nama_penerima,
                'ekspedisi_id' =>  $i->ekspedisi_id != NULL ? $i->Ekspedisi->nama : NULL,
                'pengirim' => $i->pengirim,
                'status' => $i->State->nama
            );
        }

        return response()->json(['data' => $data]);
    }

    public function detail_pengiriman(Request $r){
        $p = Pengiriman::where('id', $r->id)->get();
        $data = array();
        foreach($p as $key => $i){
            $data = array(
                'id' => $i->id,
                'no_pengiriman' => $i->no_pengiriman != NULL ? $i->no_pengiriman : '-',
                'tgl_pengiriman' => $i->tanggal != NULL ? $i->tanggal : '-',
                'ekspedisi_id' => $i->ekspedisi_id != NULL ? $i->Ekspedisi->nama : $i->pengirim,

                'id_ekspedisi' => $i->ekspedisi_id != NULL ? $i->ekspedisi_id : '-',
                'pengirim' => $i->pengirim != NULL ? $i->pengirim : '-',

                'biaya_kirim' => $i->biaya_kirim != NULL ? $i->biaya_kirim : '-',
                'no_resi' => $i->no_resi != NULL ? $i->no_resi : '-',

                'nama_penerima' => $i->customer_id != NULL ? $i->Customer->nama : $i->nama_penerima,
                'alamat_penerima' => $i->customer_id != NULL ? $i->Customer->alamat : $i->alamat_penerima,
                'telp_penerima' => $i->customer_id != NULL ? $i->Customer->telp : $i->telepon_penerima,

                'no_retur' => $i->ReturPenjualan->no_retur,
                'tgl_retur' => $i->ReturPenjualan->tgl_retur,
                'jenis' => $i->ReturPenjualan->jenis != NULL ? $i->ReturPenjualan->jenis : '-',
                'keterangan' => $i->ReturPenjualan->keterangan != NULL ? $i->ReturPenjualan->keterangan : '-',

                'customer_id' => $i->ReturPenjualan->Customer->nama,
                'alamat' => $i->ReturPenjualan->Customer->alamat,
                'telp' => $i->ReturPenjualan->Customer->telp != NULL ? $i->ReturPenjualan->Customer->telp : '-',

                'status' => $i->State->nama,
                'produk' => array(),
                'part' => array(),
            );
            if($i->PengirimanNoseri != null){
                foreach($i->PengirimanNoseri as $keys => $prd){
                    $data['produk'][$keys] = array(
                        'id' => $prd->id,
                        'nama' => $prd->NoseriBarangJadi->gudang->produk->nama.' '.$prd->NoseriBarangJadi->gudang->nama,
                        'noseri' => $prd->NoseriBarangJadi->noseri
                    );
                }
            }
            if($i->PengirimanPart != null){
                foreach($i->PengirimanPart as $keys => $part){
                    $data['part'][$keys] = array(
                        'id' => $part->id,
                        'nama' => $part->Sparepart->nama,
                        'jumlah' => $part->jumlah
                    );
                }
            }
        }

        return response()->json($data);
    }

    public function retur_siap_kirim(Request $r){
        $res = ReturPenjualan::where('no_retur', 'LIKE', '%'.$r->input('term', '').'%')->whereNotIN('state_id', ['10'])
        ->addSelect(['count_perbaikan_non_karantina' => function($q){
            $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
            ->from('noseri_perbaikan')
            ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
            ->where('noseri_perbaikan.m_status_id', '=', '2')
            ->where('noseri_perbaikan.tindak_lanjut', '!=', 'karantina')
            ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
        }, 'count_perbaikan_karantina' => function($q){
            $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
            ->from('noseri_perbaikan')
            ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
            ->where('noseri_perbaikan.m_status_id', '=', '2')
            ->where('noseri_perbaikan.noseri_pengganti_id', '!=', NULL)
            ->where('noseri_perbaikan.tindak_lanjut', '=', 'karantina')
            ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
        }, 'count_kirim_noseri' => function($q){
            $q->selectRaw('coalesce(COUNT(pengiriman_noseri.id), 0)')
            ->from('pengiriman_noseri')
            ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
            ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
        }, 'count_part' => function($q){
            $q->selectRaw('coalesce(SUM(t_gbj_detail.qty), 0)')
            ->from('t_gbj_detail')
            ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            ->where('t_gbj.jenis', 'masuk')
            ->whereNotNull('t_gbj_detail.m_sparepart_id')
            ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
        }, 'count_kirim_part' => function($q){
            $q->selectRaw('coalesce(SUM(pengiriman_part.jumlah), 0)')
            ->from('pengiriman_part')
            ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_part.pengiriman_id')
            ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
        }])->havingRaw('(count_perbaikan_non_karantina + count_perbaikan_karantina) > count_kirim_noseri OR count_part > count_kirim_part')->get();

        $data = array();
        foreach($res as $key => $i){
            $pesanan = NULL;
            if($i->pesanan_id != null){
                $pesanan = $i->Pesanan->no_po;
            }else if($i->retur_penjualan_id != null){
                $pesanan = $i->ReturPenjualanChild->no_retur;
            }else{
                $pesanan = $i->no_pesanan;
            }
            $data[$key] = array(
                'id' => $i->id,
                'no_retur' => $i->no_retur,
                'tgl_retur' => $i->tgl_retur,
                'keterangan' => $i->keterangan,
                'jenis' => $i->jenis,
                'tgl_retur' => $i->tgl_retur,
                'no_pesanan' => $pesanan,
                'customer' => $i->Customer->nama,
                'customer_id' => $i->Customer->id,
                'alamat' => $i->Customer->alamat,
                'telp' => $i->Customer->telp != null ? $i->Customer->telp : '-',
            );
        }

        return response()->json($data);
    }


    public function barang_siap_kirim_retur(Request $r){
        $retur_id = $r->id;

        $resproduknonkarantina = NoseriPerbaikan::whereHas('Perbaikan.ReturPenjualan', function($q) use($retur_id){
            $q->where('id', $retur_id)->whereNotIn('state_id', ['10']);
        })
        ->where('noseri_perbaikan.m_status_id', '=', '2')
        ->whereNotIn('noseri_perbaikan.tindak_lanjut', ['karantina'])
        ->whereDoesntHave('NoseriBarangJadi.PengirimanNoseri.Pengiriman', function($q) use($retur_id){
            $q->where('retur_penjualan_id', $retur_id);
        })->get();
        $resprodukkarantina = NoseriPerbaikan::whereHas('Perbaikan.ReturPenjualan', function($q) use($retur_id){
            $q->where('retur_id', $retur_id)->whereNotIn('state_id', ['10']);
        })
        ->where('noseri_perbaikan.m_status_id', '=', '2')
        ->whereNotNull('noseri_pengganti_id')
        ->whereIn('noseri_perbaikan.tindak_lanjut', ['karantina'])
        ->whereDoesntHave('NoseriBarangJadi.PengirimanNoseri.Pengiriman', function($q) use($retur_id){
            $q->where('retur_penjualan_id', $retur_id);
        })->get();
        $resproduk = $resproduknonkarantina->merge($resprodukkarantina);

        $respart = TFProduksiDetail::whereHas('header', function($q) use($retur_id){
            $q->where([['retur_penjualan_id', '=', $retur_id], ['jenis', '=', 'masuk']]);
        })->whereHas('header.ReturPenjualan', function($q){
            $q->whereNotIn('state_id', ['10']);
        })->whereNotNull('m_sparepart_id')->get();

        $produk = array();
        $part = array();

            foreach($resproduk as $key => $i){
                $produk[$key] = array(
                    'id' => $i->tindak_lanjut != 'karantina' ? $i->noseri_barang_jadi_id : $i->noseri_pengganti_id,
                    'nama_produk' => $i->Perbaikan->GudangBarangJadi->produk->nama.' '.$i->Perbaikan->GudangBarangJadi->nama,
                    'noseri' => $i->tindak_lanjut != 'karantina' ? $i->NoseriBarangJadi->noseri : $i->NoseriPengganti->noseri
                );
            }

            foreach($respart as $key => $i){
                $part[$key] = array(
                    'id' => $i->Sparepart->id,
                    'nama_part' => $i->Sparepart->nama,
                    'jumlah' => $i->qty,
                );
            }

        return response()->json(['produk' => $produk, 'part' => $part]);
    }

    public function store_pengiriman(Request $r){
        $validator = NULL;
        if($r->customer_id != NULL){
            $validator = Validator::make($r->all(), [
                'customer_id' => 'required'
            ]);
        }else{
            $validator = Validator::make($r->all(), [
                'nama_penerima' => 'required',
                'alamat_penerima' => 'required'
            ]);
        }
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Periksa Kembali Form Anda');
        } else {
            $bool = true;
            $customer_id = NULL;
            $nama_penerima = NULL;
            $alamat_penerima = NULL;
            $telp_penerima = NULL;
            if($r->customer_id != NULL){
                $customer_id = $r->customer_id;
            }
            else{
                $nama_penerima = $r->nama_penerima;
                $alamat_penerima = $r->alamat_penerima;
                $telp_penerima = $r->telp_penerima;
            }
            $c = Pengiriman::create([
                'retur_penjualan_id' => $r->no_retur,
                'customer_id' => $customer_id,
                'nama_penerima' => $nama_penerima,
                'alamat_penerima' => $alamat_penerima,
                'telepon_penerima' => $telp_penerima,
                'm_state_id' => '11',
                'created_by' => Auth::user()->id
            ]);

            if($c){
                if(isset($r->no_seri_id) && $r->no_seri_id != null){
                    foreach($r->no_seri_id as $key => $i){
                        $pn = PengirimanNoseri::create([
                            'pengiriman_id' => $c->id,
                            'noseri_barang_jadi_id' => $i
                        ]);
                        if(!$pn){
                            $bool = false;
                        } else{
                            $un = NoseriBarangJadi::find($i);
                            $un->is_ready = '1';
                            $un->jenis = 'KELUAR';
                            $un->used_by = "ReturID: ".$r->no_retur;
                        }
                    }
                }

                if(isset($r->part_id) && $r->part_id != null){
                    foreach($r->part_id as $key => $i){
                        $pp = PengirimanPart::create([
                            'pengiriman_id' => $c->id,
                            'm_sparepart_id' => $i,
                            'jumlah' => $r->part_jumlah[$key],
                        ]);
                        if(!$pp){
                            $bool = false;
                        }
                    }
                }
            }
            if ($bool == true) {
                return redirect()->back()->with('success', 'Berhasil menambahkan Pengiriman');
            } else if ($bool == false) {
                return redirect()->back()->with('error', 'Gagal menambahkan Pengiriman');
            }
        }
    }

    public function edit_pengiriman($id){
        $data = Pengiriman::find($id);
        return view('page.as.pengiriman.edit', ['id' => $id, 'i' => $data]);
    }

    public function update_pengiriman(Request $r, $id){
        $validator = NULL;
        if($r->customer_id != NULL){
            $validator = Validator::make($r->all(), [
                'customer_id' => 'required'
            ]);
        }else{
            $validator = Validator::make($r->all(), [
                'nama_penerima' => 'required',
                'alamat_penerima' => 'required'
            ]);
        }
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Periksa Kembali Form Anda');
        } else {
            $customer_id = NULL;
            $nama_penerima = NULL;
            $alamat_penerima = NULL;
            $telp_penerima = NULL;
            if($r->customer_id != NULL){
                $customer_id = $r->customer_id;
            }
            else{
                $nama_penerima = $r->nama_penerima;
                $alamat_penerima = $r->alamat_penerima;
                $telp_penerima = $r->telp_penerima;
            }
            $u = Pengiriman::find($id);
            $u->customer_id = $customer_id;
            $u->nama_penerima = $nama_penerima;
            $u->alamat_penerima = $alamat_penerima;
            $u->telepon_penerima = $telp_penerima;
            $u->created_by = Auth::user()->id;
            $up = $u->save();
            if ($up) {
                return redirect()->back()->with('success', 'Berhasil mengubah Pengiriman');
            } else {
                return redirect()->back()->with('error', 'Gagal mengubah Pengiriman');
            }
        }
    }

    public function send_pengiriman(Request $r){
        $p = Pengiriman::find($r->id);
        if(isset($r->resi)){
            $p->resi = $r->resi;
        }
        $p->m_state_id = '10';
        $u = $p->save();

        $bool = true;
        $krm = null;
        if($u){
            $krm = ReturPenjualan::where('id', $p->retur_penjualan_id)->addSelect(['count_noseri' => function($q){
                $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                ->from('t_gbj_noseri')
                ->join('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->where('t_gbj.jenis', '=', 'masuk')
                ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
            },'count_perbaikan_karantina' => function($q){
                $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
                ->from('noseri_perbaikan')
                ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
                ->where('noseri_perbaikan.m_status_id', '=', '2')
                ->where('noseri_perbaikan.noseri_pengganti_id', '=', NULL)
                ->where('noseri_perbaikan.tindak_lanjut', '=', 'karantina')
                ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
            }, 'count_kirim_noseri' => function($q){
                $q->selectRaw('coalesce(COUNT(pengiriman_noseri.id), 0)')
                ->from('pengiriman_noseri')
                ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
                ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
            }, 'count_part' => function($q){
                $q->selectRaw('coalesce(SUM(t_gbj_detail.qty), 0)')
                ->from('t_gbj_detail')
                ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->where('t_gbj.jenis', 'masuk')
                ->whereNotNull('t_gbj_detail.m_sparepart_id')
                ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
            }, 'count_kirim_part' => function($q){
                $q->selectRaw('coalesce(SUM(pengiriman_part.jumlah), 0)')
                ->from('pengiriman_part')
                ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_part.pengiriman_id')
                ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
            }])->first();

            if($krm){
                if($krm->count_noseri > 0 && $krm->count_part > 0){
                    $count_all = $krm->count_kirim_noseri + $krm->count_perbaikan_karantina;
                    if($krm->count_noseri <= $count_all && $krm->count_part <= $krm->count_kirim_part){
                        $retur = ReturPenjualan::find($p->retur_penjualan_id);
                        $retur->state_id = '10';
                        $retur->save();
                    }
                }else{
                    if($krm->count_noseri > 0){
                        $count_all = $krm->count_kirim_noseri + $krm->count_perbaikan_karantina;
                        if($krm->count_noseri <= $count_all){
                            $retur = ReturPenjualan::find($p->retur_penjualan_id);
                            $retur->state_id = '10';
                            $retur->save();
                        }
                    }else{
                        if($krm->count_part <= $krm->count_kirim_part){
                            $retur = ReturPenjualan::find($p->retur_penjualan_id);
                            $retur->state_id = '10';
                            $retur->save();
                        }
                    }
                }
            }
            else{
                $bool = false;
            }
        }

        if($bool == true){
            return response()->json(['res' => 'success', 'msg' => 'Berhasil dikirim']);
        }else{
            return response()->json(['res' => 'error', 'msg' => 'Gagal dikirim']);
        }
    }

    public function laporan_retur($tgl_awal, $tgl_akhir){
        return Excel::download(new LaporanAfterSalesRetur($tgl_awal, $tgl_akhir), 'Laporan After Sales Retur Penjualan.xlsx');
    }

    public function data_laporan($tgl_awal, $tgl_akhir){
        $from = date($tgl_awal);
        $to = date($tgl_akhir);
        $array = array();
        $data = ReturPenjualan::whereBetween('tgl_retur', [$from, $to])->whereHas('TFProduksi', function($q){
            $q->where('jenis', 'masuk');
            })->addSelect(['count_noseri' => function($q){
                $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                ->from('t_gbj_noseri')
                ->join('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->where('t_gbj.jenis', '=', 'masuk')
                ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
            },'count_perbaikan_karantina' => function($q){
                $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
                ->from('noseri_perbaikan')
                ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
                ->where('noseri_perbaikan.m_status_id', '=', '2')
                ->where('noseri_perbaikan.noseri_pengganti_id', '=', NULL)
                ->where('noseri_perbaikan.tindak_lanjut', '=', 'karantina')
                ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
            },'count_perbaikan' => function($q){
                $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
                ->from('noseri_perbaikan')
                ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
                ->where('noseri_perbaikan.m_status_id', '=', '2')
                ->where('noseri_perbaikan.tindak_lanjut', '!=', 'karantina')
                ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
            }, 'count_kirim_noseri' => function($q){
                $q->selectRaw('coalesce(COUNT(pengiriman_noseri.id), 0)')
                ->from('pengiriman_noseri')
                ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
                ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
            }, 'count_part' => function($q){
                $q->selectRaw('coalesce(SUM(t_gbj_detail.qty), 0)')
                ->from('t_gbj_detail')
                ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->where('t_gbj.jenis', 'masuk')
                ->whereNotNull('t_gbj_detail.m_sparepart_id')
                ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
            }, 'count_kirim_part' => function($q){
                $q->selectRaw('coalesce(SUM(pengiriman_part.jumlah), 0)')
                ->from('pengiriman_part')
                ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_part.pengiriman_id')
                ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
        }])->get();

        foreach($data as $key => $i){

            $perbaikan = 100;
            if($i->count_noseri > 0){
                $perbaikan = floor((($i->count_perbaikan + $i->count_perbaikan_karantina) / $i->count_noseri) * 100);
            }

            $pengiriman = floor ((($i->count_perbaikan_karantina + $i->count_kirim_noseri + $i->count_kirim_part) / ($i->count_part + $i->count_noseri)) * 100);

            $array[$key] = array('id' => $i->id,
                'produk' => array(),
                'no_retur' => $i->no_retur,
                'tgl_retur' => \Carbon\Carbon::createFromFormat('Y-m-d', $i->tgl_retur)->format('d-m-Y'),
                'jumlah' => count($i->TFProduksi->detail),
                'ket' => $i->keterangan,
                'pic' => $i->karyawan_id != NULL ? $i->karyawan->nama : $i->pic,
                'telp_pic' => $i->telp_pic != NULL ? "(".$i->telp_pic.")" : '',
                'customer' => $i->Customer->nama,
                'jenis' => $i->jenis != "none" ? $i->jenis : 'tanpa status',
                'prog_pengiriman' => $i->state_id != "10" ? $pengiriman : 100,
                'prog_perbaikan' => $perbaikan,
                'row' => 0
            );
            $row = 0;
            foreach($i->TFProduksi->detail as $keys => $j){
                if($j->gdg_brg_jadi_id != NULL){
                    $row = $row + (count($j->seri) > 0 ? count($j->seri) : 1);
                    $array[$key]['produk'][$keys] = array(
                        'id' => $j->gdg_brg_jadi_id,
                        'jumlah_unit' => count($j->seri) > 0 ? count($j->seri) : 1,
                        'noseri' => array(),
                        'produk' => $j->produk->produk->nama.' '.$j->produk->nama
                    );

                    foreach($j->seri as $keyz => $k){
                        $gbj = $j->gdg_brg_jadi_id;
                        $ret = $i->id;
                        $p = NoseriPerbaikan::whereHas('Perbaikan', function($q) use($gbj, $ret){
                            $q->where([['gdg_barang_jadi_id', '=', $gbj], ['retur_id', '=', $ret]]);
                        })->where('noseri_barang_jadi_id', $k->seri->id)->first();
                        $array[$key]['produk'][$keys]['noseri'][$keyz] = array(
                            'noseri' => $k->seri->noseri,
                            'noseri_pengganti' => $p != NULL ? ($p->noseri_pengganti_id != NULL ? $p->NoseriPengganti->noseri : '') : '',
                            'tindak_lanjut' => $p != NULL ? $p->tindak_lanjut : '',
                            'no_perbaikan' => $p != NULL ? $p->Perbaikan->no_perbaikan : '',
                            'tgl_perbaikan' => $p != NULL ? \Carbon\Carbon::createFromFormat('Y-m-d', $p->Perbaikan->tanggal)->format('d-m-Y') : '',
                            'keterangan' => $p != NULL ? $p->Perbaikan->keterangan : '',
                            'no_sj' => NULL,
                            'tgl_kirim' => NULL
                        );
                        $currseri = $p != NULL ? ($p->noseri_pengganti_id != NULL ? $p->noseri_pengganti_id : $k->seri->id) : $k->seri->id;
                        $l = PengirimanNoseri::whereHas('Pengiriman', function($q) use($ret){
                            $q->where('retur_penjualan_id', '=', $ret);
                        })->where('noseri_barang_jadi_id', $currseri)->first();
                        $array[$key]['produk'][$keys]['noseri'][$keyz]['no_sj'] = $l != NULL ? $l->Pengiriman->no_pengiriman : '';
                        $array[$key]['produk'][$keys]['noseri'][$keyz]['tgl_kirim'] = $l != NULL ? $l->Pengiriman->tanggal : '';
                    }
                }
                else{
                    $row = $row + 1;
                    $array[$key]['produk'][$keys] = array(
                        'id' => $j->m_sparepart_id,
                        'jumlah_unit' => 1,
                        'noseri' => array(),
                        'produk' => $j->Sparepart->nama." ".$j->qty
                    );

                    $part = $j->m_sparepart_id;
                    $ret = $i->id;
                    $array[$key]['produk'][$keys]['noseri'][0] = array(
                        'noseri' => '',
                        'noseri_pengganti' => '',
                        'tindak_lanjut' => '',
                        'no_perbaikan' => '',
                        'tgl_perbaikan' => '',
                        'keterangan' => '',
                        'no_sj' => NULL,
                        'tgl_kirim' => NULL
                    );
                    $l = PengirimanPart::whereHas('Pengiriman', function($q) use($ret){
                        $q->where('retur_penjualan_id', '=', $ret);
                    })->where('m_sparepart_id', $part)->first();
                    $array[$key]['produk'][$keys]['noseri'][0]['no_sj'] = $l != NULL ? $l->Pengiriman->no_pengiriman : '';
                    $array[$key]['produk'][$keys]['noseri'][0]['tgl_kirim'] = $l != NULL ? $l->Pengiriman->tanggal : '';
                }
            }
            $array[$key]['row'] = $row;
        }
        return response()->json(['data' => $array]);
    }

    function toRomawi($number)
    {
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    function bulan($value)
    {
        $bulan =  Carbon::createFromFormat('Y-m-d', $value)->format('m');
        return $this->toRomawi($bulan);
    }

    function tahun($value)
    {
        $tahun =  Carbon::createFromFormat('Y-m-d', $value)->format('Y');
        return $tahun;
    }

    function no_bm($value)
    {
        $bulan = $this->bulan($value);
        $tahun = $this->tahun($value);
        $nomor = NULL;
        $res = NULL;

        $k = ReturPenjualan::whereYear('tgl_retur', $tahun)->selectRaw("max(CAST(SUBSTRING(`no_retur`,1,4) as DECIMAL)) as nomor")->first();
        if($k != NULL){
            $nomor = $k->nomor + 1;
            $res = str_pad($nomor, 4,"0",STR_PAD_LEFT).'/BM-'.$bulan.'/'.$tahun;
        }
        else{
            $res = str_pad("1", 4,"0",STR_PAD_LEFT).'/BM-'.$bulan.'/'.$tahun;
        }
        return $res;
    }

    function no_pbk($value)
    {
        $bulan = $this->bulan($value);
        $tahun = $this->tahun($value);
        $nomor = NULL;
        $res = NULL;

        $k = Perbaikan::whereYear('tanggal', $tahun)->selectRaw("max(CAST(SUBSTRING(`no_perbaikan`,1,4) as DECIMAL)) as nomor")->first();
        if($k != NULL){
            $nomor = $k->nomor + 1;
            $res = str_pad($nomor, 4,"0",STR_PAD_LEFT).'/PBK-'.$bulan.'/'.$tahun;
        }
        else{
            $res = str_pad("1", 4,"0",STR_PAD_LEFT).'/PBK-'.$bulan.'/'.$tahun;
        }
        return $res;
    }
}
