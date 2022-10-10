<?php

namespace App\Http\Controllers;
use App\Models\Pesanan;
use App\Models\Logistik;
use App\Models\DetailLogistik;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;

use App\Models\DetailLogistikPart;
use App\Models\DetailPesananPart;
use App\Models\NoseriDetailLogistik;
use App\Models\NoseriDetailPesanan;
use App\Models\Spb;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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

    public function get_data_retur(){

    }

    public function detail_retur(){
        return view('page.as.retur.detail');
    }

    public function create_retur(){
        return view('page.as.retur.create');
    }

    public function store_retur(Request $r){

    }

    public function edit_retur($id){
        return view('page.as.retur.edit', ['id' => $id]);
    }

    public function update_retur(Request $r, $id){
    }

    public function get_list_so_selesai($jenis, Request $r){
        $data = "";
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
        else{
            $data = Logistik::where('nosurat', 'LIKE', '%'.$r->term.'%')->addSelect(['id' => function($q){
                $q->selectRaw('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftJoin('detail_logistik', 'detail_logistik.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->whereColumn('detail_logistik.logistik_id', 'logistik.id')
                ->limit(1);
            }, 'id' => function($q){
                $q->selectRaw('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_logistik_part', 'detail_logistik_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                ->whereColumn('detail_logistik_part.logistik_id', 'logistik.id')
                ->limit(1);
            }])->selectRaw('nosurat as nama')->get();
        }


        echo json_encode($data);
    }

    public function get_list_so_selesai_paket($id, Request $r){
        $data = DetailPesanan::where('pesanan_id', $id)->has('DetailPesananProduk.DetailLogistik')->with('PenjualanProduk')->whereHas('PenjualanProduk', function($q) use($r){
            $q->where('nama', 'LIKE', '%'.$r->term.'%');
        })->get();
        echo json_encode($data);
    }

    public function get_list_so_selesai_paket_produk($id, Request $r){
        $data = DetailPesananProduk::where('detail_pesanan_id', $id)->has('DetailLogistik')->with('GudangBarangJadi.Produk')->whereHas('GudangBarangJadi.Produk', function($q) use($r){
            $q->where('nama', 'LIKE', '%'.$r->term.'%');
        })->get();
        echo json_encode($data);
    }

    public function get_detail_so_retur($id){
        $data = "";
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

        echo json_encode($data);
    }
}
