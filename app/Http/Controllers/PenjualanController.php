<?php

namespace App\Http\Controllers;

use App\Exports\LaporanPenjualan;
use App\Exports\LaporanPenjualanAll;
use App\Models\Customer;
use App\Models\DetailEkatalog;
use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\DetailPesananProduk;
use App\Models\DetailPesananProdukDsb;
use App\Models\DetailRencanaPenjualan;
use App\Models\DetailSpa;
use App\Models\DetailSpb;
use App\Models\NoseriTGbj;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\Logistik;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriDetailPesanan;
use App\Models\OutgoingPesananPart;
use App\Models\Pesanan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\Provinsi;
use App\Models\SaveResponse;
use App\Models\SystemLog;
use App\Models\TFProduksi;
use PDF;
use Carbon\Doctrine\CarbonType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Carbon;

use League\Fractal\Resource\Item;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Validator as ValidationValidator;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Symfony\Component\Console\Input\Input;
use function PHPUnit\Framework\assertIsNotArray;

class PenjualanController extends Controller
{
    public function in_array_all($needles, $haystack)
    {
        return empty(array_diff($needles, $haystack));
    }
    //Get Data Table
    public function penjualan_data($jenis, $status, $tahun)
    {
        $x = explode(',', $jenis);
        $y = explode(',', $status);
        $data = "";
        if ($jenis == "semua" && $status == "semua") {
            $Ekatalog = collect(Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([
                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog as e')
                        ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                        ->whereColumn('e.id', 'ekatalog.id')
                        ->limit(1);
                }, 'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                }

            ])->whereYear('created_at',  $tahun)->orderByRaw('CONVERT(no_urut, SIGNED) desc')->get());

            $Spa = collect(Spa::addSelect([

                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                }

            ])->with(['Pesanan.State',  'Customer'])->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get());

            $Spb = collect(Spb::addSelect([

                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                }

            ])->with(['Pesanan.State',  'Customer'])->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
        } else if ($jenis != "semua" && $status == "semua") {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekatalog', $x)) {
                $Ekatalog = collect(Ekatalog::with(['Pesanan.State', 'Customer'])->addSelect([
                    'tgl_kontrak_custom' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog as e')
                            ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                            ->whereColumn('e.id', 'ekatalog.id')
                            ->limit(1);
                    }, 'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                    }
                ])->orderBy('id', 'DESC')->get());
            }
            if (in_array('spa', $x)) {
                $Spa = collect(Spa::addSelect([
                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    }
                ])->with(['Pesanan.State', 'Customer'])->orderBy('id', 'DESC')->get());
            }
            if (in_array('spb', $x)) {
                $Spb = collect(Spb::addSelect([
                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    }
                ])->with(['Pesanan.State', 'Customer'])->orderBy('id', 'DESC')->get());
            }
            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        } else if ($jenis == "semua" && $status != "semua") {
            $Ekatalog = collect(Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([

                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog as e')
                        ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                        ->whereColumn('e.id', 'ekatalog.id')
                        ->limit(1);
                }, 'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                }
            ])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());

            $Spa = collect(Spa::addSelect([
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                }
            ])->with(['Pesanan.State', 'Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());

            $Spb = collect(Spb::addSelect([
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                }
            ])->with(['Pesanan.State', 'Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                $q->whereIN('log_id', $y);
            })->orderBy('id', 'DESC')->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
        } else {
            $Ekatalog = "";
            $Spa = "";
            $Spb = "";
            if (in_array('ekatalog', $x)) {
                $Ekatalog = collect(Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([
                    'tgl_kontrak_custom' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog as e')
                            ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                            ->whereColumn('e.id', 'ekatalog.id')
                            ->limit(1);
                    }, 'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
                    }
                ])->whereHas('Pesanan', function ($q) use ($y) {
                    $q->whereIN('log_id', $y);
                })->orderBy('id', 'DESC')->get());
            }
            if (in_array('spa', $x)) {
                $Spa = collect(Spa::addSelect([
                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    }
                ])->with(['Pesanan.State', 'Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                    $q->whereIN('log_id', $y);
                })->orderBy('id', 'DESC')->get());
            }
            if (in_array('spb', $x)) {
                $Spb = collect(Spb::addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    }

                ])->with(['Pesanan.State',  'Customer'])->whereHas('Pesanan', function ($q) use ($y) {
                    $q->whereIN('log_id', $y);
                })->orderBy('id', 'DESC')->get());
            }
            if ($Ekatalog != "" && $Spa != "" && $Spb != "") {
                $data = $Ekatalog->merge($Spa)->merge($Spb);
            } else if ($Ekatalog != "" && $Spa != "" && $Spb == "") {
                $data = $Ekatalog->merge($Spa);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb != "") {
                $data = $Ekatalog->merge($Spb);
            } else if ($Ekatalog == "" && $Spa != "" && $Spb != "") {
                $data = $Spa->merge($Spb);
            } else if ($Ekatalog != "" && $Spa == "" && $Spb == "") {
                $data = $Ekatalog;
            } else if ($Ekatalog == "" && $Spa != "" && $Spb == "") {
                $data = $Spa;
            } else if ($Ekatalog == "" && $Spa == "" && $Spb != "") {
                $data = $Spb;
            }
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('jenis_ppic', function ($data) {
                return strtolower($data->getTable());
            })
            ->addColumn('jenis', function ($data) {
                $name = $data->getTable();
                if ($name == 'ekatalog') {
                    return "E-Catalogue";
                } else if ($name == 'spa') {
                    return "SPA";
                } else if ($name == 'spb') {
                    return "SPB";
                }
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->Customer)) {
                    return $data->Customer['nama'];
                } else {
                    return '-';
                }
            })
            ->addColumn('no_paket', function ($data) {
                if (isset($data->no_paket)) {
                    $datas = '';
                    $datas .= '<div>' . $data->no_paket . '</div>';
                    if (!empty($data->status)) {
                        if ($data->status == "batal") {
                            $datas .= '<small class="badge-danger badge">';
                        } else if ($data->status == "negosiasi") {
                            $datas .= '<small class="badge-warning badge">';
                        } else if ($data->status == "draft") {
                            $datas .= '<small class="badge-info badge">';
                        } else if ($data->status == "sepakat") {
                            $datas .= '<small class="badge-success badge">';
                        }
                        $datas .= ucfirst($data->status) . '</small>';
                    }

                    return $datas;
                    // return $data->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('no_paket_ppic', function ($data) {
                if (isset($data->no_paket)) {
                    $datas = '';
                    $datas .= $data->no_paket;
                    return $datas;
                    // return $data->no_paket;
                } else {
                    return '-';
                }
            })
            ->addColumn('status_paket', function ($data) {
                if (isset($data->status)) {
                    return strtolower($data->status);
                } else {
                    return '-';
                }
            })
            ->addColumn('tgl_order', function ($data) {

                if (!empty($data->Pesanan->tgl_po)) {
                    return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                } else {
                    return "-";
                }
            })
            ->addColumn('tgl_kontrak', function ($data) {
                $name = $data->getTable();
                if ($name == 'ekatalog') {
                    if ($data->tgl_kontrak_custom != "") {
                        if ($data->Pesanan->log_id != '10') {
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak_custom;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                    <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else {
                                return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih dari ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                        }
                    }
                }
            })
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->no_po)) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $name = $data->getTable();
                $progress = "";
                $tes = $data->cjumlahprd + $data->cjumlahpart;
                if ($tes > 0) {
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                    if ($hitung > 0) {
                        $progress = '<div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Selesai</small>';
                    } else {
                        $progress = '<div class="progress">
                            <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted">Selesai</small>';
                    }
                }

                if ($name == "ekatalog") {
                    if ($data->status == "batal" && ($data->Pesanan->log_id != "7")) {
                        // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="EKAT" data-provinsi="">
                        //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                        //         <i class="fas fa-times"></i>
                        //         Batal
                        //     </button>
                        // </a>';
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                } else if ($name == "spa") {
                    if ($data->log == "batal") {
                        // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="SPA" data-provinsi="">
                        //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                        //         <i class="fas fa-times"></i>
                        //         Batal
                        //     </button>
                        // </a>';
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                } else if ($name == "spb") {
                    if ($data->log == "batal") {
                        // return '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="SPB" data-provinsi="">
                        //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                        //         <i class="fas fa-times"></i>
                        //         Batal
                        //     </button>
                        // </a>';
                        return '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
                        } else {
                            return $progress;
                        }
                    }
                }

                // $datas = "";
                // if (!empty($data->Pesanan->log_id)) {
                //     if ($data->Pesanan->State->nama == "Penjualan") {
                //         $datas .= '<span class="red-text badge">';
                //     } else if ($data->Pesanan->State->nama == "PO") {
                //         $datas .= '<span class="purple-text badge">';
                //     } else if ($data->Pesanan->State->nama == "Gudang") {
                //         $datas .= '<span class="orange-text badge">';
                //     } else if ($data->Pesanan->State->nama == "QC") {
                //         $datas .= '<span class="yellow-text badge">';
                //     } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                //         $datas .= '<span class="red-text badge">';
                //     } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                //         $datas .= '<span class="blue-text badge">';
                //     } else if ($data->Pesanan->State->nama == "Kirim") {
                //         $datas .= '<span class="green-text badge">';
                //     }
                //     $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                // } else {
                //     $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                // }
                // return $datas;
            })
            ->addColumn('status_ppic', function ($data) {
                $name = $data->getTable();
                $progress = "";
                $tes = $data->cjumlahprd + $data->cjumlahpart;
                if ($tes > 0) {
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                    if ($hitung > 0) {
                        $progress = $hitung;
                    } else {
                        $progress = $hitung;
                    }
                }

                if ($name == "ekatalog") {
                    if ($data->status == "batal" && ($data->Pesanan->log_id != "7")) {
                        return 'batal';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return strtolower($data->Pesanan->State->nama);
                        } else {
                            return $progress;
                        }
                    }
                } else if ($name == "spa") {
                    if ($data->log == "batal") {

                        return 'batal';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return strtolower($data->Pesanan->State->nama);
                        } else {
                            return $progress;
                        }
                    }
                } else if ($name == "spb") {
                    if ($data->log == "batal") {

                        return 'batal';
                    } else {
                        if ($data->Pesanan->log_id == "7") {
                            return strtolower($data->Pesanan->State->nama);
                        } else {
                            return $progress;
                        }
                    }
                }
            })
            ->addColumn('button', function ($data) {
                $name =  $data->getTable();
                if ($name == 'ekatalog') {
                    if ($data->status != 'draft') {
                        return  '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
                    }
                } else if ($name == 'spa') {
                    return  '<a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
                } else {
                    return  '<a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                          <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
                }
            })
            ->rawColumns(['button', 'status', 'tgl_order', 'tgl_kontrak', 'no_paket'])
            ->setRowClass(function ($data) {
                $name =  $data->getTable();
                if ($name == 'ekatalog') {
                    if ($data->status == 'batal') {
                        return 'text-danger font-weight-bold line-through';
                    }
                } else {
                    if ($data->log == 'batal') {
                        return 'text-danger font-weight-bold line-through';
                    }
                }
            })
            ->make(true);
    }
    public function get_lacak_penjualan($parameter, $value)
    {
        if ($parameter == 'no_po') {
            $val = str_replace("_",  "/",  $value);
            $si_ekat21 = DB::connection('si_21')->table('admjual_on')
                ->select(
                    'admjual_on.nopo_on as no_po',
                    'admjual_on.tglpo_on as tgl_po',
                    'distributor.pabrik as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'admjual_on.pabrikadm_on')
                ->where('admjual_on.nopo_on', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_on.nopo_on')
                ->get();

            $si_ekat20 = DB::connection('si_20')->table('admjual_on')
                ->select(
                    'admjual_on.nopo_on as no_po',
                    'admjual_on.tglpo_on as tgl_po',
                    'distributor.pabrik as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'admjual_on.pabrikadm_on')
                ->where('admjual_on.nopo_on', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_on.nopo_on')
                ->get();


            $si_spa21 = DB::connection('si_21')->table('admjual_off')
                ->select(
                    'admjual_off.nopo_off as no_po',
                    'admjual_off.tglpo_off as tgl_po',
                    'distributor.pabrik as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'admjual_off.pabrikadm_off')
                ->where('admjual_off.nopo_off', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_off.nopo_off')
                ->get();

            $si_spa20 = DB::connection('si_20')->table('admjual_off')
                ->select(
                    'admjual_off.nopo_off as no_po',
                    'admjual_off.tglpo_off as tgl_po',
                    'distributor.pabrik as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'admjual_off.pabrikadm_off')
                ->where('admjual_off.nopo_off', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_off.nopo_off')
                ->get();


            $si_spb21 = DB::connection('si_21')->table('admjual_spb')
                ->select(
                    'admjual_spb.nopo_spb as no_po',
                    'admjual_spb.tglpo_spb as tgl_po',
                    'spb.pelanggan_spb as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('spb', 'spb.nospb', '=', 'admjual_spb.noadm_spb')
                ->where('admjual_spb.nopo_spb', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_spb.nopo_spb')
                ->get();

            $si_spb20 = DB::connection('si_20')->table('admjual_spb')
                ->select(
                    'admjual_spb.nopo_spb as no_po',
                    'admjual_spb.tglpo_spb as tgl_po',
                    'spb.pelanggan_spb as customer',
                )
                ->addSelect(DB::raw("'-' as sj"))
                ->leftjoin('spb', 'spb.nospb', '=', 'admjual_spb.noadm_spb')
                ->where('admjual_spb.nopo_spb', 'LIKE', '%' . $val . '%')
                ->groupby('admjual_spb.nopo_spb')
                ->get();

            $spa = Pesanan::select(
                'pesanan.id',
                'pesanan.no_po',
                'pesanan.so',
                'pesanan.tgl_po',
                // 'm_state.nama as state_nama',
                // 'c_ekat.nama as c_ekat_nama',
                // 'c_spa.nama as c_spa_nama',
                // 'c_spb.nama as c_spb_nama',
                // 'ekatalog.satuan as satuan',
            )
                ->leftJoin('detail_pesanan',  'detail_pesanan.pesanan_id',  '=',  'pesanan.id')
                // ->leftJoin('ekatalog',  'ekatalog.pesanan_id',  '=',  'pesanan.id')
                // ->leftJoin('customer as c_ekat',  'c_ekat.id',  '=',  'ekatalog.customer_id')
                // ->leftJoin('spa',  'spa.pesanan_id',  '=',  'pesanan.id')
                // ->leftJoin('customer as c_spa',  'c_spa.id',  '=',  'spa.customer_id')
                // ->leftJoin('spb',  'spb.pesanan_id',  '=',  'pesanan.id')
                // ->leftJoin('customer as c_spb',  'c_spb.id',  '=',  'spb.customer_id')
                // ->leftJoin('m_state',  'm_state.id',  '=',  'pesanan.log_id')
                // ->leftJoin('m_state',  'm_state.id',  '=',  'pesanan.log_id')
                ->where('no_po', 'LIKE', '%' . $val . '%')
                ->groupby('pesanan.no_po')
                ->get();

            if (count($spa) > 0) {
                foreach ($spa as $s) {
                    $spa_data[] = array(
                        'so' => $s->so,
                        'no_po' => $s->no_po,
                        'tgl_po' => $s->tgl_po,
                        'sj' => $s->getSuratJalan()
                    );
                }
                $data = $si_ekat21->merge($si_ekat20)->merge($si_spa21)->merge($si_spa20)->merge($si_spb21)->merge($si_spb20)->merge($spa_data);
            } else {
                $data = $si_ekat21->merge($si_ekat20)->merge($si_spa21)->merge($si_spa20)->merge($si_spb21)->merge($si_spb20);
            }


            return response()->json(['data' => $data]);
            // $val = str_replace("-",  "/",  $value);
            // $data = Pesanan::select(

            //     'pesanan.no_po',
            //     'pesanan.so',
            //     'pesanan.tgl_po',
            //     'm_state.nama as state_nama',
            //     'c_ekat.nama as c_ekat_nama',
            //     'c_spa.nama as c_spa_nama',
            //     'c_spb.nama as c_spb_nama',
            //     'ekatalog.satuan as satuan',
            // )
            //     ->leftJoin('ekatalog',  'ekatalog.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_ekat',  'c_ekat.id',  '=',  'ekatalog.customer_id')
            //     ->leftJoin('spa',  'spa.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_spa',  'c_spa.id',  '=',  'spa.customer_id')
            //     ->leftJoin('spb',  'spb.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_spb',  'c_spb.id',  '=',  'spb.customer_id')
            //     ->leftJoin('m_state',  'm_state.id',  '=',  'pesanan.log_id')
            //     ->where('no_po', 'LIKE', '%' . $val . '%')
            //     ->get();
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('nama_customer', function ($data) {
            //         $name = explode('/', $data->so);
            //         if ($name[1] == 'EKAT') {
            //             $datas = $data->c_ekat_nama;
            //             if ($data->satuan) {
            //                 $datas .= "<div><small>" . $data->satuan . "</small></div>";
            //             }
            //         } else if ($name[1] == 'SPA') {
            //             $datas = $data->c_spa_nama;
            //         } else if ($name[1] == 'SPB') {
            //             $datas = $data->c_spb_nama;
            //         }

            //         return $datas;
            //     })
            //     ->addColumn('so', function ($data) {
            //         if ($data->so) {
            //             return $data->so;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('no_po', function ($data) {
            //         if ($data->no_po) {
            //             return $data->no_po;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('tgl_po', function ($data) {
            //         if ($data->tgl_po) {
            //             if ($data->tgl_po != "0000-00-00" && !empty($data->tgl_po)) {
            //                 return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
            //             } else {
            //                 return '-';
            //             }
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('noseri', function () {
            //         return '';
            //     })
            //     ->addColumn('log', function ($data) {
            //         $progress = '';
            //         $tes = $data->cjumlahprd + $data->cjumlahpart;
            //         // if($tes > 0){
            //         //     $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
            //         //     if($hitung > 0){
            //         //         $progress = '<div class="progress">
            //         //             <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
            //         //         </div>
            //         //         <small class="text-muted">Selesai</small>';
            //         //     }else{
            //         //         $progress = '<div class="progress">
            //         //             <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
            //         //         </div>
            //         //         <small class="text-muted">Selesai</small>';
            //         //     }
            //         // }
            //         $datas = "";
            //         // if (!empty($data->state_nama)) {
            //         //     if ($data->state_nama == "Penjualan") {
            //         //         $datas .= '<span class="red-text badge">'. ucfirst($data->state_nama) . '</span>';
            //         //     } else {
            //         //         if($data->ekat_log == "batal"){
            //         //             $datas .= '<span class="red-text badge">'. ucfirst($data->ekat_log) . '</span>';
            //         //         }
            //         //         else if($data->spa_log == "batal"){
            //         //             $datas .= '<span class="red-text badge">'. ucfirst($data->spa_log) . '</span>';
            //         //         }
            //         //         else if($data->spb_log == "batal"){
            //         //             $datas .= '<span class="red-text badge">'. ucfirst($data->spb_log) . '</span>';
            //         //         }
            //         //         else{
            //         //             $datas .= $progress;
            //         //         }
            //         //     }
            //         // }
            //         if (!empty($data->state_nama)) {
            //             if ($data->state_nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->state_nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->state_nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->state_nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->state_nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->state_nama) . '</span>';
            //         }
            //         return $datas;
            //     })
            //     ->rawColumns(['log',  'nama_customer'])
            //     ->make(true);
        } else if ($parameter == 'no_akn') {
            $si_ekat21 = DB::connection('si_21')->table('spa_on')
                ->select(
                    'spa_on.noaks_on as no_paket',
                    'spa_on.status_on as status',
                    'spa_on.tgl_buat_on as tgl_buat',
                    'spa_on.tgl_edit_on as tgl_edit',
                    'spa_on.instansi_on as instansi',
                    'distributor.pabrik as customer',
                )
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                ->where('spa_on.noaks_on', 'LIKE', '%' . $value . '%')
                ->groupby('spa_on.noaks_on')
                ->get();

            $si_ekat20 = DB::connection('si_20')->table('spa_on')
                ->select(
                    'spa_on.noaks_on as no_paket',
                    'spa_on.status_on as status',
                    'spa_on.tgl_buat_on as tgl_buat',
                    'spa_on.tgl_edit_on as tgl_edit',
                    'spa_on.instansi_on as instansi',
                    'distributor.pabrik as customer',
                )
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                ->where('spa_on.noaks_on', 'LIKE', '%' . $value . '%')
                ->groupby('spa_on.noaks_on')
                ->get();

            $spa = Ekatalog::with(['Pesanan.State', 'Customer'])->addSelect(['tgl_kontrak_custom' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog as e')
                    ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                    ->whereColumn('e.id', 'ekatalog.id')
                    ->limit(1);
            }])->where('no_paket', 'LIKE', '%' . $value . '%')
                ->get();
            $data = $si_ekat20->merge($si_ekat21)->merge($spa);

            return response()->json(['data' => $data]);

            // $val = str_replace("-",  "/",  $value);
            // $data = Ekatalog::with(['Pesanan.State', 'Customer'])->addSelect(['tgl_kontrak_custom' => function ($q) {
            //     $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
            //         ->from('ekatalog as e')
            //         ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
            //         ->whereColumn('e.id', 'ekatalog.id')
            //         ->limit(1);
            // }])->where('no_paket', 'LIKE', '%' . $val . '%')
            //     ->get();
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('status', function ($data) {
            //         $status = "";
            //         if ($data->status == "draft") {
            //             $status = '<span class="badge blue-text">Draft</span>';
            //         } else if ($data->status == "sepakat") {
            //             $status = '<span class="green-text badge">Sepakat</span>';
            //         } else if ($data->status == "negosiasi") {
            //             $status =  '<span class="yellow-text badge">Negosiasi</span>';
            //         } else {
            //             $status =  '<span class="red-text badge">Batal</span>';
            //         }
            //         return $status;
            //     })
            //     ->addColumn('so', function ($data) {
            //         if ($data->Pesanan->so) {
            //             return $data->Pesanan->so;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_paket', function ($data) {
            //         if ($data->no_paket) {
            //             return $data->no_paket;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_buat', function ($data) {
            //         if (!empty($data->tgl_buat)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
            //         }
            //     })
            //     ->addColumn('tgl_kontrak', function ($data) {
            //         if ($data->tgl_kontrak_custom != "") {
            //             if ($data->Pesanan->log_id) {
            //                 $tgl_sekarang = Carbon::now();
            //                 $tgl_parameter = $data->tgl_kontrak_custom;
            //                 $hari = $tgl_sekarang->diffInDays($tgl_parameter);
            //                 if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
            //                     if ($hari > 7) {
            //                         return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
            //                         <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
            //                     } else if ($hari > 0 && $hari <= 7) {
            //                         return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
            //                         <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
            //                     } else {
            //                         return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
            //                         <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
            //                     }
            //                 } else {
            //                     return  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
            //                     <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih dari ' . $hari . ' Hari</small></div>';
            //                 }
            //             } else {
            //                 return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
            //             }
            //         }
            //     })
            //     ->addColumn('customer', function ($data) {
            //         return $data->Customer->nama;
            //     })
            //     ->addColumn('instansi', function ($data) {
            //         $datas = $data->satuan;
            //         $datas .= "<div><small>" . $data->instansi . "</small></div>";
            //         return $datas;
            //     })
            //     ->addColumn('log', function ($data) {
            //         $progress = '';
            //         // $tes = $data->cjumlahprd + $data->cjumlahpart;
            //         // if($tes > 0){
            //         //     $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
            //         //     if($hitung > 0){
            //         //         $progress = '<div class="progress">
            //         //             <div class="progress-bar bg-success" role="progressbar" aria-valuenow="'.$hitung.'"  style="width: '.$hitung.'%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
            //         //         </div>
            //         //         <small class="text-muted">Selesai</small>';
            //         //     }else{
            //         //         $progress = '<div class="progress">
            //         //             <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">'.$hitung.'%</div>
            //         //         </div>
            //         //         <small class="text-muted">Selesai</small>';
            //         //     }
            //         // }
            //         $datas = "";
            //         // if (!empty($data->Pesanan->State->nama)) {
            //         //     if ($data->Pesanan->State->nama == "Penjualan") {
            //         //         $datas .= '<span class="red-text badge">'. ucfirst($data->state_nama) . '</span>';
            //         //     } else {
            //         //         if($data->status == "batal"){
            //         //             $datas .= '<span class="red-text badge">Batal</span>';
            //         //         }
            //         //         else{
            //         //             $datas .= $progress;
            //         //         }
            //         //     }
            //         // }

            //         if (!empty($data->Pesanan->State->nama)) {
            //             if ($data->Pesanan->State->nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->Pesanan->State->nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->Pesanan->State->nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->Pesanan->State->nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->Pesanan->State->nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
            //         }

            //         return $datas;
            //     })
            //     ->rawColumns(['status', 'log', 'instansi',  'tgl_kontrak'])
            //     ->make(true);
        } else if ($parameter == 'customer') {
            $val = str_replace("_",  "/",  $value);
            // $ekatalog = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog', function ($q) use ($value) {
            //     $q->where('satuan', 'LIKE', '%' . $value . '%');
            // })->orwhereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog', function ($q) use ($value) {
            //     $q->where('instansi', 'LIKE', '%' . $value . '%');
            // })->orwhereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Ekatalog.Customer', function ($q) use ($value) {
            //     $q->where('nama', 'LIKE', '%' . $value . '%');
            // })->get();
            // $spa = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Spa.Customer', function ($q) use ($value) {
            //     $q->where('nama', 'LIKE', '%' . $value . '%');
            // })->get();
            // $spb = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.DetailPesanan.Pesanan.Spb.Customer', function ($q) use ($value) {
            //     $q->where('nama', 'LIKE', '%' . $value . '%');
            // })->get();
            // $data = $ekatalog->merge($spa)->merge($spb);
            $data =  NoseriBarangJadi::select(
                'noseri_barang_jadi.noseri',
                'pesanan.no_po',
                'pesanan.so',
                'noseri_detail_pesanan.tgl_uji',
                'logistik.tgl_kirim as tgl_sj',
                'logistik.nosurat as no_sj',
                'produk.nama as p_nama',
                'c_ekat.nama as c_ekat_nama',
                'c_spa.nama as c_spa_nama',
                'c_spb.nama as c_spb_nama',
                'ekatalog.satuan as satuan',
                'm_state.nama as state_nama',
            )
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
                ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->where('c_spa.nama', 'LIKE', '%' . $val . '%')
                ->orwhere('c_spb.nama', 'LIKE', '%' . $val . '%')
                ->orwhere('c_ekat.nama', 'LIKE', '%' . $val . '%')
                ->orwhere('ekatalog.instansi', 'LIKE', '%' . $val . '%')
                ->orwhere('ekatalog.satuan', 'LIKE', '%' . $val . '%')
                ->orderBy('noseri_barang_jadi.noseri', 'ASC')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noseri', function ($data) {
                    return $data->noseri;
                })
                ->addColumn('nama_produk', function ($data) {
                    if ($data->p_nama) {
                        return $data->p_nama;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('no_so', function ($data) {
                    if ($data->no_po) {
                        return $data->no_po;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    if ($data->so) {
                        $name = explode('/', $data->so);
                        if ($name[1] == 'EKAT') {
                            $datas = $data->c_ekat_nama;
                            if ($data->satuan) {
                                $datas .= "<div><small>" . $data->satuan . "</small></div>";
                            }
                        } else if ($name[1] == 'SPA') {
                            $datas = $data->c_spa_nama;
                        } else if ($name[1] == 'SPB') {
                            $datas = $data->c_spb_nama;
                        }
                        return $datas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_uji', function ($data) {
                    if (isset($data->tgl_uji)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('no_sj', function ($data) {
                    if (isset($data->no_sj)) {
                        return $data->no_sj;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if (isset($data->tgl_sj)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_sj)->format('d-m-Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if (!empty($data->state_nama)) {
                        if ($data->state_nama == "Penjualan") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->state_nama == "PO") {
                            $datas .= '<span class="purple-text badge">';
                        } else if ($data->state_nama == "Gudang") {
                            $datas .= '<span class="orange-text badge">';
                        } else if ($data->state_nama == "QC") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->state_nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->state_nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->state_nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->state_nama) . '</span>';
                    }
                    return $datas;
                })
                ->rawColumns(['divisi_id', 'status', 'nama_customer'])
                ->make(true);
        } else if ($parameter == 'produk') {

            // $data = NoseriTGbj::whereHas('NoseriDetailPesanan.DetailPesananProduk.GudangBarangJadi.produk', function ($q) use ($value) {
            //     $q->where('nama', 'LIKE', '%' . $value . '%');
            // })->get();
            $data =  NoseriBarangJadi::select(
                'noseri_barang_jadi.noseri',
                'pesanan.no_po',
                'pesanan.so',
                'noseri_detail_pesanan.tgl_uji',
                'logistik.tgl_kirim as tgl_sj',
                'logistik.nosurat as no_sj',
                'produk.nama as p_nama',
                'c_ekat.nama as c_ekat_nama',
                'c_spa.nama as c_spa_nama',
                'c_spb.nama as c_spb_nama',
                'ekatalog.satuan as satuan',
                'm_state.nama as state_nama',
            )
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'detail_logistik.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
                ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->where('produk.nama', 'LIKE', '%' . $value . '%')
                ->orderBy('noseri_barang_jadi.noseri', 'ASC')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noseri', function ($data) {
                    return $data->noseri;
                })
                ->addColumn('nama_produk', function ($data) {
                    return $data->p_nama;
                })
                ->addColumn('no_so', function ($data) {
                    if ($data->no_po) {
                        return $data->no_po;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    if ($data->so) {
                        $name = explode('/', $data->so);
                        if ($name[1] == 'EKAT') {
                            $datas = $data->c_ekat_nama;
                            if ($data->satuan) {
                                $datas .= "<div><small>" . $data->satuan . "</small></div>";
                            }
                        } else if ($name[1] == 'SPA') {
                            $datas = $data->c_spa_nama;
                        } else if ($name[1] == 'SPB') {
                            $datas = $data->c_spb_nama;
                        }
                        return $datas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_uji', function ($data) {
                    if (isset($data->tgl_uji)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('no_sj', function ($data) {
                    if (isset($data->no_sj)) {
                        return $data->no_sj;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('tgl_kirim', function ($data) {
                    if (isset($data->tgl_sj)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_sj)->format('d-m-Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if (!empty($data->state_nama)) {
                        if ($data->state_nama == "Penjualan") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->state_nama == "PO") {
                            $datas .= '<span class="purple-text badge">';
                        } else if ($data->state_nama == "Gudang") {
                            $datas .= '<span class="orange-text badge">';
                        } else if ($data->state_nama == "QC") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->state_nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->state_nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->state_nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->state_nama) . '</span>';
                    }
                    return $datas;
                })
                ->rawColumns(['divisi_id', 'status', 'nama_customer'])
                ->make(true);
        } else if ($parameter == 'no_seri') {
            //Baru
            $si_ekat21 = DB::connection('si_21')->table('seri_on')
                ->select(
                    'seri_on.noseri_on as noseri',
                    'admjual_on.nopo_on as no_po',
                    'qc_on.tglterima_on as tglterima_on',
                    'qc_on.tglserah_on as tglserah_on',
                    'gudang_on.tglsj_on as tgl_sj',
                    'gudang_on.nosj_on as no_sj',
                    'spa_on.satuan_on as satuan',
                    'distributor.pabrik as c_ekat_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_on', 'gudang_on.nolkppgdg_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('qc_on', 'qc_on.nolkppqc_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('spa_on', 'spa_on.nolkpp_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_on.idprod_on')
                ->where('seri_on.noseri_on', 'LIKE', '%' . $value . '%')
                ->whereNotNull('gudang_on.tglsj_on')
                ->groupby('seri_on.noseri_on')
                ->get();

            $si_ekat20 = DB::connection('si_20')->table('seri_on')
                ->select(
                    'seri_on.noseri_on as noseri',
                    'admjual_on.nopo_on as no_po',
                    'qc_on.tglterima_on as tglterima_on',
                    'qc_on.tglserah_on as tglserah_on',
                    'gudang_on.tglsj_on as tgl_sj',
                    'gudang_on.nosj_on as no_sj',
                    'spa_on.satuan_on as satuan',
                    'distributor.pabrik as c_ekat_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_on', 'gudang_on.nolkppgdg_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('qc_on', 'qc_on.nolkppqc_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('spa_on', 'spa_on.nolkpp_on', '=', 'seri_on.lkppfk_on')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_on.idprod_on')
                ->where('seri_on.noseri_on', 'LIKE', '%' . $value . '%')
                ->whereNotNull('gudang_on.tglsj_on')
                ->groupby('seri_on.noseri_on')
                ->get();


            $si_spa21 = DB::connection('si_21')->table('seri_off')
                ->select(
                    'seri_off.noseri_off as noseri',
                    'admjual_off.nopo_off as no_po',
                    'qc_off.tglterima_off as tglterima_off',
                    'qc_off.tglserah_off as tglserah_off',
                    'gudang_off.tglsj_off as tgl_sj',
                    'gudang_off.nosj_off as no_sj',
                    'distributor.pabrik as c_spa_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_off', 'gudang_off.idordergdg_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('admjual_off', 'admjual_off.idorderadm_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('qc_off', 'qc_off.idorderqc_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('spa_off', 'spa_off.idorder_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_off.pabrik_off')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_off.idprod_off')
                ->where('seri_off.noseri_off', 'LIKE', '%' . $value . '%')
                ->whereNotNull('gudang_off.tglsj_off')
                ->groupby('seri_off.noseri_off')
                ->get();

            $si_spa20 = DB::connection('si_20')->table('seri_off')
                ->select(
                    'seri_off.noseri_off as noseri',
                    'admjual_off.nopo_off as no_po',
                    'qc_off.tglterima_off as tglterima_off',
                    'qc_off.tglserah_off as tglserah_off',
                    'gudang_off.tglsj_off as tgl_sj',
                    'gudang_off.nosj_off as no_sj',
                    'distributor.pabrik as c_spa_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_off', 'gudang_off.idordergdg_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('admjual_off', 'admjual_off.idorderadm_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('qc_off', 'qc_off.idorderqc_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('spa_off', 'spa_off.idorder_off', '=', 'seri_off.idorderfk_off')
                ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_off.pabrik_off')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_off.idprod_off')
                ->where('seri_off.noseri_off', 'LIKE', '%' . $value . '%')
                ->whereNotNull('gudang_off.tglsj_off')
                ->groupby('seri_off.noseri_off')
                ->get();

            $si_spb21 = DB::connection('si_21')->table('seri_spb')
                ->select(
                    'seri_spb.noseri_spb as noseri',
                    'admjual_spb.nopo_spb as no_po',
                    'qc_spb.tglterimaqc_spb as tglterima_spb',
                    'qc_spb.tglserahqc_spb as tglserah_spb',
                    'gudang_spb.tglsjgdg_spb as tgl_sj',
                    'gudang_spb.nosjgdg_spb as no_sj',
                    'spb.pelanggan_spb as c_spb_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_spb', 'gudang_spb.nogdg_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('admjual_spb', 'admjual_spb.noadm_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('qc_spb', 'qc_spb.noqc_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('spb', 'spb.nospb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spb.idprod_spb')
                ->where('seri_spb.noseri_spb', 'LIKE', '%' . $value . '%')
                ->whereNotNull('gudang_spb.tglsjgdg_spb')
                ->groupby('seri_spb.noseri_spb')
                ->get();

            $si_spb20 = DB::connection('si_20')->table('seri_spb')
                ->select(
                    'seri_spb.noseri_spb as noseri',
                    'admjual_spb.nopo_spb as no_po',
                    'qc_spb.tglterimaqc_spb as tglterima_spb',
                    'qc_spb.tglserahqc_spb as tglserah_spb',
                    'gudang_spb.tglsjgdg_spb as tgl_sj',
                    'gudang_spb.nosjgdg_spb as no_sj',
                    'spb.pelanggan_spb as c_spb_nama',
                    'produk_master.nam_prod as p_nama'
                )
                ->leftjoin('gudang_spb', 'gudang_spb.nogdg_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('admjual_spb', 'admjual_spb.noadm_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('qc_spb', 'qc_spb.noqc_spb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('spb', 'spb.nospb', '=', 'seri_spb.nogdgfk')
                ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spb.idprod_spb')
                ->where('seri_spb.noseri_spb', 'LIKE', '%' . $value . '%')
                ->whereNotNull('gudang_spb.tglsjgdg_spb')
                ->groupby('seri_spb.noseri_spb')
                ->get();

            $spa =  NoseriBarangJadi::select(
                'noseri_barang_jadi.noseri',
                'pesanan.no_po',
                'pesanan.so',
                'noseri_detail_pesanan.tgl_uji',
                'logistik.tgl_kirim as tgl_sj',
                'logistik.nosurat as no_sj',
                'produk.nama as p_nama',
                'c_ekat.nama as c_ekat_nama',
                'c_spa.nama as c_spa_nama',
                'c_spb.nama as c_spb_nama',
                'ekatalog.satuan as satuan',
                'm_state.nama as state_nama',
            )

                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
                ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
                ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
                ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
                ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftJoin('pesanan', 'pesanan.id', '=', 't_gbj.pesanan_id')
                ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
                ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->where('noseri_barang_jadi.noseri', 'LIKE', '%' . $value . '%')
                ->whereNotNull('t_gbj.pesanan_id')
                ->orderBy('noseri_barang_jadi.noseri', 'ASC')
                ->get();

            $noseriretur = NoseriBarangJadi::selectRaw(
                'noseri_barang_jadi.noseri,
                    retur_penjualan.no_retur as no_po,
                    pengiriman.tanggal as tgl_sj,
                    retur_penjualan.tgl_retur as tgl_uji,
                    pengiriman.no_pengiriman as no_sj,
                    produk.nama as p_nama,
                    customer.nama as c_ekat_nama,
                    m_state.nama as state_nama'
            )
                ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
                ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
                ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('retur_penjualan', 'retur_penjualan.id', '=', 't_gbj.retur_penjualan_id')
                ->leftjoin('pengiriman_noseri', 'pengiriman_noseri.noseri_barang_jadi_id', '=', 'noseri_barang_jadi.id')
                ->leftjoin('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
                ->leftjoin('pengiriman as pn', 'pn.retur_penjualan_id', '=', 'retur_penjualan.id')
                ->leftJoin('m_state', 'm_state.id', '=', 'retur_penjualan.state_id')
                ->leftJoin('customer', 'customer.id', '=', 'retur_penjualan.customer_id')
                ->where('noseri_barang_jadi.noseri', 'LIKE', '%' . $value . '%')
                ->whereNotNull('t_gbj.retur_penjualan_id')
                ->orderBy('noseri_barang_jadi.noseri', 'ASC')
                ->groupBy('retur_penjualan.id')
                ->get();


            $data = $si_spa21->merge($si_spa20)->merge($si_spb20)->merge($si_spb21)->merge($si_ekat20)->merge($si_ekat21)->merge($spa)->merge($noseriretur);


            return response()->json(['data' => $data]);
            // $data =  NoseriBarangJadi::select(
            //     'noseri_barang_jadi.noseri',
            //     'pesanan.no_po',
            //     'pesanan.so',
            //     'noseri_detail_pesanan.tgl_uji',
            //     'logistik.tgl_kirim as tgl_sj',
            //     'logistik.nosurat as no_sj',
            //     'produk.nama as p_nama',
            //     'c_ekat.nama as c_ekat_nama',
            //     'c_spa.nama as c_spa_nama',
            //     'c_spb.nama as c_spb_nama',
            //     'ekatalog.satuan as satuan',
            //     'm_state.nama as state_nama',
            // )

            //     ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'noseri_barang_jadi.gdg_barang_jadi_id')
            //     ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
            //     ->leftjoin('t_gbj_noseri', 't_gbj_noseri.noseri_id', '=', 'noseri_barang_jadi.id')
            //     ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.t_tfbj_noseri_id', '=', 't_gbj_noseri.id')
            //     ->leftJoin('noseri_logistik', 'noseri_logistik.noseri_detail_pesanan_id', '=', 'noseri_detail_pesanan.id')
            //     ->leftJoin('detail_logistik', 'detail_logistik.id', '=', 'noseri_logistik.detail_logistik_id')
            //     ->leftJoin('logistik', 'logistik.id', '=', 'detail_logistik.logistik_id')
            //     ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
            //     ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
            //     ->leftJoin('pesanan', 'pesanan.id', '=', 't_gbj.pesanan_id')
            //     ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
            //     ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
            //     ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
            //     ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
            //     ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
            //     ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
            //     ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
            //     ->where('noseri_barang_jadi.noseri', 'LIKE', '%' . $value . '%')
            //     ->whereNotNull('t_gbj.pesanan_id')

            //     ->orderBy('noseri_barang_jadi.noseri', 'ASC')
            //     ->get();

            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('noseri', function ($data) {
            //         return $data->noseri;
            //     })
            //     ->addColumn('nama_produk', function ($data) {
            //         if ($data->p_nama) {
            //             return $data->p_nama;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_so', function ($data) {
            //         if ($data->no_po) {
            //             return $data->no_po;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('nama_customer', function ($data) {

            //         if ($data->so) {
            //             $name = explode('/', $data->so);
            //             if ($name[1] == 'EKAT') {
            //                 $datas = $data->c_ekat_nama;
            //                 if ($data->satuan) {
            //                     $datas .= "<div><small>" . $data->satuan . "</small></div>";
            //                 }
            //             } else if ($name[1] == 'SPA') {
            //                 $datas = $data->c_spa_nama;
            //             } else if ($name[1] == 'SPB') {
            //                 $datas = $data->c_spb_nama;
            //             }
            //             return $datas;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_uji', function ($data) {

            //         if (isset($data->tgl_uji)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_uji)->format('d-m-Y');
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('no_sj', function ($data) {
            //         if (isset($data->no_sj)) {
            //             return $data->no_sj;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_kirim', function ($data) {
            //         if (isset($data->tgl_sj)) {
            //             return Carbon::createFromFormat('Y-m-d', $data->tgl_sj)->format('d-m-Y');
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('status', function ($data) {

            //         $datas = "";
            //         if (!empty($data->state_nama)) {
            //             if ($data->state_nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->state_nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->state_nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->state_nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->state_nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->state_nama) . '</span>';
            //         }
            //         return $datas;
            //     })
            //     ->rawColumns(['status',  'nama_customer'])
            //     ->make(true);
        } else if ($parameter == 'no_so') {
            $val = str_replace("_",  "/",  $value);
            $data = Pesanan::select(
                'pesanan.no_po',
                'pesanan.so',
                'pesanan.tgl_po',
                'm_state.nama as state_nama',
                'c_ekat.nama as c_ekat_nama',
                'c_spa.nama as c_spa_nama',
                'c_spb.nama as c_spb_nama',
                'ekatalog.satuan as satuan',
            )
                ->leftJoin('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'ekatalog.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->leftJoin('m_state', 'm_state.id', '=', 'pesanan.log_id')
                ->where('so', 'LIKE', '%' . $val . '%')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_customer', function ($data) {
                    $name = explode('/', $data->so);
                    if ($name[1] == 'EKAT') {
                        $datas = $data->c_ekat_nama;
                        if ($data->satuan) {
                            $datas .= "<div><small>" . $data->satuan . "</small></div>";
                        }
                    } else if ($name[1] == 'SPA') {
                        $datas = $data->c_spa_nama;
                    } else if ($name[1] == 'SPB') {
                        $datas = $data->c_spb_nama;
                    }

                    return $datas;
                })
                ->addColumn('so', function ($data) {
                    if ($data->so) {
                        return $data->so;
                    } else {
                        return '';
                    }
                })
                ->addColumn('no_po', function ($data) {
                    if ($data->no_po) {
                        return $data->no_po;
                    } else {
                        return '';
                    }
                })
                ->addColumn('tgl_po', function ($data) {
                    if ($data->tgl_po) {
                        if ($data->tgl_po != "0000-00-00" && !empty($data->tgl_po)) {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y');
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('log', function ($data) {
                    $progress = '';
                    $tes = $data->cjumlahprd + $data->cjumlahpart;
                    $datas = "";
                    if (!empty($data->state_nama)) {
                        if ($data->state_nama == "Penjualan") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->state_nama == "PO") {
                            $datas .= '<span class="purple-text badge">';
                        } else if ($data->state_nama == "Gudang") {
                            $datas .= '<span class="orange-text badge">';
                        } else if ($data->state_nama == "QC") {
                            $datas .= '<span class="yellow-text badge">';
                        } else if ($data->state_nama == "Belum Terkirim") {
                            $datas .= '<span class="red-text badge">';
                        } else if ($data->state_nama == "Terkirim Sebagian") {
                            $datas .= '<span class="blue-text badge">';
                        } else if ($data->state_nama == "Kirim") {
                            $datas .= '<span class="green-text badge">';
                        }
                        $datas .= ucfirst($data->state_nama) . '</span>';
                    }
                    return $datas;
                })
                ->rawColumns(['log', 'nama_customer'])
                ->make(true);
        } else if ($parameter == 'no_sj') {
            $data = DB::connection('si_21')->table('gudang_on')
                ->select(
                    // 'seri_on.noseri_on as noseri',
                    // 'admjual_on.nopo_on as no_po',
                    // 'qc_on.tglterima_on as tglterima_on',
                    // 'qc_on.tglserah_on as tglserah_on',
                    'gudang_on.tglsj_on as tgl_sj',
                    'gudang_on.nosj_on as no_sj',
                    // 'spa_on.satuan_on as satuan',
                    // 'distributor.pabrik as c_ekat_nama',
                    // 'produk_master.nam_prod as p_nama'
                )
                // ->leftjoin('gudang_on', 'gudang_on.nolkppgdg_on', '=', 'seri_on.lkppfk_on')
                // ->leftjoin('admjual_on', 'admjual_on.nolkppadm_on', '=', 'seri_on.lkppfk_on')
                // ->leftjoin('qc_on', 'qc_on.nolkppqc_on', '=', 'seri_on.lkppfk_on')
                // ->leftjoin('spa_on', 'spa_on.nolkpp_on', '=', 'seri_on.lkppfk_on')
                // ->leftjoin('distributor', 'distributor.iddsb', '=', 'spa_on.pabrik_on')
                // ->leftjoin('produk_master', 'produk_master.id_prod', '=', 'spa_on.idprod_on')
                ->where('gudang_on.nosj_on', 'LIKE', '%' . $value . '%')
                ->groupby('gudang_on.nosj_on')
                ->get();
            return response()->json(['data' => $data]);
            // $data = Logistik::select(
            //     'logistik.nosurat as nosj',
            //     'logistik.noresi',
            //     'logistik.tgl_kirim as tglsj',
            //     'ekatalog.satuan',
            //     'pesanan.no_po as po',
            //     'pesanan.so as so',
            //     'm_state.nama as state_nama',
            // )
            //     ->leftJoin('detail_logistik_part',  'detail_logistik_part.logistik_id',  '=',  'logistik.id')
            //     ->leftJoin('detail_logistik',  'detail_logistik.logistik_id',  '=',  'logistik.id')
            //     ->leftJoin('detail_pesanan_produk',  'detail_pesanan_produk.id',  '=',  'detail_logistik.detail_pesanan_produk_id')
            //     ->leftJoin('detail_pesanan',  'detail_pesanan.id',  '=',  'detail_pesanan_produk.detail_pesanan_id')
            //     ->leftJoin('pesanan',  'pesanan.id',  '=',  'detail_pesanan.pesanan_id')
            //     ->leftJoin('detail_pesanan_part',  'detail_pesanan_part.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('ekatalog',  'ekatalog.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_ekat',  'c_ekat.id',  '=',  'ekatalog.customer_id')
            //     ->leftJoin('spa',  'spa.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_spa',  'c_spa.id',  '=',  'spa.customer_id')
            //     ->leftJoin('spb',  'spb.pesanan_id',  '=',  'pesanan.id')
            //     ->leftJoin('customer as c_spb',  'c_spb.id',  '=',  'spb.customer_id')
            //     ->leftJoin('m_state',  'm_state.id',  '=',  'pesanan.log_id')
            //     ->where('logistik.nosurat',  'LIKE', '%' . $value . '%')->get();
            // return datatables()->of($data)
            //     ->addIndexColumn()
            //     ->addColumn('po', function ($data) {
            //         if ($data->po) {
            //             return $data->po;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('resi', function ($data) {
            //         if ($data->noresi) {
            //             return $data->noresi;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('nosurat', function ($data) {
            //         if ($data->nosj) {
            //             return $data->nosj;
            //         } else {
            //             return '';
            //         }
            //     })
            //     ->addColumn('customer', function ($data) {
            //         if ($data->so) {
            //             $name = explode('/', $data->so);
            //             if ($name[1] == 'EKAT') {
            //                 $datas = $data->c_ekat_nama;
            //                 if ($data->satuan) {
            //                     $datas .= "<div><small>" . $data->satuan . "</small></div>";
            //                 }
            //             } else if ($name[1] == 'SPA') {
            //                 $datas = $data->c_spa_nama;
            //             } else if ($name[1] == 'SPB') {
            //                 $datas = $data->c_spb_nama;
            //             }

            //             return $datas;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('tgl_kirim', function ($data) {
            //         if ($data->tglsj) {
            //             return $data->tglsj;
            //         } else {
            //             return '-';
            //         }
            //     })
            //     ->addColumn('status', function ($data) {
            //         $datas = "";
            //         if (!empty($data->state_nama)) {
            //             if ($data->state_nama == "Penjualan") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "PO") {
            //                 $datas .= '<span class="purple-text badge">';
            //             } else if ($data->state_nama == "Gudang") {
            //                 $datas .= '<span class="orange-text badge">';
            //             } else if ($data->state_nama == "QC") {
            //                 $datas .= '<span class="yellow-text badge">';
            //             } else if ($data->state_nama == "Belum Terkirim") {
            //                 $datas .= '<span class="red-text badge">';
            //             } else if ($data->state_nama == "Terkirim Sebagian") {
            //                 $datas .= '<span class="blue-text badge">';
            //             } else if ($data->state_nama == "Kirim") {
            //                 $datas .= '<span class="green-text badge">';
            //             }
            //             $datas .= ucfirst($data->state_nama) . '</span>';
            //         }
            //         return $datas;
            //     })
            //     ->rawColumns(['status', 'no_so',  'customer'])
            //     ->make(true);
        } else if ($parameter == 'no_seri_gbj') {
            $data = NoseriBarangJadi::where([
                ['noseri', 'LIKE', '%' . $value . '%'],
                // ['is_aktif', '=', '1'],
                // ['is_ready', '=', '0'],
                // ['is_change', '=', '1'],
                // ['is_delete', '=', '0']
            ])->get();
            $arr = array();
            foreach ($data as $key => $i) {

                if ($i->is_ready != 0) {
                    if ($i->NoseriTGbj->last()->detail->header->pesanan_id != NULL) {
                        $detail = $i->NoseriTGbj->last()->detail->header->pesanan->no_po;
                    } else {
                        $detail = $i->NoseriTGbj->last()->detail->header->deskripsi;
                    }
                } else {
                    $detail = 'Tersedia';
                }

                $arr[$key] = array(
                    'noseri' => $i->noseri,
                    'nama_produk' => $i->gudang->produk->nama,
                    'variasi' => $i->gudang->nama != NULL ? $i->gudang->nama : '-',
                    'state_nama' =>  $i->is_ready != 0 ? 'Terpakai' : 'Tersedia',
                    'keterangan' => $i->is_ready != 0 ? $detail : '-'

                );
            }
            return response()->json(['data' => $arr]);
        }
    }
    public function get_data_detail_spa($value)
    {
        $data = Spa::with(['Pesanan.State',  'Customer.Provinsi'])->addSelect([
            'ckirimprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
            },
            'cjumlahprd' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
            },
            'ckirimpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
            },
            'cjumlahpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
            }

        ])->where('id', $value)->first();
        $cek_1 = DetailPesanan::where('pesanan_id', $data->pesanan_id)->get()->count();
        $cek_2 = DetailPesananPart::where('pesanan_id', $data->pesanan_id)->get()->count();

        if ($cek_1 <= 0) {
            $param = 'part';
        } else if ($cek_2 <= 0) {
            $param = 'produk';
        } else {
            $param = 'semua';
        }

        $status = "";
        $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
        if ($data->log == "batal") {
            $status = '<span class="badge red-text">Batal</span>';
        } else {
            if ($hitung > 0) {
                $status = '<div class="align-center"><div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small></div>';
            } else {
                $status = '<div class="align-center"><div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small></div>';
            }
        }

        return view('page.penjualan.penjualan.detail_spa', ['data' => $data, 'param' => $param, 'status' => $status]);
    }

    public function get_data_detail_ekatalog($value)
    {
        $data  = Ekatalog::with(['Pesanan.State',  'Customer', 'Provinsi'])->addSelect([

            'tgl_kontrak_custom' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                    ->from('ekatalog as e')
                    ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                    ->whereColumn('e.id', 'ekatalog.id')
                    ->limit(1);
            },
            'ckirimprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
            },
            'cjumlahprd' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
            },
            'ckirimpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
            },
            'cjumlahpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'ekatalog.pesanan_id');
            }

        ])->where('id', $value)->first();

        $status = "";


        if ($data->Pesanan->log_id == "7") {
            $status = '<div><span class="badge red-text">' . $data->Pesanan->State->nama . '</span></div>';
        } else if ($data->log == "batal") {
            $status = '<div><span class="badge red-text">Batal</span></div>';
        } else {
            $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
            if ($hitung > 0) {
                $status = '<div class="align-center"><div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted align-center">Selesai</small></div>';
            } else {
                $status = '<div class="align-center"><div class="progress">
                            <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                        </div>
                        <small class="text-muted align-center">Selesai</small></div>';
            }
        }
        $tgl_kontrak = "";
        if ($data->tgl_kontrak_custom != "") {
            if ($data->Pesanan->log_id != '10') {
                $tgl_sekarang = Carbon::now();
                $tgl_parameter = $data->tgl_kontrak_custom;
                $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                    if ($hari > 7) {
                        $tgl_kontrak =  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                    } else if ($hari > 0 && $hari <= 7) {
                        $tgl_kontrak =  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                    } else {
                        $tgl_kontrak =  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                    }
                } else {
                    $tgl_kontrak =  '<div class="text-danger"><b> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                            <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih dari ' . $hari . ' Hari</small></div>';
                }
            } else {
                $tgl_kontrak = Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
            }
        }
        return view('page.penjualan.penjualan.detail_ekatalog', ['data' => $data, 'status' => $status, 'tgl_kontrak' => $tgl_kontrak]);
    }

    public function get_data_detail_ekatalog_ppic($id)
    { {
            $data = array();
            $pesanan = Pesanan::find($id);
            if ($pesanan) {
                $data['so'] = $pesanan->so;
                $data['no_po'] = $pesanan->no_po;
                $data['tgl_po'] = $pesanan->tgl_po;
                if ($pesanan->Ekatalog) {
                    $data['no_paket'] = $pesanan->Ekatalog->no_paket;
                    $data['customer'] = $pesanan->Ekatalog->Customer->nama;
                    $data['instansi'] = $pesanan->Ekatalog->instansi;
                    $data['instansi_alamat'] = $pesanan->Ekatalog->alamat;
                    if ($pesanan->Ekatalog->provinsi != '') {
                        $data['instansi_provinsi'] = $pesanan->Ekatalog->Provinsi->nama;
                    }
                    $data['no_urut'] = $pesanan->Ekatalog->no_urut;
                    $data['tgl_kontrak'] = $pesanan->Ekatalog->tgl_kontrak;
                    $data['tgl_edit'] = $pesanan->Ekatalog->tgl_edit;
                    $data['tgl_buat'] = $pesanan->Ekatalog->tgl_buat;
                    $data['deskripsi'] = $pesanan->Ekatalog->deskripsi;
                }


                if ($pesanan->Spb) {
                    $data['customer'] = $pesanan->Spb->Customer->nama;
                    $data['customer_alamat'] = $pesanan->Spb->Customer->alamat;
                    $data['customer_provinsi'] = $pesanan->Spb->Customer->Provinsi->nama;
                    $data['deskripsi'] = $pesanan->Spb->ket;
                }

                if ($pesanan->Spa) {
                    $data['customer'] = $pesanan->Spa->Customer->nama;
                    $data['customer_alamat'] = $pesanan->Spa->Customer->alamat;
                    $data['customer_provinsi'] = $pesanan->Spa->Customer->Provinsi->nama;
                    $data['deskripsi'] = $pesanan->Spa->ket;
                }


                if ($pesanan->DetailPesananPart) {
                    foreach ($pesanan->DetailPesananPart  as $key_detail => $detailpart) {
                        if (strpos($detailpart->Sparepart->kode, 'JASA') === false) {
                            $jenis = 'part';
                        } else {
                            $jenis = 'jasa';
                        }
                        $data['detail_pesanan'][$key_detail] = array(
                            'id' => $detailpart->id,
                            'nama_paket' => $detailpart->Sparepart->nama,
                            'jumlah' => $detailpart->jumlah,
                            'harga' => $detailpart->harga,
                            'jenis' => $jenis,

                        );
                    }
                }



                if ($pesanan->DetailPesanan) {
                    foreach ($pesanan->DetailPesanan  as $key_detail => $detail) {
                        $data['detail_pesanan'][$key_detail] = array(
                            'id' => $detail->id,
                            'nama_paket' => $detail->PenjualanProduk->nama,
                            'jumlah' => $detail->jumlah,
                            'harga' => $detail->harga,
                            'ongkir' => $detail->ongkir,
                            'jenis' => 'paket',
                            'detail_produk' => array()
                        );

                        foreach ($detail->DetailPesananProduk  as $key_detailp => $detailp) {

                            if ($detailp->GudangBarangJadi->nama != '') {
                                $nama_produk = $detailp->GudangBarangJadi->nama;
                            } else {
                                $nama_produk = $detailp->GudangBarangJadi->Produk->nama;
                            }

                            $data['detail_pesanan'][$key_detail]['detail_produk'][$key_detailp] = array(
                                'id' => $detailp->id,
                                'nama_produk' => $nama_produk,
                                'jenis' => 'variasi',
                                'jumlah' => $detailp->getJumlahPesanan(),

                            );
                        }
                    }
                }

                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil',
                    'data'    =>  $data
                ], 200);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Data Tidak ditemukan',
                ], 200);
            }
        }
    }

    public function get_data_detail_spb($value)
    {
        $data  = Spb::with(['Pesanan.State',  'Customer.Provinsi'])->addSelect([
            'ckirimprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                    ->from('noseri_logistik')
                    ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
            },
            'cjumlahprd' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                    ->from('detail_pesanan')
                    ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                    ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
            },
            'ckirimpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
            },
            'cjumlahpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
            }

        ])->where('id', $value)->first();

        $status = "";
        $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
        if ($data->Pesanan->log_id == "7") {
            $status = '<span class="badge red-text">' . $data->Pesanan->State->nama . '</span>';
        } else if ($data->log == "batal") {
            $status = '<span class="badge red-text">Batal</span>';
        } else {
            if ($hitung > 0) {
                $status = '<div class="align-center"><div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small></div>';
            } else {
                $status = '<div class="align-center"><div class="progress">
                        <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                    </div>
                    <small class="text-muted">Selesai</small></div>';
            }
        }

        return view('page.penjualan.penjualan.detail_spb', ['data' => $data, 'status' => $status]);
    }

    public function get_data_detail_paket_spa($id)
    {
        $data  = DetailSpa::where('spa_id', $id)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('total', function ($data) {
                return $data->harga * $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<i class="fas fa-eye"></i>';
            })
            ->rawColumns(['button'])
            ->make(true);
    }
    public function get_data_detail_paket_spb($id)
    {
        $data  = DetailSpb::where('spb_id', $id)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('total', function ($data) {
                return $data->harga * $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<i class="fas fa-eye"></i>';
            })
            ->rawColumns(['button',])
            ->make(true);
    }
    public function get_data_detail_paket_ekatalog($id)
    {
        $data  = DetailEkatalog::with('gudangbarangjadi')->where('ekatalog_id', $id)
            ->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->penjualanproduk->nama;
            })
            ->addColumn('variasi', function ($data) {
                return implode(',', $data->gudangbarangjadi->pluck('nama')->toArray());

                //return implode(',', $data->detailekatalogproduk->gudangbarangjadi->nama);
            })
            ->addColumn('total', function ($data) {
                return $data->harga * $data->jumlah;
            })
            ->addColumn('button', function ($data) {
                return '<i class="fas fa-eye"></i>';
            })
            ->rawColumns(['button', 'variasi'])
            ->make(true);
    }
    public function get_data_paket_pesanan_ekat($id)
    {
        $produk = DetailPesananProduk::whereHas('DetailPesanan.Pesanan.Ekatalog', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();
        $produk_dsb = DetailPesananProdukDsb::whereHas('DetailPesananDsb.Pesanan.Ekatalog', function ($q) use ($id) {
            $q->where('id', $id);
        })->get();
        $data = $produk->merge($produk_dsb);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('paket_produk', function ($data) {
                if ($data->DetailPesanan) {
                    return $data->DetailPesanan->PenjualanProduk->nama . ' (' . $data->DetailPesanan->jumlah . ' unit)';
                } else {
                    return $data->DetailPesananDsb->PenjualanProduk->nama . ' (' . $data->DetailPesananDsb->jumlah . ' unit)' . ' <span class="badge info-text">Stok
                    distributor</span>';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                return $data->GudangBarangJadi->Produk->nama . ' ' . $data->GudangBarangJadi->nama;
            })
            ->addColumn('jumlah', function ($data) {
                return $data->getJumlahPesanan();
            })
            ->rawColumns(['paket_produk', 'nama_produk'])
            ->make(true);
    }

    public function get_data_pesanan_detail($id)
    {
        $dp = DetailPesanan::where('pesanan_id', $id)->get();
        $dpp = DetailPesananPart::where('pesanan_id', $id)->get();

        $data = $dp->merge($dpp);
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                if (isset($data->DetailPesananProduk)) {
                    return $data->PenjualanProduk->nama;
                } else {
                    return $data->Sparepart->nama;
                }
            })
            ->addColumn('jumlah', function ($data) {
                if ($data->jumlah) {
                    return $data->jumlah;
                }
            })
            ->addColumn('harga', function ($data) {
                return $data->harga;
            })
            ->addColumn('sub', function ($data) {
                return $data->harga * $data->jumlah;
            })
            ->make(true);
    }

    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '14';
        } else {
            $days = '21';
        }
        return Carbon::parse($value)->subDays($days);
    }
    public function get_data_ekatalog_pengiriman()
    {
        $data  = Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNotNull('no_po')->whereNotIn('log_id', ['7', '10', '20']);
        })->addSelect(['tgl_kontrak_custom' => function ($q) {
            $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                ->from('ekatalog as e')
                ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                ->whereColumn('e.id', 'ekatalog.id')
                ->limit(1);
        }, 'cjumlahprd' => function ($q) {
            $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
        }, 'clogprd' => function ($q) {
            $q->selectRaw('count(noseri_logistik.id)')
                ->from('noseri_logistik')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id')
                ->limit(1);
        }])->orderBy('tgl_kontrak_custom', 'ASC')->limit(20)->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->so;
                } else {
                    return '';
                }
            })
            ->addColumn('no_po', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_po', function ($data) {
                return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if (!empty($data->Pesanan->log_id)) {
                    $hitung = floor(((($data->clogprd + $data->clogpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));

                    if ($hitung > 0) {
                        $datas = '<div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                    } else {
                        $datas = '<div class="progress">
                                <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                    }
                }
                return $datas;
            })
            ->addColumn('batas_kontrak', function ($data) {
                if ($data->tgl_kontrak_custom != "") {
                    if ($data->Pesanan->log_id != "10") {
                        $tgl_sekarang = Carbon::now();
                        $tgl_parameter = $data->tgl_kontrak_custom;
                        $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                        if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                        <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                        <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        } else {
                            return  '<div class="text-danger"><b>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                        }
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('button', function ($data) {
                return  '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button"><i class="fas fa-eye"></i> Detail</button>
                    </a>';
            })
            ->rawColumns(['batas_kontrak', 'button', 'status'])
            ->make(true);
    }
    public function get_data_ekatalog($value, $tahun)
    {
        $divisi_id = Auth::user()->divisi_id;

        $x = explode(',', $value);
        $data = "";

        if ($value == 'semua') {
            $data  = Ekatalog::with(['Pesanan.State',  'Customer', 'Provinsi'])->addSelect([
                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog as e')
                        ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                        ->whereColumn('e.id', 'ekatalog.id')
                        ->limit(1);
                },
                'cseri' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlah' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                }

            ])->whereYear('created_at',  $tahun)->orderByRaw('CONVERT(no_urut, SIGNED) desc')->get();
        } else {
            $data  = Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([

                'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog as e')
                        ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                        ->whereColumn('e.id', 'ekatalog.id')
                        ->limit(1);
                },
                'cseri' => function ($q) {
                    $q->selectRaw('count(noseri_logistik.id)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                        ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cjumlah' => function ($q) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                }


            ])->whereYear('created_at', $tahun)->orderByRaw('CONVERT(no_urut, SIGNED) desc')->whereIN('status', $x)->get();
            // ])->orderBy('created_at', 'DESC')->orderByRaw('CONVERT(no_urut, SIGNED) desc')->whereIN('status', $x)->get();
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('no_urut', function ($data) {
                return $data->no_urut;
            })
            ->editColumn('no_paket', function ($data) {
                $datas = '';
                $datas .= '<div>' . $data->no_paket . '</div>';
                if (!empty($data->status)) {
                    if ($data->status == "batal") {
                        $datas .= '<small class="badge-danger badge">';
                    } else if ($data->status == "negosiasi") {
                        $datas .= '<small class="badge-warning badge">';
                    } else if ($data->status == "draft") {
                        $datas .= '<small class="badge-info badge">';
                    } else if ($data->status == "sepakat") {
                        $datas .= '<small class="badge-success badge">';
                    }
                    $datas .= ucfirst($data->status) . '</small>';
                }

                return $datas;
            })
            ->addColumn('no_paket_ppic', function ($data) {
                return $data->no_paket;
            })
            ->addColumn('status_ppic', function ($data) {
                return $data->status;
            })
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                $datas = "";
                if ($data->Pesanan->log_id == '7') {
                    $datas .= '<span class="red-text badge">Penjualan</span>';

                    // if (!empty($data->status)) {
                    //     if ($data->status == "batal") {
                    //         $datas .= '<span class="red-text badge">';
                    //     } else if ($data->status == "negosiasi") {
                    //         $datas .= '<span class="yellow-text badge">';
                    //     } else if ($data->status == "draft") {
                    //         $datas .= '<span class="blue-text badge">';
                    //     } else if ($data->status == "sepakat") {
                    //         $datas .= '<span class="green-text badge">';
                    //     }
                    //     $datas .= ucfirst($data->status) . '</span>';
                    // }
                } else {
                    if ($data->status == "batal") {
                        $datas .= '<span class="red-text badge">Batal</span>';
                        // $datas = '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="'.$data->id.'" data-jenis="EKAT" data-provinsi="">
                        //     <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                        //         <i class="fas fa-times"></i>
                        //         Batal
                        //     </button>
                        // </a>';
                    } else {
                        $hitung = floor((($data->cseri / $data->cjumlah) * 100));
                        if ($hitung > 0) {
                            $datas = '<div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                        } else {
                            $datas = '<div class="progress">
                                <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                        }
                    }
                }
                return $datas;
            })
            ->addColumn('status_ppic', function ($data) {
                $datas = "";
                if ($data->Pesanan->log_id == '7') {
                    $datas .= 'penjualan';
                } else {
                    if ($data->status == "batal") {
                        $datas .= 'batal';
                    } else {
                        $hitung = floor((($data->cseri / $data->cjumlah) * 100));
                        if ($hitung > 0) {
                            $datas = $hitung;
                        } else {
                            $datas = $hitung;
                        }
                    }
                }
                return $datas;
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->no_po)) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->editColumn('tgl_buat', function ($data) {
                if (!empty($data->tgl_buat)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
                }
            })
            ->editColumn('tgl_edit', function ($data) {
                if (!empty($data->tgl_edit)) {
                    return Carbon::createFromFormat('Y-m-d', $data->tgl_edit)->format('d-m-Y');
                }
            })->editColumn('tgl_kontrak', function ($data) {

                if ($data->tgl_kontrak_custom != "") {
                    if ($data->Pesanan->log_id != "10") {
                        $tgl_sekarang = Carbon::now();
                        $tgl_parameter = $data->tgl_kontrak_custom;
                        $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                        if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                            if ($hari > 7) {
                                return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                        <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else if ($hari > 0 && $hari <= 7) {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                        <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                            } else {
                                return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                        <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                            }
                        } else {
                            return  '<div class="text-danger"><b>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                    <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                        }
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                    }
                }
            })
            ->addColumn('nama_customer', function ($data) {
                if (isset($data->Customer)) {
                    return $data->Customer['nama'];
                } else {
                    return '-';
                }
            })
            ->addColumn('button', function ($data) use ($divisi_id) {
                $return = "";
                if ($data->status != 'draft') {
                    if ($divisi_id == "26") {
                        $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    } else {
                        $return .= '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    }
                } else {
                    if ($divisi_id == "26") {
                        $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';
                        if (isset($data->Pesanan->DetailPesanan)) {
                            $return .= '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button">
                                <i class="fas fa-eye"></i>
                                Detail
                                </button>
                            </a>';
                        }
                    } else {
                        return '';
                    }
                    // $return .= "-";
                }



                if($data->status == 'sepakat' && ($data->Pesanan->no_po != NULL && $data->Pesanan->tgl_po != NULL)) {
                        $return .= '
                        <a target="_blank" href="' . route('penjualan.penjualan.cetak_surat_perintah', [$data->Pesanan->id]) . '">
                            <button class="dropdown-item" type="button" >
                            <i class="fas fa-print"></i>
                            SPPB
                            </button>
                        </a>
                        ';
                }

                if ($divisi_id == "26") {
                    if (!empty($data->Pesanan->log_id)) {
                        if ($data->Pesanan->State->nama == "Penjualan" || $data->cgudang == 0) {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'ekatalog']) . '" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                                </button>
                            </a>
                            ';
                            // if ($data->status == 'sepakat') {
                            //     if ($data->Pesanan == '') {
                            //         $return .= '<a href="' . route('penjualan.so.create', [$data->id]) . '" data-id="' . $data->id . '">
                            //             <button class="dropdown-item" type="button" >
                            //             <i class="fas fa-plus"></i>
                            //             Tambah PO
                            //             </button>
                            //         </a>';
                            //     } else {
                            //         if ($data->Pesanan->so == '') {
                            //             $return .= '<a href="' . route('penjualan.so.create', [$data->id]) . '" data-id="' . $data->id . '">
                            //                 <button class="dropdown-item" type="button" >
                            //                 <i class="fas fa-plus"></i>
                            //                 Tambah PO
                            //                 </button>
                            //             </a>';
                            //         }
                            //     }
                            // }
                            $return .= '<a data-toggle="modal" data-target="ekatalog" class="deletemodal" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="far fa-trash-alt"></i>
                                    Hapus
                                    </button>
                                </a>
                                ';
                        } else {
                            $return .= '<a data-toggle="modal" data-jenis="ekatalog" class="editmodal" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button">
                                <i class="fas fa-pencil-alt"></i>
                                Edit No Urut & DO
                                </button>
                            </a>';
                            if ($data->cseri <= 0) {
                                $return .= '<hr class="separator">
                                <a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="EKAT" data-provinsi="">
                                    <button class="dropdown-item" type="button">
                                        <i class="fas fa-times"></i>
                                        Batal
                                    </button>
                                </a>
                                ';
                            }
                        }
                    } else if (empty($data->Pesanan->log_id)) {
                        $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'ekatalog']) . '" data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="fas fa-pencil-alt"></i>
                            Edit
                            </button>
                        </a>
                        <a data-toggle="modal" data-target="ekatalog" class="deletemodal" data-id="' . $data->id . '">
                            <button class="dropdown-item" type="button" >
                            <i class="far fa-trash-alt"></i>
                            Hapus
                            </button>
                        </a>
                        ';
                    }
                    $return .= '</div>';
                }

                return $return;
            })


            ->rawColumns(['button', 'status', 'tgl_kontrak', 'no_paket'])
            ->setRowClass(function ($data) {
                if ($data->status == 'batal' || $data->Pesanan->State->nama == 'Batal') {
                    return 'text-danger font-weight-bold line-through';
                }
            })
            ->make(true);
    }
    public function get_data_spa($value, $tahun)
    {
        $divisi_id = Auth::user()->divisi_id;
        $x = explode(',', $value);
        $data = "";
        if ($value == 'semua') {
            $data  = Spa::with(['Pesanan.State',  'Customer'])->addSelect([
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                  'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'not like', '%Jasa%')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cujipart' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cujijasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                    ->from('outgoing_pesanan_part')
                    ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'like', '%Jasa%')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },

            ])->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get();
        } else {
            $data  = Spa::with(['Pesanan.State',  'Customer'])->addSelect([
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                },
                'cpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'not like', '%Jasa%')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');

                },
                'cjasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cujipart' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                },
                'cujijasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                    ->from('outgoing_pesanan_part')
                    ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'like', '%Jasa%')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                }

            ])->whereHas('pesanan', function ($q) use ($x) {
                $q->whereIN('log_id', $x);
            })->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get();
        }

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->no_po)) {
                        return $data->Pesanan->no_po;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                // $datas = "";
                // if ($data->log != "batal") {
                //     if (!empty($data->Pesanan->log_id)) {
                //         if ($data->Pesanan->State->nama == "PO") {
                //             $datas .= '<span class="purple-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Penjualan") {
                //             $datas .= '<span class="red-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Gudang") {
                //             $datas .= '<span class="orange-text badge">';
                //         } else if ($data->Pesanan->State->nama == "QC") {
                //             $datas .= '<span class="yellow-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                //             $datas .= '<span class="red-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                //             $datas .= '<span class="blue-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Kirim") {
                //             $datas .= '<span class="green-text badge">';
                //         }
                //         $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                //     } else {
                //         $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                //     }
                // } else {
                //     $datas .= '<span class="red-text badge">Batal</span>';
                // }
                // return $datas;
                $datas = "";
                $tes = $data->cjumlahprd + $data->cjumlahpart;
                if ($tes > 0) {
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                    if ($data->log == "batal") {
                        $datas = '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($hitung > 0) {
                            $datas = '<div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                        } else {
                            $datas = '<div class="progress">
                                <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                        }
                    }
                }
                return $datas;
            })
            ->addColumn('status_ppic', function ($data) {
                $datas = "";
                $tes = $data->cjumlahprd + $data->cjumlahpart;
                if ($tes > 0) {
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                    if ($data->log == "batal") {
                        $datas = 'batal';
                    } else {
                        if ($hitung > 0) {
                            $datas = $hitung;
                        } else {
                            $datas = $hitung;
                        }
                    }
                }
                return $datas;
            })
            ->addColumn('tglpo', function ($data) {
                if ($data->Pesanan) {
                    if ($data->Pesanan->tgl_po == "0000-00-00" || empty($data->Pesanan->tgl_po)) {
                        return '-';
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                $divisi_id = Auth::user()->divisi_id;
                $return = "";

                if ($divisi_id == "26") {
                    if ($data->log != "batal") {
                        $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '" >
                        <button class="dropdown-item" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                        if (!empty($data->Pesanan->log_id)) {
                        $item = array();
                            if($data->cjumlahprd > 0 && $data->cgudang > 0){
                                array_push($item,"1");
                            }

                            if($data->cpart > 0 && $data->cujipart > 0){
                                array_push($item,"1");
                            }

                            if($data->cjasa > 0  && $data->cujijasa > 0 ){
                                array_push($item,"1");
                            }

                            if ($data->Pesanan->State->nama == "PO" ||count($item) == 0 ) {
                                $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spa']) . '" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit
                                    </button>
                                </a>';
                                if ($divisi_id == "26") {
                                $return .= '<a data-toggle="modal" data-target="spa" class="deletemodal" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="far fa-trash-alt"></i>
                                    Hapus
                                    </button>
                                </a>
                                ';
                            }
                            } else {
                                if ($divisi_id == "26") {
                                $return .= '<a data-toggle="modal" data-jenis="spa" class="editmodal" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit DO
                                    </button>
                                </a>
                                ';
                             }
                            }
                            if ($data->Pesanan->no_po != NULL && $data->Pesanan->tgl_po != NULL) {
                            $return .= '
                            <a target="_blank" href="' . route('penjualan.penjualan.cetak_surat_perintah', [$data->Pesanan->id]) . '">
                                <button class="dropdown-item" type="button" >
                                <i class="fas fa-print"></i>
                                SPPB
                                </button>
                            </a>';
                            }
                            $jumkirim = ($data->ckirimprd + $data->ckirimpart);
                            if ($jumkirim <= 0) {
                                $return .= '<hr class="separator">
                                <a data-toggle="modal" data-jenis="SPA" class="batalmodal" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-times"></i>
                                    Batal
                                    </button>
                                </a>';
                            }
                        } else {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spa']) . '" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                                </button>
                            </a>';
                            $return .= '<a data-toggle="modal" data-target="spa" class="deletemodal" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="far fa-trash-alt"></i>
                                Hapus
                                </button>
                            </a>
                            ';
                        }
                        $return .= '</div>';
                    } else {
                        $return .= '<a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '" >
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    }
                } else {
                    $return .= '<a data-toggle="modal" data-target="spa" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '" >
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                        </button>
                    </a>';
                }

                return $return;
            })
            ->rawColumns(['button', 'status'])
            ->setRowClass(function ($data) {
                if ($data->Pesanan->State->nama == 'Batal') {
                    return 'text-danger font-weight-bold line-through';
                }
            })
            ->make(true);
    }
    public function get_data_spb($value, $tahun)
    {
        $divisi_id = Auth::user()->divisi_id;
        $x = explode(',', $value);
        $data = "";
        if ($value == 'semua') {
            $data  = Spb::with(['Pesanan.State',  'Customer'])->addSelect([
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'not like', '%Jasa%')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');

                },
                'cjasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cujipart' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cujijasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                    ->from('outgoing_pesanan_part')
                    ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'like', '%Jasa%')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
            ])->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get();
        } else {
            $data  = Spb::with(['Pesanan.State',  'Customer'])->addSelect([
                'ckirimprd' => function ($q) {
                    $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                        ->from('noseri_logistik')
                        ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                        ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahprd' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                        ->from('detail_pesanan')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'ckirimpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                        ->from('detail_logistik_part')
                        ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cjumlahpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                        ->from('detail_pesanan_part')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                } ,
                'cgudang' => function ($q) {
                    $q->selectRaw('count(detail_pesanan_produk.id)')
                        ->from('detail_pesanan_produk')
                        ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->where('detail_pesanan_produk.status_cek', '4')
                        ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                },
                'cpart' => function ($q) {
                    $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                    ->from('detail_pesanan_part')
                    ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'not like', '%Jasa%')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');

                },
                'cjasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cujipart' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                        ->from('outgoing_pesanan_part')
                        ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                        ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                        ->where('m_sparepart.kode', 'not like', '%Jasa%')
                        ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
                'cujijasa' => function ($q) {
                    $q->selectRaw('count(outgoing_pesanan_part.id)')
                    ->from('outgoing_pesanan_part')
                    ->leftjoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftjoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->where('m_sparepart.kode', 'like', '%Jasa%')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                },
            ])->whereHas('pesanan', function ($q) use ($x) {
                $q->whereIN('log_id', $x);
            })->whereYear('created_at',  $tahun)->orderBy('id', 'DESC')->get();
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                if ($data->Pesanan) {
                    if (!empty($data->Pesanan->so)) {
                        return $data->Pesanan->so;
                    } else {
                        return '-';
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('status', function ($data) {
                // $datas = "";
                // if ($data->log != "batal") {
                //     if (!empty($data->Pesanan->log_id)) {
                //         if ($data->Pesanan->State->nama == "Penjualan") {
                //             $datas .= '<span class="red-text badge">';
                //         } else if ($data->Pesanan->State->nama == "PO") {
                //             $datas .= '<span class="purple-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Gudang") {
                //             $datas .= '<span class="orange-text badge">';
                //         } else if ($data->Pesanan->State->nama == "QC") {
                //             $datas .= '<span class="yellow-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Belum Terkirim") {
                //             $datas .= '<span class="red-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Terkirim Sebagian") {
                //             $datas .= '<span class="blue-text badge">';
                //         } else if ($data->Pesanan->State->nama == "Kirim") {
                //             $datas .= '<span class="green-text badge">';
                //         }

                //         $datas .= ucfirst($data->Pesanan->State->nama) . '</span>';
                //     } else {
                //         $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
                //     }
                // } else {
                //     $datas .= '<span class="red-text badge">Batal</span>';
                // }
                // return $datas;
                $datas = "";
                $tes = $data->cjumlahprd + $data->cjumlahpart;
                if ($tes > 0) {
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                    if ($data->log == "batal") {
                        $datas = '<span class="badge red-text">Batal</span>';
                    } else {
                        if ($hitung > 0) {
                            $datas = '<div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                        } else {
                            $datas = '<div class="progress">
                                <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                            </div>
                            <small class="text-muted">Selesai</small>';
                        }
                    }
                }
                return $datas;
            })
            ->addColumn('status_ppic', function ($data) {
                $datas = "";
                $tes = $data->cjumlahprd + $data->cjumlahpart;
                if ($tes > 0) {
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));
                    if ($data->log == "batal") {
                        $datas = 'batal';
                    } else {
                        if ($hitung > 0) {
                            $datas = $hitung;
                        } else {
                            $datas = $hitung;
                        }
                    }
                }
                return $datas;
            })
            ->addColumn('nopo', function ($data) {
                if ($data->Pesanan) {
                    return $data->Pesanan->no_po;
                } else {
                    return '-';
                }
            })
            ->addColumn('tglpo', function ($data) {
                if ($data->Pesanan) {
                    if ($data->Pesanan->tgl_po == "0000-00-00") {
                        return '-';
                    } else {
                        return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_customer', function ($data) {
                return $data->Customer->nama;
            })
            ->addColumn('button', function ($data) {
                $divisi_id = Auth::user()->divisi_id;
                $return = "";

                if ($divisi_id == "26") {
                    $return .= '<div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v"></i></div>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a data-toggle="modal" data-target="spb" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '" >
                    <button class="dropdown-item" type="button">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                    </a>';
                    if ($data->log != "batal") {
                        if (!empty($data->Pesanan->log_id)) {

                            $item = array();
                            if($data->cjumlahprd > 0 && $data->cgudang > 0){
                                array_push($item,"1");
                            }

                            if($data->cpart > 0 && $data->cujipart > 0){
                                array_push($item,"1");
                            }

                            if($data->cjasa > 0  && $data->cujijasa > 0 ){
                                array_push($item,"1");
                            }

                            if ($data->Pesanan->State->nama == "PO" ||count($item) == 0) {
                                $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spb']) . '" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-pencil-alt"></i>
                                    Edit
                                    </button>
                                </a>';
                                if ($divisi_id == "26") {
                                    $return .= '<a data-toggle="modal" data-target="spb" class="deletemodal" data-id="' . $data->id . '">
                                        <button class="dropdown-item" type="button" >
                                        <i class="far fa-trash-alt"></i>
                                        Hapus
                                        </button>
                                    </a>
                                    ';
                                }
                            } else {
                                if ($divisi_id == "26") {
                                    $return .= '<a data-toggle="modal" data-jenis="spb" class="editmodal" data-id="' . $data->id . '">
                                        <button class="dropdown-item" type="button" >
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit DO
                                        </button>
                                    </a>
                                    ';
                                }
                            }
                            if ($data->Pesanan->no_po != NULL && $data->Pesanan->tgl_po != NULL) {
                                $return .= '
                                <a target="_blank" href="' . route('penjualan.penjualan.cetak_surat_perintah', [$data->Pesanan->id]) . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-print"></i>
                                    SPPB
                                    </button>
                                </a>';
                                }
                            $jumkirim = ($data->ckirimprd + $data->ckirimpart);
                            if ($jumkirim <= 0) {
                                $return .= '<hr class="separator">
                                <a data-toggle="modal" data-jenis="SPA" class="batalmodal" data-id="' . $data->id . '">
                                    <button class="dropdown-item" type="button" >
                                    <i class="fas fa-times"></i>
                                    Batal
                                    </button>
                                </a>';
                            }
                        } else {
                            $return .= '<a href="' . route('penjualan.penjualan.edit_ekatalog', [$data->id, 'jenis' => 'spb']) . '" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                                </button>
                            </a>';
                            if ($divisi_id == "26") {
                                $return .= '<a data-toggle="modal" data-target="spb" class="deletemodal" data-id="' . $data->id . '">
                                <button class="dropdown-item" type="button" >
                                <i class="far fa-trash-alt"></i>
                                Hapus
                                </button>
                            </a>';
                            }
                        }
                    }
                    $return .= '</div>';
                } else {
                    $return .= '<a data-toggle="modal" data-target="spb" class="detailmodal" data-label data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '" >
                    <button class="btn btn-outline-primary btn-sm" type="button">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                    </a>';
                }

                return $return;
            })
            ->rawColumns(['button', 'status'])
            ->setRowClass(function ($data) {
                if ($data->Pesanan->State->nama == 'Batal') {
                    return 'text-danger font-weight-bold line-through';
                }
            })
            ->make(true);
    }
    public function get_data_rencana_produk($customer_id, $instansi, $tahun)
    {
        $data = DetailRencanaPenjualan::whereHas('RencanaPenjualan', function ($q) use ($customer_id, $instansi, $tahun) {
            $q->where(['customer_id' => $customer_id, 'instansi' => $instansi, 'tahun' => $tahun]);
        })->get();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('nama_produk', function ($data) {
                return $data->PenjualanProduk->nama;
            })
            ->addColumn('qty', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('realisasi', function ($data) use ($customer_id, $instansi, $tahun) {
                $res = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($customer_id, $instansi, $tahun) {
                    $q->where(['customer_id' => $customer_id, 'instansi' => $instansi])->whereBetween('tgl_buat', [$tahun . '-01-01', $tahun . '-12-31']);
                })->where('penjualan_produk_id', $data->PenjualanProduk->id)->sum('jumlah');

                return $res;
            })
            ->addColumn('harga', function ($data) {
                return $data->harga;
            })
            ->addColumn('aksi', function ($data) {
                $res = '<button type="button" class="btn btn-outline-primary btn-circle" id="btntransfer" data-id="' . $data->id . '" data-nama_produk="' . $data->penjualanproduk->nama . '" data-produk="' . $data->penjualanproduk->id . '" data-jumlah="' . $data->jumlah . '" data-harga="' . $data->harga . '"><i class="fas fa-plus"></i></button>';
                return $res;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }


    // Create
    public function create_penjualan(Request $request)
    {

        if ($request->jenis_penjualan == 'ekatalog') {
            if ($request->status == 'sepakat' && ($request->namadistributor == 'belum' ||$request->provinsi == "NULL") ) {
                    return response()->json([
                        'message' => 'Cek Form Kembali',
                    ], 500);
            }
            if ($request->no_po_ekat != NULL && ( $request->perusahaan_pengiriman_ekat == NULL || $request->alamat_pengiriman_ekat == NULL ||  $request->kemasan == NULL) ) {
                    return response()->json([
                        'message' => 'Cek Form Kembali',
                    ], 500);
            }
            //dd($request);
            // $this->validate(
            //     $request,
            //     [
            //         'no_paket' => 'required',
            //         'customer_id' => 'required',
            //         'status' => 'required',
            //         'tgl_kontrak' => 'required',
            //         'jumlah.*' => 'required',
            //         'penjualan_produk_id.*' => 'required'
            //     ],
            //     [
            //         'no_paket.required' => 'No Paket harus di isi',
            //         'customer_id.required' => 'Customer harus di isi',
            //         'status.required' => 'Status harus di pilih',
            //         'tgl_kontrak.required' => 'Tg; Kontrak harus di isi',
            //         'jumlah.required' => 'Jumlah Produk harus di isi',
            //         'penjualan_produk_id.required' => 'Produk harus di pilih',
            //     ]
            // );


            //Konversi No SO
            // $x = Ekatalog::max('id') + 1;
            // $y = Carbon::now()->format('Y');
            // $m = Carbon::now()->format('m');
            // $filter = new IntToRoman();
            $so = NULL;
            $no_po = NULL;
            $tgl_po = NULL;
            $no_do = NULL;
            $tgl_do = NULL;
            $ket_po = NULL;
            $log_id = "7";

            if ($request->no_po_ekat != "") {
                $so = $this->createSO('EKAT');
                $no_po = $request->no_po_ekat;
                $tgl_po = $request->tanggal_po_ekat;
                $no_do = $request->no_do_ekat;
                $tgl_do = $request->tanggal_do_ekat;
                $ket_po = $request->keterangan_ekat;
                if ($request->status == 'sepakat') {
                    $log_id = "9";
                }
            }

            $pesanan = Pesanan::create([
                'so' => $so,
                'no_po' => $no_po,
                'tgl_po' => $tgl_po,
                'no_do' => $no_do,
                'tgl_do' => $tgl_do,
                'ket' =>  $ket_po,
                'log_id' => $log_id,
                'tujuan_kirim' => $request->perusahaan_pengiriman_ekat,
                'alamat_kirim' => $request->alamat_pengiriman_ekat,
                'kemasan' => $request->kemasan,
                'ekspedisi_id' => $request->ekspedisi,
                'ket_kirim' => $request->keterangan_pengiriman,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);

            $x = $pesanan->id;
            if ($request->namadistributor == 'belum') {
                $c_id = '484';
            } else {
                $c_id = $request->customer_id;
            }

            if ($request->no_paket != "") {
                $nopaket = $request->jenis_paket . $request->no_paket;
            } else {
                $nopaket = "";
            }


            $Ekatalog = Ekatalog::create([
                'customer_id' => $c_id,
                'provinsi_id' => $request->provinsi == 'NULL' ? NULL : $request->provinsi,
                'pesanan_id' => $x,
                'no_paket' => $nopaket,
                'no_urut' => $request->no_urut,
                'deskripsi' => $request->deskripsi,
                'instansi' => $request->instansi,
                'alamat' => $request->alamatinstansi,
                'satuan' => $request->satuan_kerja,
                'status' => $request->status,
                'tgl_kontrak' => $request->batas_kontrak,
                'tgl_buat' => $request->tanggal_pemesanan,
                'tgl_edit' => $request->tanggal_edit,
                'ket' => $request->keterangan,
                'log' => 'penjualan'
            ]);

            $bool = true;
            if ($Ekatalog) {
                if (($request->status == 'sepakat') || ($request->status == 'negosiasi')) {

                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        if (empty($request->produk_ongkir[$i])) {
                            $ongkir[$i] = 0;
                        } else {
                            $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                        }
                        $dekat = DetailPesanan::create([
                            'pesanan_id' => $x,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'detail_rencana_penjualan_id' => $request->rencana_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                            'ongkir' => $ongkir[$i],
                        ]);

                        if (!$dekat) {
                            $bool = false;
                        } else {
                            for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                $dekatp = DetailPesananProduk::create([
                                    'detail_pesanan_id' => $dekat->id,
                                    'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                ]);
                                if (!$dekatp) {
                                    $bool = false;
                                }
                            }
                        }
                    }
                } else {
                    if ($request->isi_produk == "isi") {
                        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                            if (empty($request->produk_ongkir[$i])) {
                                $ongkir[$i] = 0;
                            } else {
                                $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                            }
                            $dekat = DetailPesanan::create([
                                'pesanan_id' => $x,
                                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                'detail_rencana_penjualan_id' => $request->rencana_id[$i],
                                'jumlah' => $request->produk_jumlah[$i],
                                'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                'ongkir' => $ongkir[$i],
                            ]);

                            if (!$dekat) {
                                $bool = false;
                            } else {
                                for ($j = 0; $j < count(array($request->variasi[$i])); $j++) {
                                    $dekatp = DetailPesananProduk::create([
                                        'detail_pesanan_id' => $dekat->id,
                                        'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                    ]);
                                    if (!$dekatp) {
                                        $bool = false;
                                    }
                                }
                            }
                        }
                    } else {
                        $bool = true;
                    }
                }
            } else {
                $bool = false;
            }
            if ($bool == true) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Ditambahkan',
                    'pesanan_id' => $pesanan->no_po != null ? $pesanan->id : 'refresh',
                ], 200);
            } else if ($bool == false) {
                return response()->json([
                    'message' => 'Cek Form Kembali',
                ], 500);
            }
        } else if ($request->jenis_penjualan == 'spa' || $request->jenis_penjualan == 'spb') {
if( $request->perusahaan_pengiriman != NULL && $request->alamat_pengiriman != NULL &&  $request->kemasan != NULL){
            $count_array = count($request->jenis_pen);
            if (in_array("jasa", $request->jenis_pen) && $count_array == 1) {
                $k = '11';
            } else {
                $k = '9';
            }
            if ($request->jenis_penjualan == 'spa') {
                $var = 'SPA';
            } else if ($request->jenis_penjualan == 'spb') {
                $var = 'SPB';
            }
            $pesanan = Pesanan::create([
                'so' => $this->createSO($var),
                'no_po' => $request->no_po,
                'tgl_po' => $request->tanggal_po,
                'no_do' => $request->no_do,
                'tgl_do' => $request->tanggal_do,
                'ket' =>  $request->keterangan,
                'tujuan_kirim' => $request->perusahaan_pengiriman,
                'alamat_kirim' => $request->alamat_pengiriman,
                'kemasan' => $request->kemasan,
                'ekspedisi_id' => $request->ekspedisi,
                'ket_kirim' => $request->keterangan_pengiriman,
                'log_id' => $k
            ]);
            $x = $pesanan->id;
            $no_po_nonekat = $pesanan->no_po;
            if ($request->jenis_penjualan == 'spa') {
                $p = Spa::create([
                    'customer_id' => $request->customer_id,
                    'pesanan_id' => $x,
                    'ket' => $request->keterangan,
                    'log' => 'po'
                ]);
            } else if ($request->jenis_penjualan == 'spb') {
                $p = Spb::create([
                    'customer_id' => $request->customer_id,
                    'pesanan_id' => $x,
                    'ket' => $request->keterangan,
                    'log' => 'po'
                ]);
            }
            $bool = true;
            if ($p) {
                if (in_array("produk", $request->jenis_pen)) {
                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        $dspa = DetailPesanan::create([
                            'pesanan_id' => $x,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => 0,
                        ]);

                        for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                            $dspap = DetailPesananProduk::create([
                                'detail_pesanan_id' => $dspa->id,
                                'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                            ]);
                            if (!$dspap) {
                                $bool = false;
                            }
                        }
                    }
                }
                if (in_array("sparepart", $request->jenis_pen)) {
                    for ($i = 0; $i < count($request->part_id); $i++) {
                        $dspb = DetailPesananPart::create([
                            'pesanan_id' => $x,
                            'm_sparepart_id' => $request->part_id[$i],
                            'jumlah' => $request->part_jumlah[$i],
                            'harga' => str_replace('.', "", $request->part_harga[$i]),
                            'ppn' => isset($request->part_ppn[$i]) ? $request->part_ppn[$i] : 0,
                            'ongkir' => 0,
                        ]);
                        if (!$dspb) {
                            $bool = false;
                        }
                    }
                }
                if (in_array("jasa", $request->jenis_pen)) {
                    for ($i = 0; $i < count($request->jasa_id); $i++) {
                        $dspb = DetailPesananPart::create([
                            'pesanan_id' => $x,
                            'm_sparepart_id' => $request->jasa_id[$i],
                            'jumlah' => 1,
                            'harga' => str_replace('.', "", $request->jasa_harga[$i]),
                            'ppn' => isset($request->jasa_ppn[$i]) ? $request->jasa_ppn[$i] : 0,
                            'ongkir' => 0,
                        ]);

                        $qcspb = OutgoingPesananPart::create([
                            'detail_pesanan_part_id' => $dspb->id,
                            'tanggal_uji' => $request->tanggal_po,
                            'jumlah_ok' => 1,
                            'jumlah_nok' => 0
                        ]);

                        if (!$dspb) {
                            $bool = false;
                        }
                    }
                }
            } else {
                $bool = false;
            }

            if ($bool == true) {
                return response()->json([
                    'status' => 200,
                    'message' => 'Berhasil Ditambahkan',
                    'pesanan_id' => $no_po_nonekat != null ? $x : 'refresh',
                ], 200);
            } else if ($bool == false) {
                return response()->json([
                    'message' => 'Cek Form Kembali',
                ], 500);
            }
        }
    }else{
        return response()->json([
            'message' => 'Cek Form Kembali',
        ], 500);
    }
    }

    public function view_so_ekatalog($value)
    {
        $ekatalog = Ekatalog::find($value);
        return view('page.penjualan.so.create', ['ekatalog' => $ekatalog]);
    }
    public function create_so_ekatalog(Request $request, $id)
    {
        $v = "";
        // Validator::make(
        //     $request->all(),
        //     [
        //         'customer_id' => 'required',
        //         'status' => 'required',
        //     ],
        //     [
        //         'customer_id.required' => 'Customer harus di isi',
        //         'status.required' => 'Status harus di pilih',
        //     ]
        // );

        // if ($v->fails()) {
        //     return redirect()->back()->withErrors($v);
        // } else {
        // $this->validate(
        //     $request,
        //     [w
        //         'customer_id' => 'required',
        //         'status' => 'required',
        //         'jumlah.*' => 'required',
        //         'penjualan_produk_id.*' => 'required'
        //     ],
        //     [
        //         'customer_id.required' => 'Customer harus di isi',
        //         'status.required' => 'Status harus di pilih',
        //         'jumlah.required' => 'Jumlah Produk harus di isi',
        //         'penjualan_produk_id.required' => 'Produk harus di pilih',
        //     ]
        // );
        $bool = true;

        $ekatalog = Ekatalog::find($id);
        $p = Pesanan::find($ekatalog->pesanan_id);


        if (isset($p)) {
            $p->so = $this->createSO('EKAT');
            $p->no_po = $request->no_po;
            $p->tgl_po = $request->tanggal_po;
            $p->no_do = $request->no_do;
            $p->tgl_do = $request->tanggal_do;
            $p->ket = $request->keterangan;
            $p->log_id = "9";
            $pes = $p->save();
            if (!$pes) {
                $bool = false;
            }
        } else {
            $po = Pesanan::create([
                'so' => $this->createSO('EKAT'),
                'no_po' => $request->no_po,
                'tgl_po' => $request->tanggal_po,
                'no_do' => $request->no_do,
                'tgl_do' => $request->tanggal_do,
                'ket' => $request->keterangan
            ]);

            if ($po) {
                $ekatalog->pesanan_id = $po->id;
                $eksave = $ekatalog->save();
                if (!$eksave) {
                    $bool = false;
                }
            }
        }

        $ekatalog->log = "po";
        $ekatalog->save();
        if ($bool == true) {
            return redirect()->back()->with('success', 'Berhasil menambahkan PO');
        } else if ($bool == false) {
            return redirect()->back()->with('error', 'Gagal menambahkan PO');
        }
        // }
    }
    //Update
    public function edit_penjualan_pesanan($id, $jenis)
    {
        $data = "";
        if ($jenis == "ekatalog") {
            $data = Ekatalog::find($id);
        } else if ($jenis == "spa") {
            $data = Spa::find($id);
        } else if ($jenis == "spb") {
            $data = Spb::find($id);
        }
        return view('page.penjualan.penjualan.edit_pesanan', ['data' => $data, 'id' => $id, 'jenis' => $jenis]);
    }

    public function update_penjualan($id, $jenis)
    {
        if ($jenis == 'ekatalog') {
            $ekatalog = Ekatalog::find($id);
            return view('page.penjualan.penjualan.edit_ekatalog', ['e' => $ekatalog]);
        } else if ($jenis == 'spa') {
            $spa = Spa::where('id', $id)->get();
            return view('page.penjualan.penjualan.edit_spa', ['spa' => $spa]);
        } else {
            $spb = Spb::where('id', $id)->get();
            return view('page.penjualan.penjualan.edit_spb', ['spb' => $spb]);
        }
    }
    public function update_ekatalog(Request $request, $id)
    {
        //dd($request->all());
        if ($request->status_akn == 'sepakat' && ($request->namadistributor == 'belum' ||$request->provinsi == "NULL")) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
    }

    if ($request->status == 'sepakat' && ( $request->perusahaan_pengiriman == NULL || $request->alamat_pengiriman == NULL ||  $request->kemasan == NULL ) ) {
        return response()->json([
            'message' => 'Cek Form Kembali',
        ], 500);
}

        // echo json_encode($request->all());
        if ($request->namadistributor == 'belum') {
            $c_id = '484';
        } else {
            $c_id = $request->customer_id;
        }

        $ekatalog = Ekatalog::find($id);

        if ($request->status_akn == 'draft' ||  $request->status_akn == 'batal') {
            if ($request->no_paket == '') {
                $c_akn = NULL;
            } else {
                $c_akn = $request->has('isi_nopaket') ? $request->jenis_paket . $request->no_paket : NULL;
            }

            $akn = $c_akn;
        } else {
            $akn = $ekatalog->no_paket;
        }


        $poid = $ekatalog->pesanan_id;
        $ekatalog->customer_id = $c_id;
        $ekatalog->provinsi_id = $request->provinsi == "NULL" ? NULL : $request->provinsi;
        $ekatalog->deskripsi = $request->deskripsi;
        $ekatalog->instansi = $request->instansi;
        $ekatalog->alamat = $request->alamatinstansi;
        $ekatalog->tgl_kontrak = $request->batas_kontrak;
        $ekatalog->tgl_buat = $request->tgl_buat;
        $ekatalog->tgl_edit = $request->tgl_edit;
        $ekatalog->no_urut = $request->no_urut;
        $ekatalog->satuan = $request->satuan_kerja;
        $ekatalog->status = $request->status_akn;
        $ekatalog->ket = $request->keterangan;
        $ekatalog->no_paket = $akn;
        $ekat = $ekatalog->save();

        $p = Pesanan::find($poid);
        if ($p->so == NULL && $request->no_po_ekat != NULL && ($request->status_akn != "draft" || $request->status_akn != "batal")) {
            $p->so = $this->createSO('EKAT');
        }
        $p->no_po = $request->no_po_ekat;
        $p->tgl_po = $request->tanggal_po_ekat;
        $p->no_do = $request->no_do_ekat;
        $p->tgl_do = $request->tanggal_do_ekat;
        $p->tujuan_kirim = $request->perusahaan_pengiriman;
        $p->alamat_kirim = $request->alamat_pengiriman;
        $p->kemasan = $request->kemasan;
        $p->ekspedisi_id = $request->ekspedisi;
        $p->ket_kirim = $request->keterangan_pengiriman;
        $p->ket = $request->keterangan_ekat;


        if ($request->status_akn == "sepakat" && $request->no_po_ekat != NULL) {
            $p->log_id = "9";
        }
        $p->save();

        $bool = true;
        if ($ekat) {
            $dekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->get();

            if (count($dekatp) > 0) {
                $deldekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->delete();
                if (!$deldekatp) {
                    $bool = false;
                }
            }
            $dekat = DetailPesanan::where('pesanan_id', $poid)->get();
            if (count($dekat) > 0) {
                $deldekat = DetailPesanan::where('pesanan_id', $poid)->delete();
                if (!$deldekat) {
                    $bool = false;
                }
            }
            if ($bool == true) {
                if (($request->status_akn == "sepakat") || ($request->status_akn == "negosiasi")) {

                    for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                        if (empty($request->produk_ongkir[$i])) {
                            $ongkir[$i] = 0;
                        } else {
                            $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                        }
                        $c = DetailPesanan::create([
                            'pesanan_id' => $poid,
                            'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                            'jumlah' => $request->produk_jumlah[$i],
                            'harga' => str_replace('.', "", $request->produk_harga[$i]),
                            'ongkir' => $ongkir[$i],
                            'detail_rencana_penjualan_id' => $request->rencana_id[$i],
                            'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                        ]);
                        if ($c) {
                            for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                $v = DetailPesananProduk::create([
                                    'detail_pesanan_id' => $c->id,
                                    'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                ]);
                                if (!$v) {
                                    $bool = false;
                                }
                            }
                        } else {
                            $bool = false;
                        }
                    }
                } elseif (($request->status_akn == "draft") || ($request->status_akn == "batal")) {
                    if ($request->isi_produk == "isi") {
                        for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                            if (empty($request->produk_ongkir[$i])) {
                                $ongkir[$i] = 0;
                            } else {
                                $ongkir[$i] =  str_replace('.', "", $request->produk_ongkir[$i]);
                            }
                            $c = DetailPesanan::create([
                                'pesanan_id' => $poid,
                                'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                'jumlah' => $request->produk_jumlah[$i],
                                'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                'ongkir' => $ongkir[$i],
                                'detail_rencana_penjualan_id' => $request->rencana_id[$i],
                                'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                            ]);
                            if ($c) {
                                for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                    $v = DetailPesananProduk::create([
                                        'detail_pesanan_id' => $c->id,
                                        'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                    ]);
                                    if (!$v) {
                                        $bool = false;
                                    }
                                }
                            } else {
                                $bool = false;
                            }
                        }
                    }
                }
            } else {
                $bool = false;
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } else if ($bool == false) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }
    }
    public function update_spa(Request $request, $id)
    {
       //dd($request->all());
        if ($request->perusahaan_pengiriman_nonakn == NULL || $request->alamat_pengiriman == NULL ||  $request->kemasan == NULL )  {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
    }
        $spa = Spa::find($id);
        $poid = $spa->pesanan_id;
        $spa->customer_id = $request->customer_id;
        $uspa = $spa->save();
        $bool = true;
        if ($uspa) {
            $pesanan = Pesanan::find($spa->pesanan_id);
            $pesanan->no_do = $request->no_do;
            $pesanan->tgl_do = $request->tanggal_do;
            $pesanan->ket = $request->keterangan;
            $pesanan->tujuan_kirim = $request->perusahaan_pengiriman_nonakn;
            $pesanan->alamat_kirim = $request->alamat_pengiriman;
            $pesanan->kemasan = $request->kemasan;
            $pesanan->ket_kirim = $request->keterangan_pengiriman;
            $pesanan->ekspedisi_id = $request->ekspedisi;
            $po = $pesanan->save();

            if ($po) {
                $dspap = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->get();
                if (count($dspap) > 0) {
                    $deldspap = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();
                    if (!$deldspap) {
                        $bool = false;
                    }
                }

                $dspa = DetailPesanan::where('pesanan_id', $poid)->get();
                if (count($dspa) > 0) {
                    $deldspa = DetailPesanan::where('pesanan_id', $poid)->delete();
                    if (!$deldspa) {
                        $bool = false;
                    }
                }

                if ($request->jenis_pen) {
                    if (in_array("produk", $request->jenis_pen)) {
                        if ($dspa) {
                            for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                                $c = DetailPesanan::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                    'jumlah' => $request->produk_jumlah[$i],
                                    'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                    'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                    'ongkir' => 0,
                                ]);
                                if (!$c) {
                                    $bool = false;
                                } else {
                                    for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                        $cd = DetailPesananProduk::create([
                                            'detail_pesanan_id' => $c->id,
                                            'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                        ]);
                                        if (!$cd) {
                                            $bool = false;
                                        }
                                    }
                                }
                            }
                        } else {
                            $bool = false;
                        }
                    } else {
                        $dspa = DetailPesanan::where('pesanan_id', $poid)->get();
                        if (count($dspa) > 0) {
                            $deldspa = DetailPesanan::where('pesanan_id', $poid)->delete();
                            if (!$deldspa) {
                                $bool = false;
                            }
                        }
                    }

                    if (in_array("sparepart", $request->jenis_pen)) {
                        $dspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                            $q->where('kode', 'not like', '%Jasa%');
                        })->where('pesanan_id', $poid)->get();
                        if (count($dspb) > 0) {
                            $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                                $q->where('kode', 'not like', '%Jasa%');
                            })->where('pesanan_id', $poid)->delete();
                            if (!$deldspb) {
                                $bool = false;
                            }
                        }
                        if ($dspb) {
                            for ($i = 0; $i < count($request->part_id); $i++) {
                                $dspb = DetailPesananPart::create([
                                    'pesanan_id' => $poid,
                                    'm_sparepart_id' => $request->part_id[$i],
                                    'jumlah' => $request->part_jumlah[$i],
                                    'harga' => str_replace('.', "", $request->part_harga[$i]),
                                    'ppn' => isset($request->produk_ppn[$i]) ? $request->produk_ppn[$i] : 0,
                                    'ongkir' => 0,
                                ]);
                                if (!$dspb) {
                                    $bool = false;
                                }
                            }
                        } else {
                            $bool = false;
                        }
                    } else {
                        $dspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                            $q->where('kode', 'not like', '%Jasa%');
                        })->where('pesanan_id', $poid)->get();
                        if (count($dspb) > 0) {
                            $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                                $q->where('kode', 'not like', '%Jasa%');
                            })->where('pesanan_id', $poid)->delete();
                            if (!$deldspb) {
                                $bool = false;
                            }
                        }
                    }
                } else {
                    $bool = false;
                }
            } else {
                $bool = false;
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } else if ($bool == false) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }
    }
    public function update_spb(Request $request, $id)
    {
       // dd($request->all());
        if ($request->perusahaan_pengiriman_nonakn == NULL || $request->alamat_pengiriman == NULL ||  $request->kemasan == NULL || $request->ekspedisi == NULL)  {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
    }
        $spa = Spb::find($id);
        $poid = $spa->pesanan_id;
        $spa->customer_id = $request->customer_id;
        $uspa = $spa->save();
        $bool = true;
        if ($uspa) {
            $pesanan = Pesanan::find($spa->pesanan_id);
            $pesanan->no_do = $request->no_do;
            $pesanan->tgl_do = $request->tanggal_do;
            $pesanan->ket = $request->keterangan;
            $pesanan->tujuan_kirim = $request->perusahaan_pengiriman_nonakn;
            $pesanan->alamat_kirim = $request->alamat_pengiriman;
            $pesanan->kemasan = $request->kemasan;
            $pesanan->ket_kirim = $request->keterangan_pengiriman;
            $pesanan->ekspedisi_id = $request->ekspedisi;
            $po = $pesanan->save();

            if ($po) {
                $dspap = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                    $q->where('pesanan_id', $poid);
                })->get();
                if (count($dspap) > 0) {
                    $deldspap = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                        $q->where('pesanan_id', $poid);
                    })->delete();
                    if (!$deldspap) {
                        $bool = false;
                    }
                }

                $dspa = DetailPesanan::where('pesanan_id', $poid)->get();
                if (count($dspa) > 0) {
                    $deldspa = DetailPesanan::where('pesanan_id', $poid)->delete();
                    if (!$deldspa) {
                        $bool = false;
                    }
                }
                if ($request->jenis_pen) {
                    if (in_array("produk", $request->jenis_pen)) {
                        if ($dspa) {
                            for ($i = 0; $i < count($request->penjualan_produk_id); $i++) {
                                $c = DetailPesanan::create([
                                    'pesanan_id' => $poid,
                                    'penjualan_produk_id' => $request->penjualan_produk_id[$i],
                                    'jumlah' => $request->produk_jumlah[$i],
                                    'harga' => str_replace('.', "", $request->produk_harga[$i]),
                                    'ppn' => isset($request->part_ppn[$i]) ? $request->part_ppn[$i] : 0,
                                    'ongkir' => 0,
                                ]);
                                if (!$c) {
                                    $bool = false;
                                } else {
                                    for ($j = 0; $j < count($request->variasi[$i]); $j++) {
                                        $cd = DetailPesananProduk::create([
                                            'detail_pesanan_id' => $c->id,
                                            'gudang_barang_jadi_id' => $request->variasi[$i][$j]
                                        ]);
                                        if (!$cd) {
                                            $bool = false;
                                        }
                                    }
                                }
                            }
                        } else {
                            $bool = false;
                        }
                    } else {
                        $dspa = DetailPesanan::where('pesanan_id', $poid)->get();
                        if (count($dspa) > 0) {
                            $deldspa = DetailPesanan::where('pesanan_id', $poid)->delete();
                            if (!$deldspa) {
                                $bool = false;
                            }
                        }
                    }

                    if (in_array("sparepart", $request->jenis_pen)) {
                        $dspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                            $q->where('kode', 'not like', '%Jasa%');
                        })->where('pesanan_id', $poid)->get();
                        if (count($dspb) > 0) {
                            $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                                $q->where('kode', 'not like', '%Jasa%');
                            })->where('pesanan_id', $poid)->delete();
                            if (!$deldspb) {
                                $bool = false;
                            }
                        }
                        if ($dspb) {
                            for ($i = 0; $i < count($request->part_id); $i++) {
                                $dspb = DetailPesananPart::create([
                                    'pesanan_id' => $poid,
                                    'm_sparepart_id' => $request->part_id[$i],
                                    'jumlah' => $request->part_jumlah[$i],
                                    'ppn' => isset($request->part_ppn[$i]) ? $request->part_ppn[$i] : 0,
                                    'harga' => str_replace('.', "", $request->part_harga[$i]),
                                    'ongkir' => 0,
                                ]);
                                if (!$dspb) {
                                    $bool = false;
                                }
                            }
                        } else {
                            $bool = false;
                        }
                    } else {
                        $dspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                            $q->where('kode', 'not like', '%Jasa%');
                        })->where('pesanan_id', $poid)->get();
                        if (count($dspb) > 0) {
                            $deldspb = DetailPesananPart::whereHas('Sparepart', function ($q) {
                                $q->where('kode', 'not like', '%Jasa%');
                            })->where('pesanan_id', $poid)->delete();
                            if (!$deldspb) {
                                $bool = false;
                            }
                        }
                    }
                } else {
                    $bool = false;
                }
            } else {
                $bool = false;
            }
        } else {
            $bool = false;
        }

        if ($bool == true) {
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } else if ($bool == false) {
            return response()->json([
                'message' => 'Cek Form Kembali',
            ], 500);
        }
    }

    public function update_penjualan_pesanan(Request $request, $id, $jenis)
    {
        $bool = true;
        if ($jenis == "ekatalog") {
            $po = Pesanan::find($id);
            $ekat = Ekatalog::find($po->Ekatalog->id);
            $ekat->no_urut = $request->no_urut;
            $u = $ekat->save();
            if ($u) {
                if (!empty($request->no_do) && !empty($request->tgl_do)) {
                    $po->no_do = $request->no_do;
                    $po->tgl_do = $request->tgl_do;
                    $po->ket = $request->keterangan;
                    $pou = $po->save();
                    if (!$pou) {
                        $bool = false;
                    }
                } else if (empty($request->no_do) && empty($request->tgl_do)) {
                    $po->no_do = "";
                    $po->tgl_do = NULL;
                    $po->ket = $request->keterangan;
                    $pou = $po->save();
                    $bool = true;
                } else {
                    $bool = false;
                }
            } else {
                $bool = false;
            }
        } else {
            $po = Pesanan::find($id);
            if (!empty($request->no_do) && !empty($request->tgl_do)) {
                $po->no_do = $request->no_do;
                $po->tgl_do = $request->tgl_do;
                $po->ket = $request->keterangan;
                $pou = $po->save();

                if (!$pou) {
                    $bool = false;
                }
            } else if (empty($request->no_do) && empty($request->tgl_do)) {
                $po->no_do = "";
                $po->tgl_do = NULL;
                $po->ket = $request->keterangan;
                $pou = $po->save();
                $bool = true;
            } else {
                $bool = false;
            }
        }

        if ($bool == true) {
            return response()->json(['data' => 'success']);
        } else if ($bool == false) {
            return response()->json(['data' => 'error']);
        }
    }

    //Delete
    public function delete_ekatalog($id)
    {
        $e = Ekatalog::find($id);
        $poid = $e->pesanan_id;

        $bool = true;
        $cdpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
            $q->where('pesanan_id', $poid);
        })->get();
        if (count($cdpp) > 0) {
            $dpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->delete();
            if (!$dpp) {
                $bool = false;
            }
        }

        $cdp = DetailPesanan::where('pesanan_id', $poid)->get();
        if (count($cdp) > 0) {
            $dp = DetailPesanan::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }

        if ($bool == true) {

            $d = $e->delete();
            if ($d) {
                if (!empty($poid)) {
                    $p = Pesanan::where('id', $poid)->delete();
                    if ($p) {
                        return response()->json(['data' => 'success']);
                    } else if (!$p) {
                        return response()->json(['data' => 'error']);
                    }
                } else {
                    return response()->json(['data' => 'success']);
                }
            } else {
                return response()->json(['data' => 'error']);
            }
        } else {
            return response()->json(['data' => 'error']);
        }
    }

    public function delete_spa($id)
    {
        $e = Spa::find($id);
        $poid = $e->pesanan_id;

        $bool = true;

        $cdpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
            $q->where('pesanan_id', $poid);
        })->get();
        if (count($cdpp) > 0) {
            $dpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->delete();
            if (!$dpp) {
                $bool = false;
            }
        }

        $cdp = DetailPesanan::where('pesanan_id', $poid)->get();
        if (count($cdp) > 0) {
            $dp = DetailPesanan::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }

        $cdppt = DetailPesananPart::where('pesanan_id', $poid)->get();
        if (count($cdppt) > 0) {
            $dp = DetailPesananPart::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }
        if ($bool == true) {
            $d = $e->delete();
            if ($d) {
                if (!empty($poid)) {
                    $p = Pesanan::where('id', $poid)->delete();
                    if ($p) {
                        return response()->json(['data' => 'success']);
                    } else if (!$p) {
                        return response()->json(['data' => 'error']);
                    }
                } else {
                    return response()->json(['data' => 'success']);
                }
            } else {
                return response()->json(['data' => 'error']);
            }
        } else {
            return response()->json(['data' => 'error']);
        }
    }

    public function delete_spb($id)
    {
        $e = Spb::find($id);
        $poid = $e->pesanan_id;

        $bool = true;

        $cdpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
            $q->where('pesanan_id', $poid);
        })->get();
        if (count($cdpp) > 0) {
            $dpp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($poid) {
                $q->where('pesanan_id', $poid);
            })->delete();
            if (!$dpp) {
                $bool = false;
            }
        }

        $cdp = DetailPesanan::where('pesanan_id', $poid)->get();
        if (count($cdp) > 0) {
            $dp = DetailPesanan::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }

        $cdppt = DetailPesananPart::where('pesanan_id', $poid)->get();
        if (count($cdppt) > 0) {
            $dp = DetailPesananPart::where('pesanan_id', $poid)->delete();
            if (!$dp) {
                $bool = false;
            }
        }
        if ($bool == true) {
            $d = $e->delete();
            if ($d) {
                if (!empty($poid)) {
                    $p = Pesanan::where('id', $poid)->delete();
                    if ($p) {
                        return response()->json(['data' => 'success']);
                    } else if (!$p) {
                        return response()->json(['data' => 'error']);
                    }
                } else {
                    return response()->json(['data' => 'success']);
                }
            } else {
                return response()->json(['data' => 'error']);
            }
        } else {
            return response()->json(['data' => 'error']);
        }
    }

    public function delete_detail_ekatalog($id)
    {
        $detail_ekatalog = DetailEkatalog::findOrFail($id);
        $detail_ekatalog->delete();
    }

    public function delete_detail_spa($id)
    {
        $detail_spa = DetailSpa::findOrFail($id);
        $detail_spa->delete();
    }

    public function delete_detail_spb($id)
    {
        $detail_spb = DetailSpb::findOrFail($id);
        $detail_spb->delete();
    }

    public function cancel_penjualan($id, $jenis)
    {
        $data = "";
        if ($jenis == "EKAT") {
            $data = Ekatalog::where('id', $id)->with('Pesanan')->first();
        } else if ($jenis == "SPA") {
            $data = Spa::where('id', $id)->with('Pesanan')->first();
        } else if ($jenis == "SPB") {
            $data = Spb::where('id', $id)->with('Pesanan')->first();
        }
        return view('page.penjualan.penjualan.cancel', ['id' => $id, 'data' => $data]);
    }

    public function cancel_spa_spb(Request $r)
    {
        if ($r->jenis == "spa") {
            $spa = Spa::find($r->id);
            $spa->log = "batal";
            $u = $spa->save();
            if ($u) {
                return response()->json(['data' => 'success']);
            } else if (!$u) {
                return response()->json(['data' => 'error']);
            }
        }
    }
    // public function delete_ekatalog($id)
    // {
    //     $ekatalog = Ekatalog::findOrFail($id);
    //     $ekatalog->delete();
    // }
    // public function delete_spa($id)
    // {
    //     $ekatalog = Spa::findOrFail($id);
    //     $ekatalog->delete();
    // }
    // public function delete_spb($id)
    // {
    //     $ekatalog = Spb::findOrFail($id);
    //     $ekatalog->delete();
    // }

    // Laporan
    public function  get_data_laporan_penjualan($penjualan, $distributor, $tanggal_awal, $tanggal_akhir)
    {
        $x = explode(',', $penjualan);
        if ($distributor == 'semua') {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spa)->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($x == ['ekatalog', 'spa']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['ekatalog', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['spa', 'spb']) {

                $Spa  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();

                $prd = $Spa->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($penjualan == 'ekatalog') {
                $data  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get();
            } else if ($penjualan == 'spa') {
                $prd  = collect(DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $part =  collect(DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $prd->merge($part);
            } else if ($penjualan == 'spb') {
                $prd  = collect(DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $part =  collect(DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir]);
                })->get());
                $data = $prd->merge($part);
            }
        } else {
            if ($x == ['ekatalog', 'spa', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();

                $prd = $Ekatalog->merge($Spa)->merge($Spb);
                $part = $Part_Spa->merge($Part_Spb);
                $data = $prd->merge($part);
            } else if ($x == ['ekatalog', 'spa']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $prd = $Ekatalog->merge($Spa);
                $data = $prd->merge($Part);
            } else if ($x == ['ekatalog', 'spb']) {
                $Ekatalog  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $prd = $Ekatalog->merge($Spb);
                $data = $prd->merge($Part);
            } else if ($x == ['spa', 'spb']) {
                $Spa  = DetailPesanan::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Spb  = DetailPesanan::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spa  = DetailPesananPart::whereHas('Pesanan.SPA', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part_Spb  = DetailPesananPart::whereHas('Pesanan.SPB', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $part = $Part_Spa->merge($Part_Spb);
                $prd = $Spa->merge($Spb);
                $data = $part->merge($prd);
            } else if ($penjualan == 'ekatalog') {
                $data = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
            } else if ($penjualan == 'spa') {
                $Spa  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spa', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $data = $Spa->merge($Part);
            } else if ($penjualan == 'spb') {
                $Spb  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $Part  = DetailPesananPart::whereHas('Pesanan.Spb', function ($q) use ($distributor, $tanggal_awal, $tanggal_akhir) {
                    $q->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                        ->where('customer_id', $distributor);
                })->get();
                $data = $Spb->merge($Part);
            }
        }
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('no_paket', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->no_paket;
                } else {
                    return '';
                }
            })
            ->addColumn('no_so', function ($data) {
                return $data->Pesanan->so;
            })
            ->addColumn('no_po', function ($data) {
                return $data->Pesanan->no_po;
            })
            ->addColumn('no_sj', function () {
                return '-';
            })
            ->addColumn('nama_customer', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->Customer->nama;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->Customer->nama;
                } else {
                    return $data->Pesanan->Spb->Customer->nama;
                }
            })
            ->addColumn('tgl_kontrak', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->tgl_kontrak;
                } else {
                    return '';
                }
            })
            ->addColumn('tgl_kirim', function () {
                return '-';
            })
            ->addColumn('tgl_po', function ($data) {
                return $data->Pesanan->tgl_po;
            })
            ->addColumn('instansi', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->instansi;
                } else {
                    return '-';
                }
            })
            ->addColumn('satuan', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->satuan;
                } else {
                    return '-';
                }
            })
            ->addColumn('nama_produk', function ($data) {
                if ($data->PenjualanProduk) {
                    return $data->penjualanproduk->nama;
                } else {
                    return $data->Sparepart->nama;
                }
            })
            ->addColumn('no_seri', function () {
                return '-';
            })
            ->addColumn('jumlah', function ($data) {
                return $data->jumlah;
            })
            ->addColumn('harga', function ($data) {
                return $data->harga;
            })
            ->addColumn('subtotal', function ($data) {
                return $data->jumlah * $data->harga;
            })
            ->addColumn('total', function ($data) {
                return $data->jumlah * $data->harga;
            })
            ->addColumn('log', function () {
                return '-';
            })
            ->addColumn('ket', function ($data) {
                $name = explode('/', $data->pesanan->so);
                if ($name[1] == 'EKAT') {
                    return $data->Pesanan->Ekatalog->ket;
                } elseif ($name[1] == 'SPA') {
                    return $data->Pesanan->Spa->ket;
                } else {
                    return $data->Pesanan->Spb->ket;
                }
            })
            ->addColumn('kosong', function () {
                return '';
            })
            ->make(true);
    }

    // public function get_data_laporan_penjualan($penjualan, $distributor, $tanggal_awal, $tanggal_akhir){
    //     $x = explode(',', $penjualan);
    //     $data = [];
    //     if($distributor == 'semua'){
    //         $ekat = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->Has('Ekatalog')->get();
    //         $ekatnopo = Pesanan::whereNull('no_po')->Has('Ekatalog')->get();
    //         $spa = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->Has('Spa')->get();
    //         $spanopo = Pesanan::whereNull('no_po')->Has('Spa')->get();
    //         $spb = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->Has('Spb')->get();
    //         $spbnopo = Pesanan::whereNull('no_po')->Has('Spb')->get();
    //         if($x == ['ekatalog']){
    //             $data = $ekat->merge($ekatnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spa']){
    //             $data = $spa->merge($spanopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spb']){
    //             $data = $spb->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spa']){
    //             $data = $ekat->merge($spa)->merge($ekatnopo)->merge($spanopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spb']){
    //             $data = $ekat->merge($spb)->merge($ekatnopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spa', 'spb']){
    //             $data = $spa->merge($spb)->merge($spanopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spa', 'spb']){
    //             $data = $data = $ekat->merge($spa)->merge($spb)->merge($ekatnopo)->merge($spanopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //     }
    //     else{
    //         $ekat = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->whereHas('Ekatalog', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $ekatnopo = Pesanan::whereNull('no_po')->whereHas('Ekatalog', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $spa = $data = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->whereHas('Spa', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $spanopo = Pesanan::whereNull('no_po')->whereHas('Spa', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $spb = Pesanan::whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])->whereHas('Spb', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         $spbnopo = Pesanan::whereNull('no_po')->whereHas('Spb', function($q) use($distributor){
    //             $q->where('customer_id', $distributor);
    //         })->get();
    //         if($x == ['ekatalog']){
    //             $data = $ekat->merge($ekatnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spa']){
    //             $data = $spa->merge($spanopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spb']){
    //             $data = $spb->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spa']){
    //             $data = $ekat->merge($spa)->merge($ekatnopo)->merge($spanopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spb']){
    //             $data = $ekat->merge($spb)->merge($ekatnopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['spa', 'spb']){
    //             $data = $spa->merge($spb)->merge($spanopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //         else if($x == ['ekatalog', 'spa', 'spb']){
    //             $data = $data = $ekat->merge($spa)->merge($spb)->merge($ekatnopo)->merge($spanopo)->merge($spbnopo)->sortBy('created_at');
    //         }
    //     }

    //     return datatables()->of($data)
    //         ->addIndexColumn()
    //         ->addColumn('so', function ($data) {
    //             return $data->so;
    //         })
    //         ->addColumn('no_paket', function ($data) {
    //             // if($data->so){
    //             //     $name = explode('/', $data->so);
    //                 if ($data->Ekatalog) {
    //                     return $data->Ekatalog->no_paket;
    //                 } else {
    //                     return '';
    //                 }
    //             // }
    //         })
    //         ->addColumn('no_po', function ($data) {
    //             if($data->no_po){
    //                 return '<div>'.$data->no_po.'</div><small>Tanggal PO '.Carbon::createFromFormat('Y-m-d', $data->tgl_po)->format('d-m-Y').'</small>';
    //             }
    //         })
    //         ->addColumn('no_sj', function () {
    //             return '-';
    //         })
    //         ->addColumn('nama_customer', function ($data) {
    //             // if($data->so){
    //                 // $name = explode('/', $data->so);
    //                 if ($data->Ekatalog) {
    //                     return $data->Ekatalog->Customer->nama;
    //                 } elseif ($data->Spa) {
    //                     return $data->Spa->Customer->nama;
    //                 } else {
    //                     return $data->Spb->Customer->nama;
    //                 }
    //             // }
    //         })
    //         ->addColumn('tgl_kontrak', function ($data) {
    //             if($data->so){
    //                 $name = explode('/', $data->so);
    //                 if ($name[1] == 'EKAT') {
    //                     if (isset($data->Ekatalog->tgl_kontrak)) {
    //                         $tgl_sekarang = Carbon::now()->format('Y-m-d');
    //                         $tgl_parameter = $data->Ekatalog->tgl_kontrak;

    //                         if (isset($data->Pesanan->so)) {
    //                             if ($data->Pesanan->getJumlahPesanan() == $data->Pesanan->getJumlahKirim()) {
    //                                 return Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y');
    //                             } else {
    //                                 if ($tgl_sekarang < $tgl_parameter) {
    //                                     $to = Carbon::now();
    //                                     $from = $data->Ekatalog->tgl_kontrak;
    //                                     $hari = $to->diffInDays($from);
    //                                     if ($hari > 7) {
    //                                         return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                         <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                                     } else if ($hari > 0 && $hari <= 7) {
    //                                         return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                         <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                                     } else {
    //                                         return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                         <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                                     }
    //                                 } else if ($tgl_sekarang == $tgl_parameter) {
    //                                     return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                                 } else {
    //                                     $to = Carbon::now();
    //                                     $from = $data->Ekatalog->tgl_kontrak;
    //                                     $hari = $to->diffInDays($from);
    //                                     return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
    //                                 }
    //                             }
    //                         } else {
    //                             if ($tgl_sekarang < $tgl_parameter) {
    //                                 $to = Carbon::now();
    //                                 $from = $data->Ekatalog->tgl_kontrak;
    //                                 $hari = $to->diffInDays($from);
    //                                 if ($hari > 7) {
    //                                     return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                                 } else if ($hari > 0 && $hari <= 7) {
    //                                     return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
    //                                 } else {
    //                                     return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                     <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                                 }
    //                             } else if ($tgl_sekarang == $tgl_parameter) {
    //                                 return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                 <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
    //                             } else {
    //                                 $to = Carbon::now();
    //                                 $from = $data->Ekatalog->tgl_kontrak;
    //                                 $hari = $to->diffInDays($from);
    //                                 return '<div id="urgent">' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
    //                                 <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Melebihi ' . $hari . ' Hari</div>';
    //                             }
    //                         }
    //                     } else {
    //                         return '-';
    //                     }
    //                 } else {
    //                     return '';
    //                 }
    //             }
    //         })
    //         ->addColumn('tgl_kirim', function () {
    //             return '-';
    //         })
    //         ->addColumn('tgl_po', function ($data) {
    //             if($data->tgl_po){
    //                 return $data->tgl_po;
    //             }
    //         })
    //         ->addColumn('instansi', function ($data) {
    //             // if($data->so){
    //             //     $name = explode('/', $data->so);
    //                 if ($data->Ekatalog) {
    //                     return '<div>'.$data->Ekatalog->instansi.'</div><small>'.$data->Ekatalog->satuan.'</small>';
    //                 } else {
    //                     return '-';
    //                 }
    //             // }
    //         })
    //         ->addColumn('satuan', function ($data) {
    //             // $name = explode('/', $data->so);
    //             // if ($name[1] == 'EKAT') {
    //             //     return $data->Ekatalog->satuan;
    //             // } else {
    //             //     return '-';
    //             // }
    //         })
    //         ->addColumn('nama_produk', function ($data) {
    //         //     if ($data->PenjualanProduk) {
    //         //         return $data->penjualanproduk->nama;
    //         //     } else {
    //         //         return $data->Sparepart->nama;
    //         //     }
    //         })
    //         ->addColumn('no_seri', function () {
    //             return '-';
    //         })
    //         ->addColumn('jumlah', function ($data) {
    //             return '-';
    //         })
    //         ->addColumn('harga', function ($data) {
    //             return '-';
    //         })
    //         ->addColumn('subtotal', function ($data) {
    //             return '-';
    //         })
    //         ->addColumn('total', function ($data) {
    //             return '-';
    //         })
    //         ->addColumn('log', function ($data) {
    //             $datas = "";
    //             if ($data->log != "batal") {
    //                 if (!empty($data->log_id)) {
    //                     if ($data->State->nama == "Penjualan") {
    //                         $datas .= '<span class="red-text badge">';
    //                     } else if ($data->State->nama == "PO") {
    //                         $datas .= '<span class="purple-text badge">';
    //                     } else if ($data->State->nama == "Gudang") {
    //                         $datas .= '<span class="orange-text badge">';
    //                     } else if ($data->State->nama == "QC") {
    //                         $datas .= '<span class="yellow-text badge">';
    //                     } else if ($data->State->nama == "Belum Terkirim") {
    //                         $datas .= '<span class="red-text badge">';
    //                     } else if ($data->State->nama == "Terkirim Sebagian") {
    //                         $datas .= '<span class="blue-text badge">';
    //                     } else if ($data->State->nama == "Kirim") {
    //                         $datas .= '<span class="green-text badge">';
    //                     }

    //                     $datas .= ucfirst($data->State->nama) . '</span>';
    //                 } else {
    //                     $datas .= '<small class="text-muted"><i>Tidak Tersedia</i></small>';
    //                 }
    //             } else {
    //                 $datas .= '<span class="red-text badge">Batal</span>';
    //             }
    //             return $datas;
    //         })
    //         ->addColumn('ket', function ($data) {
    //             if($data->so){
    //                 $name = explode('/', $data->so);
    //                 if ($name[1] == 'EKAT') {
    //                     return $data->Ekatalog->ket;
    //                 } elseif ($name[1] == 'SPA') {
    //                     return $data->Spa->ket;
    //                 } else {
    //                     return $data->Spb->ket;
    //                 }
    //             }
    //         })
    //         ->addColumn('kosong', function () {
    //             return '';
    //         })
    //         ->rawColumns(['tgl_kontrak', 'log', 'no_po', 'instansi'])
    //         ->make(true);
    // }

    // public function laporan(Request $request)
    // {
    //     return Excel::download(new LaporanPenjualan($request->customer_id ?? '', $request->penjualan ?? '', $request->tanggal_mulai  ?? '', $request->tanggal_akhir ?? ''), 'laporan_penjualan.xlsx');
    // }

    //Chart
    public function chart_penjualan()
    {
        $now = Carbon::now();
        $tgl_awal = $now->year . "-01-01";
        $tgl_akhir = $now->year . "-12-31";
        //EKAT
        $ekatalog = Pesanan::whereHas('Ekatalog',function ($q){
            $q->where('status','sepakat');
        })
            ->whereBetween('tgl_po', [$tgl_awal, $tgl_akhir])
            ->select('Pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });

        $ekatalog_count = [];
        $ekatalog_graph = [];

        foreach ($ekatalog as $key => $value) {
            $ekatalog_count[(int)  $key] = count($value);
        }


        for ($i = 1; $i <= 12; $i++) {
            if (!empty($ekatalog_count[$i])) {
                $ekatalog_graph[$i]['count'] = $ekatalog_count[$i];
            } else {
                $ekatalog_graph[$i]['count'] = 0;
            }
            $ekatalog_graph[$i];
        }

        //SPA
        $spa = Pesanan::Has('Spa')
            ->whereBetween('tgl_po', [$tgl_awal, $tgl_akhir])
            ->select('Pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });


        $spa_count = [];
        $spa_graph = [];
        foreach ($spa as $key => $value) {
            $spa_count[(int)  $key] = count($value);
        }


        for ($i = 1; $i <= 12; $i++) {
            if (!empty($spa_count[$i])) {
                $spa_graph[$i]['count'] = $spa_count[$i];
            } else {
                $spa_graph[$i]['count'] = 0;
            }
            $spa_graph[$i];
        }


        //SPB
        $spb = Pesanan::Has('Spb')
            ->whereBetween('tgl_po', [$tgl_awal, $tgl_akhir])
            ->select('Pesanan.tgl_po')
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->tgl_po)->format('m');
            });


        $spb_count = [];
        $spb_graph = [];

        foreach ($spb as $key => $value) {
            $spb_count[(int)  $key] = count($value);
        }


        for ($i = 1; $i <= 12; $i++) {
            if (!empty($spb_count[$i])) {
                $spb_graph[$i]['count'] = $spb_count[$i];
            } else {
                $spb_graph[$i]['count'] = 0;
            }
            $spb_graph[$i];
        }

        return response()->json(compact('ekatalog_graph', 'spa_graph', 'spb_graph'));
    }

    // //Dashboard
    // public function dashboard()
    // {
    //     $penj = Pesanan::whereNull('so')->where('log_id', '7')->count();
    //     $gudang = 0;
    //     $qc = 0;
    //     $log = 0;
    //     $dc = 0;
    //     $pes = Pesanan::whereNotIn('log_id', ['7', '10'])->get();
    //     foreach ($pes as $i) {
    //         if (isset($i->DetailPesanan)) {
    //             if ($i->getJumlahSeri() < $i->getJumlahPesanan()) {
    //                 $gudang = $gudang + 1;
    //             }
    //         }

    //         if (isset($i->DetailPesanan)) {
    //             if ($i->getJumlahCek() < $i->getJumlahPesanan()) {
    //                 $qc = $qc + 1;
    //             }
    //         }

    //         if (isset($i->DetailPesanan)) {
    //             if ($i->getJumlahKirim() < $i->getJumlahPesanan()) {
    //                 $log = $log + 1;
    //             }
    //         }

    //         if (isset($i->DetailPesananPart)) {
    //             if ($i->getJumlahKirimPart() < $i->getJumlahPesananPart()) {
    //                 $log = $log + 1;
    //             }
    //         }

    //         if (isset($i->DetailPesananPart)) {
    //             if ($i->getJumlahCoo() < $i->getJumlahPesanan()) {
    //                 $dc = $dc + 1;
    //             }
    //         }
    //     }


    //     // $belum_so = Pesanan::whereNull('so')->get()->count();
    //     // $so_belum_gudang = Pesanan::DoesntHave('TFProduksi')->get()->count();
    //     // $so_belum_qc = Pesanan::DoesntHave('DetailPesanan.DetailPesananPRoduk.Noseridetailpesanan')->get()->count();
    //     // $so_belum_logistik = Pesanan::DoesntHave('DetailPesanan.DetailPesananProduk.DetailLogistik.Logistik')->get()->count();
    //     return view('page.penjualan.dashboard', ['belum_so' => $penj, 'so_belum_gudang' => $gudang, 'so_belum_qc' => $qc, 'so_belum_logistik' => $log]);
    // }

    public function dashboard()
    {
        $penj = Ekatalog::whereHas('Pesanan', function ($q) {
            $q->whereNull('so')->where('log_id', '7')->whereNotIn('log_id', ['20', '10']);
        })->where('status', 'sepakat')->count();

        $gudang = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->groupBy('pesanan.id')
                ->havingRaw('NOT EXISTS(select *
                    from t_gbj_noseri
                    left join t_gbj_detail on t_gbj_detail.id = t_gbj_noseri.t_gbj_detail_id
                    left join t_gbj on t_gbj.id = t_gbj_detail.t_gbj_id
                    where t_gbj.pesanan_id = pesanan.id)');
        })->whereNotIn('log_id', ['7', '20', '10'])->count();
        //QC


        $qc = Pesanan::whereNotIn('log_id', ['7', '10'])->addSelect([

            'tgl_kontrak' => function ($q) {
                $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 28 DAY))')
                    ->from('ekatalog')
                    ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                    ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'ctfprd' => function ($q) {
                $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                    ->from('t_gbj_noseri')
                    ->leftJoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                    ->leftJoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                    ->whereColumn('t_gbj.pesanan_id', 'pesanan.id');
            },
            'cqcprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_detail_pesanan.id), 0)')
                    ->from('noseri_detail_pesanan')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->where('noseri_detail_pesanan.status', 'ok')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id');
            },
            'ctfpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah), 0)')
                    ->from('detail_pesanan_part')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },
            'cqcpart' => function ($q) {
                $q->selectRaw('coalesce(sum(outgoing_pesanan_part.jumlah_ok), 0)')
                    ->from('outgoing_pesanan_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'outgoing_pesanan_part.detail_pesanan_part_id')
                    ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->where('detail_pesanan_part.pesanan_id', 'pesanan.id');
            },  'clogprd' => function ($q) {
                $q->selectRaw('coalesce(count(noseri_logistik.id), 0)')
                    ->from('noseri_logistik')
                    ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                    ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                    ->leftJoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                    ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                    ->limit(1);
            },
            'clogpart' => function ($q) {
                $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                    ->from('detail_logistik_part')
                    ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                    ->join('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                    ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                    ->whereColumn('detail_pesanan_part.pesanan_id', 'pesanan.id')
                    ->limit(1);
            }

        ])
            ->with(['ekatalog.customer.provinsi', 'spa.customer.provinsi', 'spb.customer.provinsi'])
            ->havingRaw('(cqcprd < ctfprd AND ctfprd > 0) OR (cqcpart < ctfpart AND ctfpart > 0)')
            ->orderBy('tgl_kontrak', 'asc')
            ->count();

        //LOGISTIK
        $logprd = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan', 'detail_pesanan.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->leftJoin('noseri_detail_pesanan', 'noseri_detail_pesanan.detail_pesanan_produk_id', '=', 'detail_pesanan_produk.id')
                ->groupBy('pesanan.id')
                ->havingRaw('count(noseri_detail_pesanan.id) > (select count(noseri_logistik.id)
            from noseri_logistik
            left join noseri_detail_pesanan on noseri_detail_pesanan.id = noseri_logistik.noseri_detail_pesanan_id
            left join detail_pesanan_produk on detail_pesanan_produk.id = noseri_detail_pesanan.detail_pesanan_produk_id
            left join detail_pesanan on detail_pesanan.id = detail_pesanan_produk.detail_pesanan_id
            where detail_pesanan.pesanan_id = pesanan.id)');
        })->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '9', '20', '10']);

        $logpart = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('outgoing_pesanan_part', 'outgoing_pesanan_part.detail_pesanan_part_id', '=', 'detail_pesanan_part.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->whereRaw('m_sparepart.kode NOT LIKE "%JASA%"')
                ->groupBy('pesanan.id')
                ->havingRaw("(sum(outgoing_pesanan_part.jumlah_ok) > (
                    select sum(detail_logistik_part.jumlah)
                    from detail_logistik_part
                    left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                    left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                    where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS
                       (select * from detail_logistik_part
                        left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode NOT LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id)) AND SUM(outgoing_pesanan_part.jumlah_ok) > 0");
        })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '20', '10']);

        $logjasa = Pesanan::whereIn('id', function ($q) {
            $q->select('pesanan.id')
                ->from('pesanan')
                ->leftJoin('detail_pesanan_part', 'detail_pesanan_part.pesanan_id', '=', 'pesanan.id')
                ->leftJoin('m_sparepart', 'm_sparepart.id', '=', 'detail_pesanan_part.m_sparepart_id')
                ->where('m_sparepart.kode', 'LIKE', '%JASA%')
                ->havingRaw("sum(detail_pesanan_part.jumlah) > (
                        select sum(detail_pesanan_part.jumlah)
                        from detail_logistik_part
                        left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                        left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                        where detail_pesanan_part.pesanan_id = pesanan.id) OR NOT EXISTS(
                            select * from detail_logistik_part
                            left join detail_pesanan_part on detail_pesanan_part.id = detail_logistik_part.detail_pesanan_part_id
                            left join m_sparepart on m_sparepart.id = detail_pesanan_part.m_sparepart_id AND m_sparepart.kode LIKE '%JASA%'
                            where detail_pesanan_part.pesanan_id = pesanan.id)")
                ->groupBy('pesanan.id');
        })->with(['Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])->whereNotIn('log_id', ['7', '20', '10'])->union($logprd)->union($logpart)->orderBy('id', 'desc')->count();

        $log = $logjasa;
        return view('page.penjualan.dashboard', ['belum_so' => $penj, 'so_belum_gudang' => $gudang, 'so_belum_qc' => $qc, 'so_belum_logistik' => $log]);
    }
    //Another
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
    function getMonth()
    {
        $m = Carbon::now()->format('m');
        return $this->toRomawi($m);
    }
    function getYear()
    {
        return  Carbon::now()->format('Y');
    }

    function createSO($value)
    {

        $check = Pesanan::whereYear('created_at', $this->getYear())->where('so', 'like', '%' . $this->getYear() . '%')->get('so');
        $max_number = 0;
        foreach ($check as $c) {
            if ($c->so == NULL) {
                $no = 'SO/' . $value . '/' . $this->getMonth() . '/' . $this->getYear() . '/1';
            } else {
                $get = explode('/', $c->so);
                if ($get[1] == $value) {
                    if ($get[4] > $max_number)
                        $max_number = $get[4];
                }
            }
        }
        $no = 'SO/' . $value . '/' . $this->getMonth() . '/' . $this->getYear() . '/' . ($max_number + 1) . '';
        return $no;
    }
    public function check_no_paket($id, $val)
    {
        if ($id != "0") {
            $e = Ekatalog::where('no_paket', 'AK1-' . $val)->whereNotIn('id', [$id])->count();
            return $e;
        } else {
            $e = Ekatalog::where('no_paket', 'AK1-' . $val)->count();
            return $e;
        }
    }

    public function check_no_urut($id, $val)
    {
        if ($id != "0") {
            $e = Ekatalog::where('no_urut', $val)->whereNotIn('id', [$id])->whereYear('created_at', $this->getYear())->count();
            return $e;
        } else {
            $e = Ekatalog::where('no_urut',  $val)->whereYear('created_at', $this->getYear())->count();
            return $e;
        }
    }

    public function check_variasi_jumlah($id)
    {
        // $gbj = GudangBarangJadi::find($id);
        // $jumlah_ekatalog = $this->get_count_ekatalog($id, $gbj->produk_id, "sepakat") + $this->get_count_ekatalog($id, $gbj->produk_id, "negosiasi");
        // $jumlah_po = $this->get_count_spa_po($id, $gbj->produk_id);
        // $jumlah = $gbj->stok - ($jumlah_ekatalog + $jumlah_po);
        // return $jumlah;

        $data = GudangBarangJadi::addSelect(['count_barang' => function ($query) {
            $query->selectRaw('count(noseri_barang_jadi.id)')
                ->from('noseri_barang_jadi')
                ->where(['noseri_barang_jadi.is_ready' => 0])
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
        },  'count_ekat_nego' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id in ("7") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
                ->limit(1);
        },  'count_ekat_draft' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id in ("7")  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                ->limit(1);
        },  'count_ekat_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                ->limit(1);
        },  'count_spa_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                ->limit(1);
        },  'count_spb_po' => function ($query) {
            $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                ->from('detail_pesanan')
                ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                ->whereRaw('pesanan.log_id not in ("7", "10") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                ->limit(1);
        },])
            ->find($id);

        $jumlahdiminta = intval($data->count_ekat_sepakat) + intval($data->count_ekat_nego) + intval($data->count_ekat_draft) + intval($data->count_ekat_po) + intval($data->count_spa_po) + intval($data->count_spb_po);
        $jumlahstok = intval($data->count_barang);
        return $jumlahstok - $jumlahdiminta;
    }

    public function check_alamat(Request $request)
    {
        $data = Ekatalog::where('alamat', 'LIKE', '%' . $request->input('term', '') . '%')->groupby('alamat')->get();
        echo json_encode($data);
    }

    public function get_count_ekatalog($id, $produk_id, $status)
    {
        $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan.Ekatalog', function ($q) use ($status) {
            $q->where('status', '=', $status);
        })->whereHas('Pesanan', function ($q) {
            $q->whereIn('log_id', ['7', '9']);
        })->get();
        $jumlah = 0;
        foreach ($res as $a) {
            foreach ($a->PenjualanProduk->Produk as $b) {
                if ($b->id == $produk_id) {
                    $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                }
            }
        }
        return $jumlah;
    }

    public function get_count_spa_po($id, $produk_id)
    {
        $res = DetailPesanan::whereHas('DetailPesananProduk', function ($q) use ($id) {
            $q->where('gudang_barang_jadi_id', $id);
        })->whereHas('Pesanan', function ($q) {
            $q->whereIn('log_id', ['7', '9']);
        })->doesntHave('Pesanan.Ekatalog')->get();
        $jumlah = 0;
        foreach ($res as $a) {
            foreach ($a->PenjualanProduk->Produk as $b) {
                if ($b->id == $produk_id) {
                    $jumlah = $jumlah + ($a->jumlah * $b->pivot->jumlah);
                }
            }
        }
        return $jumlah;
    }


    public function export_laporan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan)
    {
        $x = explode(',', $jenis);
        $waktu = Carbon::now();

        if ($x == ['ekatalog', 'spa', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan Semua ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['ekatalog', 'spa']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan Ekatalog dan SPA ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['ekatalog', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan Ekatalog dan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($x == ['spa', 'spb']) {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan SPA dan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'ekatalog') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan Ekatalog ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'spa') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan SPA ' . $waktu->toDateTimeString() . '.xlsx');
        } else if ($jenis == 'spb') {
            return Excel::download(new LaporanPenjualan($jenis, $dsb, $tgl_awal, $tgl_akhir, $seri, $tampilan), 'Laporan Penjualan SPB ' . $waktu->toDateTimeString() . '.xlsx');
        }
    }

    public function manager_penjualan_show()
    {
        return view('manager.penjualan.so.show');
    }

    public function manager_penjualan_show_data($jenis, $value)
    {
        if ($jenis == "ekatalog") {
            $x = explode(',', $value);
            $data = "";

            if ($value == 'semua') {
                $data  = Ekatalog::with(['Pesanan.State',  'Customer', 'Provinsi'])->addSelect([

                    'tgl_kontrak_custom' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog as e')
                            ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                            ->whereColumn('e.id', 'ekatalog.id')
                            ->limit(1);
                    },
                    'cseri' => function ($q) {
                        $q->selectRaw('count(noseri_logistik.id)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                            ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlah' => function ($q) {
                        $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    }

                ])->orderByRaw('CONVERT(no_urut, SIGNED) desc')->get();
            } else {
                $data  = Ekatalog::with(['Pesanan.State',  'Customer'])->addSelect([

                    'tgl_kontrak_custom' => function ($q) {
                        $q->selectRaw('IF(provinsi.status = "2", SUBDATE(e.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(e.tgl_kontrak, INTERVAL 21 DAY))')
                            ->from('ekatalog as e')
                            ->join('provinsi', 'provinsi.id', '=', 'e.provinsi_id')
                            ->whereColumn('e.id', 'ekatalog.id')
                            ->limit(1);
                    },
                    'cseri' => function ($q) {
                        $q->selectRaw('count(noseri_logistik.id)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('gdg_barang_jadi', 'gdg_barang_jadi.id', '=', 'detail_pesanan_produk.gudang_barang_jadi_id')
                            ->leftjoin('produk', 'produk.id', '=', 'gdg_barang_jadi.produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    },
                    'cjumlah' => function ($q) {
                        $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'ekatalog.pesanan_id');
                    }

                ])->orderByRaw('CONVERT(no_urut, SIGNED) desc')->whereIN('status', $x)->get();
            }

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('no_urut', function ($data) {
                    return $data->no_urut;
                })
                ->addColumn('so', function ($data) {
                    if ($data->Pesanan) {
                        if (!empty($data->Pesanan->so)) {
                            return $data->Pesanan->so;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    if ($data->Pesanan->log_id == '7' || $data->Pesanan->log_id == '9') {
                        if (!empty($data->status)) {
                            if ($data->status == "batal") {
                                $datas .= '<span class="red-text badge">';
                            } else if ($data->status == "negosiasi") {
                                $datas .= '<span class="yellow-text badge">';
                            } else if ($data->status == "draft") {
                                $datas .= '<span class="blue-text badge">';
                            } else if ($data->status == "sepakat") {
                                $datas .= '<span class="green-text badge">';
                            }
                            $datas .= ucfirst($data->status) . '</span>';
                        }
                    } else {
                        if ($data->status == "batal") {
                            $datas = '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="EKAT" data-provinsi="">
                                <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                    <i class="fas fa-times"></i>
                                    Batal
                                </button>
                            </a>';
                        } else {
                            $hitung = floor((($data->cseri / $data->cjumlah) * 100));
                            if ($hitung > 0) {
                                $datas = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                            } else {
                                $datas = '<div class="progress">
                                    <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                            }
                        }
                    }
                    return $datas;
                })
                ->addColumn('nopo', function ($data) {
                    if ($data->Pesanan) {
                        if (!empty($data->Pesanan->no_po)) {
                            return $data->Pesanan->no_po;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->editColumn('tgl_buat', function ($data) {
                    if (!empty($data->tgl_buat)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_buat)->format('d-m-Y');
                    }
                })
                ->editColumn('tgl_edit', function ($data) {
                    if (!empty($data->tgl_edit)) {
                        return Carbon::createFromFormat('Y-m-d', $data->tgl_edit)->format('d-m-Y');
                    }
                })->editColumn('tgl_kontrak', function ($data) {

                    if ($data->tgl_kontrak_custom != "") {
                        if ($data->Pesanan->log_id != "10") {
                            $tgl_sekarang = Carbon::now();
                            $tgl_parameter = $data->tgl_kontrak_custom;
                            $hari = $tgl_sekarang->diffInDays($tgl_parameter);
                            if ($tgl_sekarang->format('Y-m-d') < $tgl_parameter) {
                                if ($hari > 7) {
                                    return  '<div> ' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                            <div><small><i class="fas fa-clock" id="info"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else if ($hari > 0 && $hari <= 7) {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                            <div><small><i class="fas fa-exclamation-circle" id="warning"></i> ' . $hari . ' Hari Lagi</small></div>';
                                } else {
                                    return  '<div>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</div>
                                            <div class="invalid-feedback d-block"><i class="fas fa-exclamation-circle"></i> Batas Kontrak Habis</div>';
                                }
                            } else {
                                return  '<div class="text-danger"><b>' . Carbon::createFromFormat('Y-m-d', $tgl_parameter)->format('d-m-Y') . '</b></div>
                                        <div class="text-danger"><small><i class="fas fa-exclamation-circle"></i> Lebih ' . $hari . ' Hari</small></div>';
                            }
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->tgl_kontrak_custom)->format('d-m-Y');
                        }
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    if (isset($data->Customer)) {
                        return $data->Customer['nama'];
                    } else {
                        return '-';
                    }
                })
                ->addColumn('button', function ($data) {
                    $return = "";
                    // if($data->Pesanan->log_id != "20"){
                    $return = '<a data-toggle="modal" data-target="ekatalog" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.ekatalog',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    // }
                    // else {
                    //     $return = '<a data-toggle="modal" data-jenis="ekatalog" class="batalmodal" data-id="' . $data->id . '" >
                    //         <button class="btn btn-outline-danger btn-sm" type="button">
                    //             <i class="fas fa-times"></i>
                    //             Batal
                    //             </button>
                    //         </a>';
                    // }
                    return $return;
                })
                ->rawColumns(['button', 'status', 'tgl_kontrak'])
                ->make(true);
        } else if ($jenis == "spa") {
            $x = explode(',', $value);
            if ($value == 'semua') {
                $data  = Spa::with(['Pesanan.State',  'Customer'])->addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    }

                ])->orderBy('id', 'DESC')->get();
            } else {
                $data  = Spa::with(['Pesanan.State',  'Customer'])->addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spa.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spa.pesanan_id');
                    }

                ])->whereHas('pesanan', function ($q) use ($x) {
                    $q->whereIN('log_id', $x);
                })->orderBy('id', 'DESC')->get();
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    if ($data->Pesanan) {
                        if (!empty($data->Pesanan->so)) {
                            return $data->Pesanan->so;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nopo', function ($data) {
                    if ($data->Pesanan) {
                        if (!empty($data->Pesanan->no_po)) {
                            return $data->Pesanan->no_po;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));

                    if ($data->log == "batal") {
                        $datas = '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPA" data-provinsi="">
                                    <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                        <i class="fas fa-times"></i>
                                        Batal
                                    </button>
                                </a>';
                    } else {
                        if ($hitung > 0) {
                            $datas = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                        } else {
                            $datas = '<div class="progress">
                                    <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                        }
                    }
                    return $datas;
                })
                ->addColumn('tglpo', function ($data) {
                    if ($data->Pesanan) {
                        if ($data->Pesanan->tgl_po == "0000-00-00" || empty($data->Pesanan->tgl_po)) {
                            return '-';
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->Customer->nama;
                })
                ->addColumn('button', function ($data) {
                    $return = "";
                    // if($data->Pesanan->log_id != "20"){
                    $return = '<a data-toggle="modal" data-target="spa" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spa',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    // }
                    // else{
                    //     $return = '<a data-toggle="modal" data-jenis="spa" class="batalmodal" data-id="' . $data->id . '" >
                    //         <button class="btn btn-outline-danger btn-sm" type="button">
                    //             <i class="fas fa-times"></i>
                    //             Batal
                    //             </button>
                    //         </a>';
                    // }
                    return $return;
                })
                ->rawColumns(['button', 'status'])
                ->make(true);
        } else if ($jenis == "spb") {
            $x = explode(',', $value);
            if ($value == 'semua') {
                $data  = Spb::with(['Pesanan.State',  'Customer'])->addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    }

                ])->orderBy('id', 'DESC')->get();
            } else {
                $data  = Spb::with(['Pesanan.State',  'Customer'])->addSelect([

                    'ckirimprd' => function ($q) {
                        $q->selectRaw('coalesce(count(noseri_logistik.id),0)')
                            ->from('noseri_logistik')
                            ->leftjoin('noseri_detail_pesanan', 'noseri_detail_pesanan.id', '=', 'noseri_logistik.noseri_detail_pesanan_id')
                            ->leftjoin('detail_pesanan_produk', 'detail_pesanan_produk.id', '=', 'noseri_detail_pesanan.detail_pesanan_produk_id')
                            ->leftjoin('detail_pesanan', 'detail_pesanan.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahprd' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah),0)')
                            ->from('detail_pesanan')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('produk', 'produk.id', '=', 'detail_penjualan_produk.produk_id')
                            ->whereColumn('detail_pesanan.pesanan_id', 'spb.pesanan_id');
                    },
                    'ckirimpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_logistik_part.jumlah),0)')
                            ->from('detail_logistik_part')
                            ->join('detail_pesanan_part', 'detail_pesanan_part.id', '=', 'detail_logistik_part.detail_pesanan_part_id')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    },
                    'cjumlahpart' => function ($q) {
                        $q->selectRaw('coalesce(sum(detail_pesanan_part.jumlah),0)')
                            ->from('detail_pesanan_part')
                            ->whereColumn('detail_pesanan_part.pesanan_id', 'spb.pesanan_id');
                    }

                ])->whereHas('pesanan', function ($q) use ($x) {
                    $q->whereIN('log_id', $x);
                })->orderBy('id', 'DESC')->get();
            }
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    if ($data->Pesanan) {
                        if (!empty($data->Pesanan->so)) {
                            return $data->Pesanan->so;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nopo', function ($data) {
                    if ($data->Pesanan) {
                        if (!empty($data->Pesanan->no_po)) {
                            return $data->Pesanan->no_po;
                        } else {
                            return '-';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status', function ($data) {
                    $datas = "";
                    $hitung = floor(((($data->ckirimprd + $data->ckirimpart) / ($data->cjumlahprd + $data->cjumlahpart)) * 100));

                    if ($data->log == "batal") {
                        $datas = '<a data-toggle="modal" data-target="#batalmodal" class="batalmodal" data-href="" data-id="' . $data->id . '" data-jenis="SPB" data-provinsi="">
                                    <button type="button" class="btn btn-sm btn-outline-danger" type="button">
                                        <i class="fas fa-times"></i>
                                        Batal
                                    </button>
                                </a>';
                    } else {
                        if ($hitung > 0) {
                            $datas = '<div class="progress">
                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="' . $hitung . '"  style="width: ' . $hitung . '%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                        } else {
                            $datas = '<div class="progress">
                                    <div class="progress-bar bg-light" role="progressbar" aria-valuenow="0"  style="width: 100%" aria-valuemin="0" aria-valuemax="100">' . $hitung . '%</div>
                                </div>
                                <small class="text-muted">Selesai</small>';
                        }
                    }
                    return $datas;
                })
                ->addColumn('tglpo', function ($data) {
                    if ($data->Pesanan) {
                        if ($data->Pesanan->tgl_po == "0000-00-00" || empty($data->Pesanan->tgl_po)) {
                            return '-';
                        } else {
                            return Carbon::createFromFormat('Y-m-d', $data->Pesanan->tgl_po)->format('d-m-Y');
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->Customer->nama;
                })
                ->addColumn('button', function ($data) {
                    $return = "";
                    // if($data->Pesanan->log_id != "20"){
                    $return = '<a data-toggle="modal" data-target="spb" class="detailmodal" data-attr="' . route('penjualan.penjualan.detail.spb',  $data->id) . '"  data-id="' . $data->id . '">
                        <button class="btn btn-outline-primary btn-sm" type="button">
                            <i class="fas fa-eye"></i>
                            Detail
                            </button>
                        </a>';
                    // }
                    // else{
                    //     $return = '<a data-toggle="modal" data-jenis="spb" class="batalmodal" data-id="' . $data->id . '" >
                    //         <button class="btn btn-outline-danger btn-sm" type="button">
                    //             <i class="fas fa-times"></i>
                    //             Batal
                    //             </button>
                    //         </a>';
                    // }
                    return $return;
                })
                ->rawColumns(['button', 'status'])
                ->make(true);
        }
    }

    public function store_ekat_emindo(Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(),  [
            'no_paket' => 'unique:ekatalog,no_paket'
        ]);
        if ($validator->fails()) {
            if ($request->provinsi != '') {
                $provinsi = Provinsi::where('nama', 'like', '%' . $request->provinsi . '%')->first();
                $p = $provinsi->id;
            } else {
                $p = NULL;
            }


            $e = Ekatalog::where('no_paket', $request->no_paket)->first();
            $data = Ekatalog::find($e->id);


            if ($data->customer_id != 213) {
                $data->customer_id = 213;
                $data->save();
            }

            if ($data->provinsi_id == NULL) {
                $data->provinsi_id = $p;
                $data->save();
            }

            if ($data->alamat == '-' || $data->alamat == NUll) {
                $data->alamat = $request->alamat;
                $data->save();
            }

            if ($data->deskripsi == '-' || $data->deskripsi == NUll) {
                $data->deskripsi = $request->deskripsi;
                $data->save();
            }
            if ($data->instansi == '-' || $data->instansi == NUll) {
                $data->instansi = $request->instansi;
                $data->save();
            }
            if ($data->satuan == '-' || $data->satuan == NUll) {
                $data->satuan = $request->satuan;
                $data->save();
            }

            if ($request->tglkontrak != '') {
                if ($data->tgl_kontrak == NUll) {
                    $data->tgl_kontrak = $request->tglkontrak;
                    $data->save();
                }
            }

            if ($request->tgledit != '') {
                if ($data->tgl_edit == NUll) {
                    $data->tgl_edit = $request->tgledit;
                    $data->save();
                }
            }

            if ($data->ket == NULL) {
                $data->ket =  $request->ket;
                $data->save();
            }

            if ($data->status !=  $request->status) {
                $data->status = $request->status;
                $data->save();
            }



            $dekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($data) {
                $q->where('pesanan_id',  $data->pesanan_id);
            })->get();

            if (count($dekatp) > 0) {
                $deldekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($data) {
                    $q->where('pesanan_id', $data->pesanan_id);
                })->delete();
            }
            $dekat = DetailPesanan::where('pesanan_id', $data->pesanan_id)->get();

            if (count($dekat) > 0) {
                $deldekat = DetailPesanan::where('pesanan_id', $data->pesanan_id)->delete();
            }

            foreach ($request->produk as $dp) {
                $dekatpaket = DetailPesanan::create([
                    'pesanan_id' => $e->pesanan_id,
                    'penjualan_produk_id' => $dp['id'],
                    'jumlah' => $dp['qty'],
                    'harga' => $dp['price'],
                    'ongkir' => $dp['shippingcharge'],
                ]);

                for ($j = 0; $j < count($dp['detailprodukvarian']); $j++) {
                    $dekatprd = DetailPesananProduk::create([
                        'detail_pesanan_id' => $dekatpaket->id,
                        'gudang_barang_jadi_id' => $dp['detailprodukvarian'][$j]['id'],

                    ]);
                }
            }


            $id = array();
            $saveresponse = SaveResponse::where('tipe', 'ekatalog')->get();

            foreach ($saveresponse as $s) {
                $hasil = json_decode($s->parameter);
                if ($hasil->AKN == $request->no_paket) {
                    $id[] = $hasil->id;
                }
            }

            if ($id > 0) {
                SaveResponse::whereIn('id', $id)->delete();
            }

            $save =  SaveResponse::create([
                'tipe' => 'ekatalog',
                'url' =>  URL::current(),
                'parameter' =>  '-',
                'response' => 'ok',
                'method' => 'post',
                'created_at' => Carbon::now()
            ]);

            $field = array(
                'id' => $save->id,
                'SO' => '-',
                'nourut' => $data->no_urut,
                'AKN' => $data->no_paket,
                'PO' => '-',
                'tgl_po' => '-',
                'DO' => '-',
                'tgl_do' => '-',
                'Satuan' => $data->satuan,
            );
            $data = json_encode($field);
            $get_response = SaveResponse::find($save->id);
            $get_response->parameter = $data;
            $get_response->save();
        } else {

            $x = "";
            $pesanan = Pesanan::create([
                'log_id' => '7',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            $x = $pesanan->id;

            if ($request->provinsi != '') {
                $provinsi = Provinsi::where('nama', 'like', '%' . $request->provinsi . '%')->first();
                $p = $provinsi->id;
            } else {
                $p = $request->provinsi;
            }

            if ($request->satuan != '') {
                $sat = $request->satuan;
            } else {
                $sat = '-';
            }


            $Ekatalog = Ekatalog::create([
                'customer_id' => 213,
                'provinsi_id' => $p,
                'no_paket' => $request->no_paket,
                'no_urut' => $request->no_urut,
                'deskripsi' => $request->deskripsi,
                'instansi' => $request->instansi,
                'alamat' => $request->alamat,
                'satuan' => $request->satuan,
                'tgl_kontrak' => $request->tglkontrak,
                'tgl_buat' => $request->tglbuat,
                'tgl_edit' => $request->tgledit,
                'ket' => $request->ket,
                'status' => $request->status,
                'log' => 'penjualan',
                'pesanan_id' => $x

            ]);

            foreach ($request->produk as $dp) {
                $dekatpaket = DetailPesanan::create([
                    'pesanan_id' => $x,
                    'penjualan_produk_id' => $dp['id'],
                    'jumlah' => $dp['qty'],
                    'harga' => $dp['price'],
                    'ongkir' => $dp['shippingcharge'],
                ]);

                for ($j = 0; $j < count($dp['detailprodukvarian']); $j++) {
                    $dekatprd = DetailPesananProduk::create([
                        'detail_pesanan_id' => $dekatpaket->id,
                        'gudang_barang_jadi_id' => $dp['detailprodukvarian'][$j]['id'],

                    ]);
                }
            }

            $id = array();
            $saveresponse = SaveResponse::where('tipe', 'ekatalog')->get();

            foreach ($saveresponse as $s) {
                $hasil = json_decode($s->parameter);
                if ($hasil->AKN == $request->no_paket) {
                    $id[] = $hasil->id;
                }
            }

            if ($id > 0) {
                SaveResponse::whereIn('id', $id)->delete();
            }

            $save =  SaveResponse::create([
                'tipe' => 'ekatalog',
                'url' =>  URL::current(),
                'parameter' =>  '-',
                'response' => 'ok',
                'method' => 'post',
                'created_at' => Carbon::now()
            ]);

            $field = array(
                'id' => $save->id,
                'SO' => '-',
                'nourut' => $request->no_urut,
                'AKN' => $request->no_paket,
                'PO' => '-',
                'tgl_po' => '-',
                'DO' => '-',
                'tgl_do' => '-',
                'Satuan' => $sat,
            );
            $data = json_encode($field);
            $get_response = SaveResponse::find($save->id);
            $get_response->parameter = $data;
            $get_response->save();
        }
        return response()->json(['message'  => 'Berhasil']);
    }

    public function store_ekat_emindo_po(Request $request)
    {
        $validator = Validator::make($request->all(),  [
            'no_paket' => 'unique:ekatalog,no_paket'
        ]);
        if ($validator->fails()) {
            $e = Ekatalog::where('no_paket', $request->no_paket)->first();
            $ekat = Ekatalog::find($e->id);
            $po = Pesanan::find($ekat->pesanan_id);

            if ($po->no_po == NULL) {
                $po->so = $this->createSO('EKAT');
                $po->no_po = $request->no_po;
                $po->save();
            }

            if ($po->tgl_po == NULL) {
                $po->tgl_po = $request->tgl_po;
                $po->save();
            }

            if ($po->no_do == NULL) {
                $po->no_do = $request->no_do;
                $po->save();
            }

            if ($po->tgl_do == NULL) {
                $po->tgl_do = $request->tgl_do;
                $po->save();
            }

            if ($po->ket == NULL) {
                $po->ket = $request->ket;
                $po->save();
            }

            if ($po->log_id == 7) {
                $po->log_id = 9;
                $po->save();
            }



            $dekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($ekat) {
                $q->where('pesanan_id',  $ekat->pesanan_id);
            })->get();

            if (count($dekatp) > 0) {
                $deldekatp = DetailPesananProduk::whereHas('DetailPesanan', function ($q) use ($ekat) {
                    $q->where('pesanan_id', $ekat->pesanan_id);
                })->delete();
            }
            $dekat = DetailPesanan::where('pesanan_id', $ekat->pesanan_id)->get();

            if (count($dekat) > 0) {
                $deldekat = DetailPesanan::where('pesanan_id', $ekat->pesanan_id)->delete();
            }


            foreach ($request->produk as $dp) {
                $dekatpaket = DetailPesanan::create([
                    'pesanan_id' => $e->pesanan_id,
                    'penjualan_produk_id' => $dp['id'],
                    'jumlah' => $dp['qty'],
                    'harga' => $dp['price'],
                    'ongkir' => $dp['shippingcharge'],
                ]);

                for ($j = 0; $j < count($dp['detailprodukvarian']); $j++) {
                    $dekatprd = DetailPesananProduk::create([
                        'detail_pesanan_id' => $dekatpaket->id,
                        'gudang_barang_jadi_id' => $dp['detailprodukvarian'][$j]['id'],
                    ]);
                }
            }


            $id = array();
            $saveresponse = SaveResponse::where('tipe', 'ekatalog')->get();

            foreach ($saveresponse as $s) {
                $hasil = json_decode($s->parameter);
                if ($hasil->AKN == $ekat->no_paket) {
                    $id[] = $hasil->id;
                }
            }

            if ($request->no_do != '') {
                $do = $request->no_do;
            } else {
                $do = '-';
            }

            if ($request->tgl_do != '') {
                $tgldo = $request->tgl_do;
            } else {
                $tgldo = '-';
            }

            if ($id > 0) {
                SaveResponse::whereIn('id', $id)->delete();
            }

            $save =  SaveResponse::create([
                'tipe' => 'ekatalog',
                'url' =>  URL::current(),
                'parameter' =>  '-',
                'response' => 'ok',
                'method' => 'post',
                'created_at' => Carbon::now()
            ]);

            $field = array(
                'id' => $save->id,
                'SO' => $po->so,
                'nourut' => $ekat->no_urut,
                'AKN' => $ekat->no_paket,
                'PO' =>  $request->no_po,
                'tgl_po' => $request->tgl_po,
                'DO' => $do,
                'tgl_do' => $tgldo,
                'Satuan' =>  $ekat->satuan,
            );
            $data = json_encode($field);
            $get_response = SaveResponse::find($save->id);
            $get_response->parameter = $data;
            $get_response->save();


            return response()->json([
                'status' => 200,
                'message' => 'OK',
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'TIdak ditemukan',
            ], 200);
        }
    }

    public function store_spa_emindo(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'no_po' => 'unique:pesanan,no_po'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 200,
                'message' => 'po',
            ], 200);
        } else {
            $x = "";
            $pesanan = Pesanan::create([
                'so' => $this->createSO('SPA'),
                'no_po' => $request->no_po,
                'tgl_po' => $request->tgl_po,
                'no_do' => $request->no_do,
                'tgl_do' => $request->tgl_do,
                'ket' =>  $request->ket,
                'log_id' => '7',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
            $x = $pesanan->id;

            $Spa = Spa::create([
                'customer_id' => 213,
                'pesanan_id' => $x,
                'ket' => $request->ket,
                'log' => 'po'
            ]);

            if ($request->produk) {
                foreach ($request->produk as $dp) {
                    $dekatpaket = DetailPesanan::create([
                        'pesanan_id' => $x,
                        'penjualan_produk_id' => $dp['id'],
                        'jumlah' => $dp['qty'],
                        'harga' => $dp['price'],
                        'ongkir' => $dp['shippingcharge'],
                    ]);

                    for ($j = 0; $j < count($dp['detailprodukvarian']); $j++) {
                        $dekatprd = DetailPesananProduk::create([
                            'detail_pesanan_id' => $dekatpaket->id,
                            'gudang_barang_jadi_id' => $dp['detailprodukvarian'][$j]['id'],

                        ]);
                    }
                }
            }

            if ($request->sparepart) {
                foreach ($request->sparepart as $sp) {
                    $depart = DetailPesananPart::create([
                        'pesanan_id' => $x,
                        'm_sparepart_id' => $sp['id'],
                        'jumlah' => $sp['qty'],
                        'harga' => $sp['price'],
                        'ongkir' => 0,
                    ]);
                }
            }

            if ($request->jasa) {
                foreach ($request->jasa as $js) {
                    $dejasa = DetailPesananPart::create([
                        'pesanan_id' => $x,
                        'm_sparepart_id' => $js['id'],
                        'jumlah' => 1,
                        'harga' => $js['price'],
                        'ongkir' => 0,
                    ]);


                    $qcspb = OutgoingPesananPart::create([
                        'detail_pesanan_part_id' => $dejasa->id,
                        'tanggal_uji' => $request->tgl_po,
                        'jumlah_ok' => 1,
                        'jumlah_nok' => 0
                    ]);
                }
            }

            $id = array();
            $saveresponse = SaveResponse::where('tipe',  'spa')->get();

            foreach ($saveresponse as $s) {
                $hasil = json_decode($s->parameter);
                if ($hasil->PO == $request->no_po) {
                    $id[] = $hasil->id;
                }
            }
            if ($request->no_do != '') {
                $do = $request->no_do;
            } else {
                $do = '-';
            }

            if ($request->tgl_do != '') {
                $tgldo = $request->tgl_do;
            } else {
                $tgldo = '-';
            }


            if ($id > 0) {
                SaveResponse::whereIn('id', $id)->delete();
            }

            $save =  SaveResponse::create([
                'tipe' => 'spa',
                'url' =>  URL::current(),
                'parameter' =>  '-',
                'response' => 'ok',
                'method' => 'post',
                'created_at' => Carbon::now()
            ]);

            $field = array(
                'id' => $save->id,
                'SO' => $pesanan->so,
                'nourut' => '-',
                'AKN' => '-',
                'PO' => $request->no_po,
                'tgl_po' => $request->tgl_po,
                'DO' => $do,
                'tgl_do' => $tgldo,
                'Satuan' => '-',
            );
            $data = json_encode($field);
            $get_response = SaveResponse::find($save->id);
            $get_response->parameter = $data;
            $get_response->save();

            return response()->json([
                'status' => 200,
                'message' => 'ok',
            ], 200);
        }
    }

    public function update_do(Request $request)
    {
        $get = Pesanan::where('no_po', $request->no_po)->first();
        if ($get) {
            if ($get->no_do != '') {
                return response()->json(['message' => 'donotnull']);
            } else {
                $pesanan = Pesanan::find($get->id);
                $pesanan->no_do = $request->no_do;
                $pesanan->tgl_do = $request->tgl_do;
                $update = $pesanan->save();

                if ($pesanan->Ekatalog) {
                    $id = array();
                    $saveresponse = SaveResponse::where('tipe',  'ekatalog')->get();

                    foreach ($saveresponse as $s) {
                        $hasil = json_decode($s->parameter);
                        if ($hasil->AKN == $pesanan->Ekatalog->no_paket) {
                            $id[] = $hasil->id;
                        }
                    }

                    if ($request->no_do != '') {
                        $do = $request->no_do;
                    } else {
                        $do = '-';
                    }

                    if ($request->tgl_do != '') {
                        $tgldo = $request->tgl_do;
                    } else {
                        $tgldo = '-';
                    }

                    if ($id > 0) {
                        SaveResponse::whereIn('id',  $id)->delete();
                    }

                    $save =  SaveResponse::create([
                        'tipe' => 'ekatalog',
                        'url' =>  URL::current(),
                        'parameter' =>  '-',
                        'response' => 'ok',
                        'method' => 'post',
                        'created_at' => Carbon::now()

                    ]);

                    $field = array(
                        'id' => $save->id,
                        'SO' => $pesanan->so,
                        'nourut' => $pesanan->Ekatalog->no_urut,
                        'AKN' => $pesanan->Ekatalog->no_paket,
                        'PO' =>  $pesanan->no_po,
                        'tgl_po' => $pesanan->tgl_po,
                        'DO' => $do,
                        'tgl_do' => $tgldo,
                        'Satuan' =>  $pesanan->Ekatalog->satuan,
                    );
                    $data = json_encode($field);
                    $get_response = SaveResponse::find($save->id);
                    $get_response->parameter = $data;
                    $get_response->save();
                }

                if ($pesanan->Spa) {
                    $id = array();
                    $saveresponse = SaveResponse::where('tipe',  'spa')->get();

                    foreach ($saveresponse as $s) {
                        $hasil = json_decode($s->parameter);
                        if ($hasil->PO == $pesanan->no_po) {
                            $id[] = $hasil->id;
                        }
                    }
                    if ($request->no_do != '') {
                        $do = $request->no_do;
                    } else {
                        $do = '-';
                    }

                    if ($request->tgl_do != '') {
                        $tgldo = $request->tgl_do;
                    } else {
                        $tgldo = '-';
                    }


                    if ($id > 0) {
                        SaveResponse::whereIn('id',  $id)->delete();
                    }

                    $save =  SaveResponse::create([
                        'tipe' => 'spa',
                        'url' =>  URL::current(),
                        'parameter' =>  '-',
                        'response' => 'ok',
                        'method' => 'post',
                        'created_at' => Carbon::now()

                    ]);

                    $field = array(
                        'id' => $save->id,
                        'SO' => $pesanan->so,
                        'nourut' => '-',
                        'AKN' => '-',
                        'PO' => $pesanan->no_po,
                        'tgl_po' => $pesanan->tgl_po,
                        'DO' => $do,
                        'tgl_do' => $tgldo,
                        'Satuan' => '-',
                    );
                    $data = json_encode($field);
                    $get_response = SaveResponse::find($save->id);
                    $get_response->parameter = $data;
                    $get_response->save();
                }

                return response()->json(['message' => 'Berhasil']);
            }
        } else {
            return response()->json(['message' => 'ponull']);
        }
    }

    public function get_data_ekatalog_emindo($akn)
    {

        $e = Ekatalog::where('no_paket', $akn)->first();
        if ($e) {
            $data = array();
            $detailpesanan = DetailPesanan::where('pesanan_id', $e->pesanan_id)->get();

            if ($e->Provinsi) {
                $provinsi =   $e->Provinsi->nama;
            } else {
                $provinsi = NULL;
            }

            $data = array(
                'ekatalog_id' => $e->id,
                'provinsi' => $provinsi,
                'no_paket' => $e->no_paket,
                'no_urut' => $e->no_urut,
                'deskripsi' => $e->deskripsi,
                'instansi' => $e->instansi,
                'alamat' => $e->alamat,
                'satuan' => $e->satuan,
                'tglkontrak' => $e->tgl_kontrak,
                'tglbuat' => $e->tgl_buat,
                'tgledit' => $e->tgl_edit,
                'ket' => $e->ket,
                'status' => $e->status,
            );

            if (count($detailpesanan) > 0) {
                foreach ($detailpesanan as $key_detailpesanan => $detailpesanan) {
                    $data['produk'][$key_detailpesanan] = array(
                        'id' => $detailpesanan->penjualan_produk_id,
                        'produk' => $detailpesanan->PenjualanProduk->nama,
                        'qty' => $detailpesanan->jumlah,
                        'price' => $detailpesanan->harga,
                        'shippingcharge' => $detailpesanan->ongkir,
                        'subtotal' => ($detailpesanan->harga * $detailpesanan->jumlah) + $detailpesanan->ongkir,
                        'detailproduk' => array(),
                        'detailprodukvarian' => array()
                    );

                    for ($j = 0; $j < 1; $j++) {
                        $data['produk'][$key_detailpesanan]['detailproduk'][0] = array(array(
                            'nama' => $detailpesanan->PenjualanProduk->nama,
                            'nama_alias' => $detailpesanan->PenjualanProduk->nama_alias,
                            'harga' => $detailpesanan->PenjualanProduk->harga,
                            'status' => $detailpesanan->PenjualanProduk->status,
                            'created_at' => $detailpesanan->PenjualanProduk->created_at,
                            'updated_at' => $detailpesanan->PenjualanProduk->updated_at,
                        ));
                    }

                    foreach ($detailpesanan->PenjualanProduk->Produk as $key_produk => $produk) {

                        $data['produk'][$key_detailpesanan]['detailproduk'][0]['produk'][$key_produk] = array(
                            'id' => $produk->id,
                            'produk_id' =>  $produk->produk_id,
                            'kelompok_produk_id' => $produk->kelompok_produk_id,
                            'merk' =>  $produk->merk,
                            'nama' =>  $produk->nama,
                            'nama_coo' =>  $produk->nama_coo,
                            'coo' =>  $produk->coo,
                            'no_akd' =>  $produk->no_akd,
                            'ket' => $produk->ket,
                            'status' => $produk->status,
                            'created_at' => $produk->created_at,
                            'updated_at' => $produk->updated_at,
                            'pivot' => $produk->pivot,
                        );

                        foreach ($produk->GudangBarangJadi as $key_v => $v) {
                            $data['produk'][$key_detailpesanan]['detailproduk'][0]['produk'][$key_produk]['gudang_barang_jadi'][$key_v] = array(
                                'id' => $v->id,
                                'produk_id' => $v->produk_id,
                                'nama' => $v->nama,
                                'deskripsi' => $v->deskripsi,
                                'stok' => $v->stok(),
                                'stok_siap' => $v->stok_siap,
                                'satuan_id' => $v->satuan_id,
                                'gambar' => $v->gambar,
                                'dim_p' => $v->dim_p,
                                'dim_l' => $v->dim_l,
                                'dim_t' => $v->dim_t,
                                'status' => $v->status,
                                'created_by' => $v->created_by,
                                'updated_by' => $v->updated_by,
                                'created_at' => $v->created_at,
                                'updated_at' => $v->updated_at,
                            );
                        }
                    }

                    foreach ($detailpesanan->DetailPesananProduk as $key_detailpesananproduk => $detailpesananproduk) {
                        if ($detailpesananproduk->GudangBarangJadi->nama != '') {
                            $variasi = $detailpesananproduk->GudangBarangJadi->nama;
                        } else {
                            $variasi = $detailpesananproduk->GudangBarangJadi->Produk->nama;
                        }
                        $data['produk'][$key_detailpesanan]['detailprodukvarian'][$key_detailpesananproduk] = array(
                            'namaprd' => $detailpesananproduk->GudangBarangJadi->Produk->nama,
                            'id' => $detailpesananproduk->GudangBarangJadi->id,
                            'nama' => $variasi,
                            'label' => $variasi . ' (' . $detailpesananproduk->GudangBarangJadi->stok() . ')',
                            'value' => $detailpesananproduk->GudangBarangJadi->id,
                            'stok' => $detailpesananproduk->GudangBarangJadi->stok(),
                        );
                    }
                }
            }

            //  $field = array([
            //         'AKN'=> $e->no_paket,
            //         'PO'=> null,
            //         'DO'=> 'DO123',
            //         'Instansi'=> 'Instansi Ini',
            // ]);
            // $fields = json_encode($field);

            //  SaveResponse::create([
            //   'tipe' => 'ekatalog',
            //   'url' =>  URL::current(),
            //   'parameter' =>  $fields,
            //   'response' => 'ok',
            //   'method' => 'post',
            //   'created_at' => Carbon::now()]);



            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
                'data'    => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan',
            ], 200);
        }
    }

    public function get_data_spa_emindo($po)

    {
        $p = Pesanan::where('no_po', $po)->first();
        if ($p) {
            $data = array();
            $detailpesanan = DetailPesanan::where('pesanan_id', $p->id)->get();
            $detailpesananpart =  DetailPesananPart::whereHas('Sparepart', function ($q) {
                $q->where('kode', 'not like', '%Jasa%');
            })->where('pesanan_id', $p->id)->get();
            $detailpesananjasa =  DetailPesananPart::whereHas('Sparepart', function ($q) {
                $q->where('kode', 'like', '%Jasa%');
            })->where('pesanan_id', $p->id)->get();

            $data = array(
                'no_po' => $p->no_po,
                'tgl_po' => $p->tgl_po,
                'no_do' => $p->no_do,
                'tgl_do' => $p->tgl_do,
                'ket' => $p->ket,
            );


            if (count($detailpesanan) > 0) {
                foreach ($detailpesanan as $key_detailpesanan => $detailpesanan) {
                    $data['produk'][$key_detailpesanan] = array(
                        'id' => $detailpesanan->penjualan_produk_id,
                        'produk' => $detailpesanan->PenjualanProduk->nama,
                        'qty' => $detailpesanan->jumlah,
                        'price' => $detailpesanan->harga,
                        'shippingcharge' => $detailpesanan->ongkir,
                        'subtotal' => ($detailpesanan->harga * $detailpesanan->jumlah) + $detailpesanan->ongkir,
                        'detailproduk' => array(),
                        'detailprodukvarian' => array()
                    );

                    for ($j = 0; $j < 1; $j++) {
                        $data['produk'][$key_detailpesanan]['detailproduk'][0] = array(array(
                            'nama' => $detailpesanan->PenjualanProduk->nama,
                            'nama_alias' => $detailpesanan->PenjualanProduk->nama_alias,
                            'harga' => $detailpesanan->PenjualanProduk->harga,
                            'status' => $detailpesanan->PenjualanProduk->status,
                            'created_at' => $detailpesanan->PenjualanProduk->created_at,
                            'updated_at' => $detailpesanan->PenjualanProduk->updated_at,
                        ));
                    }

                    foreach ($detailpesanan->PenjualanProduk->Produk as $key_produk => $produk) {

                        $data['produk'][$key_detailpesanan]['detailproduk'][0]['produk'][$key_produk] = array(
                            'id' => $produk->id,
                            'produk_id' =>  $produk->produk_id,
                            'kelompok_produk_id' => $produk->kelompok_produk_id,
                            'merk' =>  $produk->merk,
                            'nama' =>  $produk->nama,
                            'nama_coo' =>  $produk->nama_coo,
                            'coo' =>  $produk->coo,
                            'no_akd' =>  $produk->no_akd,
                            'ket' => $produk->ket,
                            'status' => $produk->status,
                            'created_at' => $produk->created_at,
                            'updated_at' => $produk->updated_at,
                            'pivot' => $produk->pivot,
                        );

                        foreach ($produk->GudangBarangJadi as $key_v => $v) {
                            $data['produk'][$key_detailpesanan]['detailproduk'][0]['produk'][$key_produk]['gudang_barang_jadi'][$key_v] = array(
                                'id' => $v->id,
                                'produk_id' => $v->produk_id,
                                'nama' => $v->nama,
                                'deskripsi' => $v->deskripsi,
                                'stok' => $v->stok(),
                                'stok_siap' => $v->stok_siap,
                                'satuan_id' => $v->satuan_id,
                                'gambar' => $v->gambar,
                                'dim_p' => $v->dim_p,
                                'dim_l' => $v->dim_l,
                                'dim_t' => $v->dim_t,
                                'status' => $v->status,
                                'created_by' => $v->created_by,
                                'updated_by' => $v->updated_by,
                                'created_at' => $v->created_at,
                                'updated_at' => $v->updated_at,
                            );
                        }
                    }

                    foreach ($detailpesanan->DetailPesananProduk as $key_detailpesananproduk => $detailpesananproduk) {
                        if ($detailpesananproduk->GudangBarangJadi->nama != '') {
                            $variasi = $detailpesananproduk->GudangBarangJadi->nama;
                        } else {
                            $variasi = $detailpesananproduk->GudangBarangJadi->Produk->nama;
                        }
                        $data['produk'][$key_detailpesanan]['detailprodukvarian'][$key_detailpesananproduk] = array(
                            'namaprd' => $detailpesananproduk->GudangBarangJadi->Produk->nama,
                            'id' => $detailpesananproduk->GudangBarangJadi->id,
                            'nama' => $variasi,
                            'label' => $variasi . ' (' . $detailpesananproduk->GudangBarangJadi->stok() . ')',
                            'value' => $detailpesananproduk->GudangBarangJadi->id,
                            'stok' => $detailpesananproduk->GudangBarangJadi->stok(),
                        );
                    }
                }
            }

            if (count($detailpesananpart) > 0) {
                foreach ($detailpesananpart as $key_detailpesananpart => $detailpesananpart) {
                    $data['sparepart'][$key_detailpesananpart] = array(
                        'id' => $detailpesananpart->Sparepart->id,
                        'sparepart' => $detailpesananpart->Sparepart->nama,
                        'qty' => $detailpesananpart->jumlah,
                        'price' => $detailpesananpart->harga,
                        'subtotal' => $detailpesananpart->harga * $detailpesananpart->jumlah
                    );
                }
            }

            if (count($detailpesananjasa) > 0) {
                foreach ($detailpesananjasa as $key_detailpesananjasa => $detailpesananjasa) {
                    $data['jasa'][$key_detailpesananjasa] = array(
                        'id' => $detailpesananjasa->Sparepart->id,
                        'jasa' => $detailpesananjasa->Sparepart->nama,
                        'qty' => $detailpesananjasa->jumlah,
                        'price' => $detailpesananjasa->harga,
                        'subtotal' => $detailpesananjasa->harga * $detailpesananjasa->jumlah
                    );
                }
            }
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil',
                'data'    => $data
            ], 200);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Data tidak ditemukan',
            ], 200);
        }
    }

    public function cek_paket($akn)

    {
        $e = Ekatalog::where('no_paket', $akn)->first();
        if ($e) {
            if ($e->customer_id == 484) {
                return response()->json(['message'  => 'Tidak Ditemukan']);
            } else {
                $dp = TFProduksi::where('pesanan_id', $e->pesanan_id)->count();


                if ($dp > 0) {
                    return response()->json(['message'  => 'Sudah Proses']);
                } else {
                    return response()->json(['message'  => 'Belum Proses']);
                }
            }
        } else {
            return response()->json(['message'  => 'Tidak Ditemukan']);
        }
    }

    public function cek_po($po)

    {
        $e = Pesanan::where('no_po', $po)->first();
        if ($e) {
            $dp = TFProduksi::where('pesanan_id',  $e->id)->count();

            if ($dp > 0) {
                return response()->json(['message'  => 'Sudah Proses']);
            } else {
                return response()->json(['message'  => 'Belum Proses']);
            }
        } else {
            return response()->json(['message'  => 'Tidak Ditemukan']);
        }
    }

    public function penjualan_data_emindo()
    {
        $json_array = array();
        $saveresponse = SaveResponse::whereIN('tipe', ['ekatalog', 'spa'])->get();
        foreach ($saveresponse as $s) {
            $json_array[] = json_decode($s->parameter);
        }
        return response()->json([
            'status' => 200,
            'message' =>  'Berhasil',
            'data'    =>  $json_array
        ], 200);
    }

    public function cetak_surat_perintah($id)
    {
        $pesanan = Pesanan::find($id);
        $customPaper = array(0,0,605.44,788.031);


            if($pesanan->DetailPesanan->isNotEmpty()){
                foreach($pesanan->DetailPesanan as $key => $prd){
                    $pesanan_prd[$key] = array(
                        'no' => $key + 1 ,
                        'kode' => '-',
                        'nama' => $prd->penjualanproduk->nama_alias == '' ? $prd->penjualanproduk->nama : $prd->penjualanproduk->nama_alias,
                        'variasi' => $prd->GetVariasi(),
                        'jumlah' => $prd->jumlah,
                        'pajak' => $prd->ppn == '1' ? 'PPn' : '-',
                        'satuan' => 'UNIT'
                    );
                }
            }else{
                $pesanan_prd = array();
            }

        if($pesanan->DetailPesananPart->isNotEmpty()){
            foreach($pesanan->DetailPesananPart as $key => $part){
                $pesanan_part[$key] = array(
                    'no' => count($pesanan_prd) + $key + 1,
                    'kode' =>'-',
                    'nama' => $part->Sparepart->nama,
                    'jumlah' => $part->jumlah,
                    'pajak' => $part->ppn == '1' ? 'PPn' : '-',
                    'satuan' => 'UNIT'
                );
            }
        }else{
            $pesanan_part = array();
        }

            if(count($pesanan_prd) > 0 && count($pesanan_part) <= 0){
                $data =  array_chunk($pesanan_prd, 9);
            }else if (count($pesanan_part) > 0  && count($pesanan_prd) <= 0) {
                $data = array_chunk($pesanan_part, 9);
            }else if (count($pesanan_prd) > 0 && count($pesanan_part) > 0){
                $merge = array_merge($pesanan_prd, $pesanan_part);
                $data = array_chunk($merge, 9);
            }

            if ($pesanan->Ekatalog){
                $cs = $pesanan->Ekatalog->Customer->nama;
                $alamat_cs = $pesanan->Ekatalog->Customer->alamat;
                $ket_paket =$pesanan->ket;
                $no_paket = $pesanan->Ekatalog->no_paket;
                $catatan =  $pesanan->Ekatalog->ket;

            }elseif($pesanan->Spa){
                $cs = $pesanan->Spa->Customer->nama;
                $alamat_cs = $pesanan->Spa->Customer->alamat;
                $ket_paket = '';
                $no_paket = $pesanan->ket;
                $catatan =  '';

            }elseif($pesanan->Spb){
                $cs = $pesanan->Spb->Customer->nama;
                $alamat_cs = $pesanan->Spb->Customer->alamat;
                $ket_paket =$pesanan->ket_kirim;
                $no_paket = '';
                $catatan =  $pesanan->ket;
            }


           $header = array (
            'customer' => $cs,
            'alamat_customer' =>   $alamat_cs,
            'tujuan_kirim' => $pesanan->tujuan_kirim != NULL ? $pesanan->tujuan_kirim : '-',
            'alamat_kirim' => $pesanan->alamat_kirim != NULL ?  $pesanan->alamat_kirim : '-',
            'so' =>  $pesanan->so,
            'tgl_so' =>   Carbon::parse($pesanan->created_at)->format('d M Y'),
            'no_po' =>  $pesanan->no_po,
            'tgl_po' => $pesanan->tgl_po != NULL ? Carbon::parse($pesanan->tgl_po)->format('d M Y') : '-',
            'kemasan' =>  $pesanan->kemasan,
            'tgl_kirim' =>  $pesanan->tgl_do != NULL ? Carbon::parse($pesanan->tgl_do)->format('d M Y') : '-',
            'ekspedisi' =>   $pesanan->ekspedisi_id != NULL ? $pesanan->Ekspedisi->nama  : '-',
            'ket_kirim' =>  $pesanan->ket_kirim,
            'ket_paket' =>  $ket_paket,
            'no_paket' => $no_paket,
            //*Tambahan Penjuaalan
            'catatan' => $catatan,
            //
            'item' => $data
        );




        // return response()->json($header);
        $pdf = PDF::loadView('page.penjualan.surat.surat-perintah-kirim', ['data' => $header,'pesanan'=> $pesanan,'count_page' => count($data)])->setOptions(['defaultFont' => 'sans-serif'])->setPaper($customPaper);
        return $pdf->stream('');
    }
}
