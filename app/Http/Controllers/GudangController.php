<?php

namespace App\Http\Controllers;

use App\Exports\GBJExportSPB;
use App\Exports\ImportNoseri;
use App\Exports\NonsoExport;
use App\Exports\NoseriGudangExport;
use App\Exports\SpbExport;
use App\Models\DetailEkatalog;
use App\Models\DetailEkatalogProduk;
use App\Models\DetailPesanan;
use App\Models\DetailPesananProduk;
use App\Models\Divisi;
use App\Models\Ekatalog;
use App\Models\GudangBarangJadi;
use App\Models\GudangBarangJadiHis;
use App\Models\JadwalPerakitan;
use App\Models\JadwalRakitNoseri;
use App\Models\Layout;
use App\Models\LogSurat;
use App\Models\NoseriBarangJadi;
use App\Models\NoseriBrgJadiLog;
use App\Models\NoseriDetailPesanan;
use App\Models\NoseriTGbj;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\Satuan;
use App\Models\Spa;
use App\Models\Spb;
use App\Models\SystemLog;
use App\Models\TFProduksi;
use App\Models\TFProduksiDetail;
use App\Models\User;
use Illuminate\Filesystem\Filesystem;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as ReaderXlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;
use Mockery\Undefined;
use stdClass;

class GudangController extends Controller
{
    function updateStokGudang($id)
    {
        $d = NoseriBarangJadi::whereHas('gudang', function ($q) use ($id) {
            $q->where('gdg_barang_jadi_id', $id);
        })->where('is_aktif', 1)->count();
        $a = NoseriBarangJadi::whereHas('gudang', function ($q) use ($id) {
            $q->where('gdg_barang_jadi_id', $id);
        })->where('is_aktif', 1)->where('is_ready', 0)->count();
        GudangBarangJadi::find($id)->update(['stok' => $d, 'stok_siap' => $a]);
    }
    // get
    function get_rekap_so_produk()
    {
        try {
            $data = GudangBarangJadi::select(DB::raw('concat(p.nama," ",gdg_barang_jadi.nama) as produkk'), 'gdg_barang_jadi.id')
                ->leftJoin('produk as p', 'p.id', '=', 'gdg_barang_jadi.produk_id')
                ->addSelect([
                    'count_transfer' => function ($query) {
                        $query->selectRaw('count(t_gbj_noseri.id)')
                            ->from('t_gbj_noseri')
                            ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                            ->leftjoin('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                            ->leftjoin('pesanan', 'pesanan.id', '=', 't_gbj.pesanan_id')
                            ->whereNotIn('pesanan.log_id', ["7", "10", "20"])
                            ->whereColumn('t_gbj_detail.gdg_brg_jadi_id', 'gdg_barang_jadi.id')
                            ->limit(1);
                    },
                    'count_ekat_sepakat' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
                            ->limit(1);
                    },
                    'count_ekat_po' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                            ->whereNotNull('pesanan.so')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereRaw('pesanan.log_id not in ("7", "10","20") AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                            ->limit(1);
                    },
                    'count_ekat_nego' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
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
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id in ("7")  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                            ->limit(1);
                    },
                    'count_spa_po' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id not in (7, 10,20) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                            ->limit(1);
                    }, 'count_spb_po' => function ($query) {
                        $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                            ->from('detail_pesanan')
                            ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                            ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                            ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                            ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                            ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                            ->whereNotNull('pesanan.so')
                            ->whereRaw('pesanan.log_id not in (7, 10,20) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                            ->limit(1);
                    }
                ])
                ->has('DetailPesananProduk')
                // ->havingRaw('(coalesce(count_ekat_sepakat, 0) + coalesce(count_ekat_po,0) + coalesce(count_spa_po, 0) + coalesce(count_spb_po, 0)) != 0')
                // ->havingRaw('(coalesce(count_ekat_sepakat, 0) + coalesce(count_ekat_po,0) + coalesce(count_spa_po, 0) + coalesce(count_spb_po, 0)) > count_transfer')
                ->havingRaw('(coalesce(count_ekat_sepakat, 0) + coalesce(count_ekat_nego, 0) + coalesce(count_ekat_draft, 0) + coalesce(count_ekat_po, 0) + coalesce(count_spa_po, 0) + coalesce(count_spb_po, 0)) != 0')
                ->orderBy(DB::raw('concat(p.nama," ",gdg_barang_jadi.nama)'))
                ->get();

            $dt = datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('permintaan', function ($d) {
                    $minta = intval($d->count_ekat_sepakat) + intval($d->count_ekat_po) + intval($d->count_ekat_nego) + intval($d->count_ekat_draft) + intval($d->count_spa_po) + intval($d->count_spb_po);
                    return $minta;
                })
                ->editColumn('sisa', function ($d) {
                    $minta = intval($d->count_ekat_sepakat) + intval($d->count_ekat_po) + intval($d->count_ekat_nego) + intval($d->count_ekat_draft) + intval($d->count_spa_po) + intval($d->count_spb_po);
                    $tf = intval($d->count_transfer);
                    return $minta - $tf;
                })
                ->editColumn('transfer', function ($d) {
                    return intval($d->count_transfer);
                })
                ->editColumn('aksi', function ($d) {
                    $a = '<button type="button" data-toggle="modal" data-target="#detailmodal" data-attr="" data-id="' . $d->id . '" class="btn btn-outline-info btn-sm detailBrg"><i class="far fa-eye"></i> Detail</button>';
                    return $a;
                })
                ->rawColumns(['aksi'])
                ->make(true);

            return $dt;
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function get_detail_rekap_so_produk($id)
    {
        try {
            $data = Pesanan::whereHas('DetailPesanan.DetailPesananProduk.GudangBarangJadi', function ($q) use ($id) {
                $q->where('id', $id);
            })->addSelect([
                'count_pesanan' => function ($q) use ($id) {
                    $q->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('gdg_barang_jadi', 'gdg_barang_jadi.produk_id', '=', 'detail_penjualan_produk.produk_id')
                        ->whereRaw('gdg_barang_jadi.id = ' . $id . ' AND detail_pesanan_produk.gudang_barang_jadi_id = ' . $id)
                        // ->whereNotNull('pesanan.no_po')
                        ->whereColumn('detail_pesanan.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }, 'count_transfer' => function ($q) use ($id) {
                    $q->selectRaw('count(t_gbj_noseri.id)')
                        ->from('t_gbj_noseri')
                        ->leftjoin('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                        ->leftjoin('t_gbj', 't_gbj.id', 't_gbj_detail.t_gbj_id')
                        // ->where('t_gbj_noseri.jenis', '"keluar"')
                        ->where('t_gbj_detail.gdg_brg_jadi_id', $id)
                        ->whereColumn('t_gbj.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }, 'tgl_kontrak_custom' => function ($q) {
                    $q->selectRaw('IF(provinsi.status = "2", SUBDATE(ekatalog.tgl_kontrak, INTERVAL 14 DAY), SUBDATE(ekatalog.tgl_kontrak, INTERVAL 21 DAY))')
                        ->from('ekatalog')
                        ->join('provinsi', 'provinsi.id', '=', 'ekatalog.provinsi_id')
                        ->whereColumn('ekatalog.pesanan_id', 'pesanan.id')
                        ->limit(1);
                }
            ])
                ->with(['Ekatalog.Customer.Provinsi', 'Spa.Customer.Provinsi', 'Spb.Customer.Provinsi'])
                ->whereNotIn('log_id', ['10', '20'])
                ->whereNotNull('pesanan.so')
                // ->havingRaw('count_pesanan > count_transfer')
                ->get();

            $dt = datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('jumlah', function ($data) {
                    $jumlah = $data->count_pesanan;
                    return $jumlah;
                })
                ->addColumn('status', function ($data) {
                    $hitung = $data->count_transfer;
                    return $hitung;
                })
                ->addColumn('so', function ($data) {
                    if (!empty($data->so)) {
                        return $data->so;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('po', function ($data) {
                    if (!empty($data->no_po)) {
                        return $data->no_po;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('customer', function ($data) {
                    if ($data->Ekatalog) {
                        if (isset($data->Ekatalog->Customer)) {
                            return $data->Ekatalog->Customer->nama;
                        }
                    } else if ($data->Spa) {
                        if (isset($data->Spa->Customer)) {
                            return $data->Spa->Customer->nama;
                        }
                    } else {
                        if (isset($data->Spb->Customer)) {
                            return $data->Spb->Customer->nama;
                        }
                    }
                })
                ->addColumn('aksi', function ($data) {
                    if (isset($data->Ekatalog)) {
                        if ($data->status != 'draft') {
                            return  '<a data-toggle="modal" data-target="ekatalog" class="penjualanmodal" data-value="ekatalog"  data-id="' . $data->id . '">
                                  <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                        }
                    } else if (isset($data->Spa)) {
                        return  '<a data-toggle="modal" data-target="spa" class="penjualanmodal" data-value="spa"  data-id="' . $data->id . '">
                                  <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                    } else {
                        return  '<a data-toggle="modal" data-target="spb" class="penjualanmodal" data-value="spb"  data-id="' . $data->id . '">
                                  <button type="button" class="btn btn-outline-primary btn-sm"><i class="fas fa-eye"></i> Detail</button>
                            </a>';
                    }
                })
                ->rawColumns(['aksi'])
                ->make(true);

            return $dt;
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    public function get_data_barang_jadi(Request $request)
    {
        try {
            $data = GudangBarangJadi::leftJoin('produk as p', 'p.id', '=', 'gdg_barang_jadi.produk_id')
                ->leftJoin('m_satuan as s', 's.id', '=', 'gdg_barang_jadi.satuan_id')
                ->leftJoin('kelompok_produk as kp', 'kp.id', '=', 'p.kelompok_produk_id')
                ->select(DB::raw('concat(p.nama," ", gdg_barang_jadi.nama) as produkk'), 'p.merk', 'kp.nama as kel_produk', 'gdg_barang_jadi.id', 's.nama as satuan', 'gdg_barang_jadi.stok', 'gdg_barang_jadi.stok_siap')
                ->orderBy(DB::raw('concat(p.nama," ", gdg_barang_jadi.nama)'))
                ->addSelect(['count_barang' => function ($query) {
                    $query->selectRaw('count(noseri_barang_jadi.id)')
                        ->from('noseri_barang_jadi')
                        ->where('noseri_barang_jadi.is_ready', '0')
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
                        ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
                        ->limit(1);
                }, 'count_ekat_nego' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
                        ->limit(1);
                }, 'count_ekat_draft' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7)  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                        ->limit(1);
                }, 'count_ekat_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                        ->limit(1);
                }, 'count_spa_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                        ->limit(1);
                }, 'count_spb_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                        ->limit(1);
                }])->with('Produk')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    return $data->produkk;
                })
                ->addColumn('kode_produk', function ($data) {
                    // return $data->produk->product->kode . '' . $data->produk->kode;
                    return '-';
                })
                ->addColumn('jumlah', function ($data) {
                    $this->updateStokGudang($data->id);
                    return $data->stok . ' ' . $data->satuan . '<br><span class="badge badge-dark">Stok Siap: ' . $data->stok_siap . ' ' . $data->satuan . '</span>';
                })
                ->addColumn('jumlah1', function ($data) {
                    $ss = $data->count_ekat_sepakat + $data->count_ekat_nego + $data->count_ekat_draft + $data->count_spa_po + $data->count_spb_po + $data->count_ekat_po;
                    return $data->stok - $ss . ' ' . $data->satuan;
                })
                ->addColumn('kelompok', function ($data) {
                    return $data->kel_produk;
                })
                ->addColumn('merk', function ($data) {
                    return $data->merk;
                })
                ->addColumn('action', function ($data) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-edit"></i>&nbsp;Edit
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#stokmodal" class="stokmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-warning btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Daftar Stok
                                </button>
                            </a>';
                })
                ->addColumn('action_direksi', function ($data) {
                    return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-attr=""  data-id="' . $data->id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'action_direksi', 'jumlah'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function GetBarangJadiByID(Request $request)
    {
        try {
            $data = GudangBarangJadi::with('produk', 'satuan')->where('id', $request->id)->get();
            $dataid = $data->pluck('produk_id');
            $datas = Produk::with('product')->where('id', $dataid)->get();
            return response()->json([
                'data' => $data,
                'nama_produk' => $datas
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getNoseri(Request $request, $id)
    {
        try {
            $data = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
                $d->where('id', $id);
            })
                ->where([
                    'is_aktif' => 1,
                    'is_ready' => 0,
                    'is_change' => 1,
                    'is_delete' => 0
                ])->get();
            $layout = Layout::where('jenis_id', 1)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('ids', function ($d) {
                    return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
                })
                ->addColumn('seri', function ($d) {
                    return '<input type="hidden" class="form-control" id="noseriOri[]" value="' . $d->noseri . '"><input type="text" class="form-control" id="noseri[]" value="' . $d->noseri . '" disabled>';
                })
                ->addColumn('nomor', function ($d) {
                    return $d->noseri;
                })
                ->addColumn('Layout', function ($d) use ($layout) {
                    $opt = '';
                    foreach ($layout as $l) {
                        if ($d->layout_id == $l->id) {
                            $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                        } else {
                            $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                        }
                    }
                    return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('aksi', function ($d) {
                    return '<a data-toggle="modal" data-target="#viewStock" class="viewStock" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                            <button class="btn btn-outline-info btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>';
                })
                ->rawColumns(['ids', 'Layout', 'aksi', 'seri'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getNoseriDone(Request $request, $id)
    {
        try {
            $data = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
                $d->where('id', $id);
            })->where([
                'is_aktif' => 1,
                'is_ready' => 1,
                'is_change' => 1,
                'is_delete' => 0
            ])->get();
            $layout = Layout::where('jenis_id', 1)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('ids', function ($d) {
                    return '<input type="checkbox" class="cb-child1" value="' . $d->id . '">';
                })
                ->addColumn('seri', function ($d) {
                    return '<input type="hidden" class="form-control" id="noseriOri[]" value="' . $d->noseri . '"><input type="text" class="form-control" id="noseri[]" value="' . $d->noseri . '" disabled>';
                })
                ->addColumn('nomor', function ($d) {
                    return $d->noseri;
                })
                ->addColumn('used', function ($d) {
                    return $d->pesanan->so;
                })
                ->addColumn('aksi', function ($d) {
                    return '<a data-toggle="modal" data-target="#viewStock" class="viewStock" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                            <button class="btn btn-outline-info btn-sm" type="button" >
                            <i class="far fa-eye"></i>&nbsp;Detail
                            </button>
                        </a>';
                })
                ->rawColumns(['ids', 'Layout', 'aksi', 'seri'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getHistory($id)
    {
        try {
            $data = NoseriBarangJadi::with('from', 'to')->where('noseri', $id)->get()->unique(function ($item) {
                return Carbon::parse($item->created_at)->format('Y-m-d');
            });

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tanggal', function ($d) {
                    return Carbon::parse($d->created_at)->isoFormat('dddd, D MMMM Y');
                })
                ->addColumn('dari', function ($d) {
                    return $d->from->nama;
                })->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getHistorybyProduk()
    {
        try {
            $data = GudangBarangJadi::distinct()
                ->select(
                    'gdg_barang_jadi.id',
                    DB::raw("concat(p.nama,' ',gdg_barang_jadi.nama) as produkk"),
                    DB::raw("concat(gdg_barang_jadi.stok,'',ms.nama) as stok_gudang"),
                    'gdg_barang_jadi.stok',
                    'kp.nama as kel_produk',
                    'ms.nama as satuan'
                )
                ->leftjoin(DB::raw('t_gbj_detail tgd'), 'tgd.gdg_brg_jadi_id', '=', 'gdg_barang_jadi.id')
                ->join(DB::raw('produk as p'), 'p.id', '=', 'gdg_barang_jadi.produk_id')
                ->join(DB::raw('kelompok_produk as kp'), 'kp.id', '=', 'p.kelompok_produk_id')
                ->join(DB::raw('m_satuan as ms'), 'ms.id', '=', 'gdg_barang_jadi.satuan_id')
                ->whereNotNull('tgd.gdg_brg_jadi_id')
                ->orderBy(DB::raw("concat(p.nama,' ',gdg_barang_jadi.nama)"), 'ASC')
                ->addSelect(['count_barang' => function ($query) {
                    $query->selectRaw('count(noseri_barang_jadi.id)')
                        ->from('noseri_barang_jadi')
                        ->where('noseri_barang_jadi.is_ready', '0')
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
                        ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "sepakat"')
                        ->limit(1);
                }, 'count_ekat_nego' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "negosiasi"')
                        ->limit(1);
                }, 'count_ekat_draft' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id in (7)  AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status = "draft"')
                        ->limit(1);
                }, 'count_ekat_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('ekatalog', 'ekatalog.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id AND ekatalog.status != "batal"')
                        ->limit(1);
                }, 'count_spa_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('spa', 'spa.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                        ->limit(1);
                }, 'count_spb_po' => function ($query) {
                    $query->selectRaw('sum(detail_pesanan.jumlah * detail_penjualan_produk.jumlah)')
                        ->from('detail_pesanan')
                        ->join('detail_pesanan_produk', 'detail_pesanan_produk.detail_pesanan_id', '=', 'detail_pesanan.id')
                        ->join('detail_penjualan_produk', 'detail_penjualan_produk.penjualan_produk_id', '=', 'detail_pesanan.penjualan_produk_id')
                        ->join('pesanan', 'pesanan.id', '=', 'detail_pesanan.pesanan_id')
                        ->join('spb', 'spb.pesanan_id', '=', 'pesanan.id')
                        ->whereColumn('detail_pesanan_produk.gudang_barang_jadi_id', 'gdg_barang_jadi.id')
                        ->whereRaw('pesanan.log_id not in (7, 10) AND detail_penjualan_produk.produk_id = gdg_barang_jadi.produk_id')
                        ->limit(1);
                }])->with('Produk')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('stock', function ($d) {
                    return $d->stok . ' ' . $d->satuan;
                })
                ->addColumn('stok_jual', function ($data) {
                    if ($data->id) {
                        $ss = $data->count_ekat_sepakat + $data->count_ekat_nego + $data->count_ekat_draft + $data->count_spa_po + $data->count_spb_po + $data->count_ekat_po;
                        return $data->stok - $ss . ' ' . $data->satuan;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('kelompok', function ($d) {
                    return $d->kel_produk;
                })
                ->addColumn('product', function ($d) {
                    return $d->produkk;
                })
                ->addColumn('kode_produk', function ($d) {
                    return '-';
                })
                ->addColumn('action', function ($d) {
                    return '<a class="btn btn-outline-primary" href="' . url('gbj/tp/' . $d->id . '') . '"><i
                        class="far fa-eye"></i> Detail</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getAllTransaksi()
    {
        try {
            $data1 = DB::table('t_gbj_detail')
                ->leftjoin('t_gbj as h', 'h.id', '=', 't_gbj_detail.t_gbj_id')
                ->leftjoin('gdg_barang_jadi as g', 'g.id', '=', 't_gbj_detail.gdg_brg_jadi_id')
                ->leftjoin('m_satuan as satuan', 'satuan.id', '=', 'g.satuan_id')
                ->leftjoin('produk as prd', 'prd.id', '=', 'g.produk_id')
                ->leftjoin('pesanan as p', 'p.id', '=', 'h.pesanan_id')
                ->leftjoin('m_state as stt', 'stt.id', '=', 'p.log_id')
                ->leftjoin('divisi as d', 'd.id', '=', 'h.dari')
                ->leftjoin('divisi as dd', 'd.id', '=', 'h.ke')
                // ->select('p.so', 'p.no_po', 'p.log_id', 'h.tgl_masuk', 'h.tgl_keluar', 'h.jenis', 'h.deskripsi', 't_gbj_detail.qty', 'stt.nama', 'd.nama as dari', 'dd.nama as ke', DB::raw('concat(prd.nama, " ", g.nama) as produkk'), 't_gbj_detail.id')
                ->select('h.tgl_masuk', 'h.jenis', 't_gbj_detail.qty', 'd.nama as dari', DB::raw('concat(prd.nama, " ", g.nama) as produkk'), 't_gbj_detail.id')
                ->where('h.jenis', '=', 'masuk')
                ->orderByDesc('h.tgl_masuk')
                ->get();
            $g = datatables()->of($data1)
                ->addIndexColumn()
                // ->addColumn('so', function ($d) {
                //     if (isset($d->so)) {
                //         return $d->so;
                //     } else {
                //         return '-';
                //     }
                // })
                // ->addColumn('po', function ($d) {
                //     if (isset($d->no_po)) {
                //         return $d->no_po;
                //     } else {
                //         return '-';
                //     }
                // })
                // ->addColumn('logs', function($d) {
                //     if (isset($d->so)) {
                //         if ($d->log_id == 9) {
                //             $ax = "<span class='badge badge-pill badge-secondary'>".$d->nama."</span>";
                //         } else if ($d->log_id == 6) {
                //             $ax = "<span class='badge badge-pill badge-warning'>".$d->nama."</span>";
                //         } elseif ($d->log_id == 8) {
                //             $ax = "<span class='badge badge-pill badge-info'>".$d->nama."</span>";
                //         } elseif ($d->log_id == 11) {
                //             $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                //         } elseif ($d->log_id == 10) {
                //             $ax = "<span class='badge badge-pill badge-success'>".$d->nama."</span>";
                //         } else {
                //             $ax = "<span class='badge badge-pill badge-danger'>".$d->nama."</span>";
                //         }

                //         return $ax;
                //     } else {
                //         return '-';
                //     }
                // })
                ->addColumn('date_in', function ($d) {
                    if (isset($d->tgl_masuk)) {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMMM Y');
                    } else {
                        return "-";
                    }
                })
                // ->addColumn('date_out', function ($d) {
                //     if (isset($d->tgl_keluar)) {
                //         return Carbon::parse($d->tgl_keluar)->isoFormat('D MMMM Y');
                //     } else {
                //         return "-";
                //     }
                // })
                ->addColumn('divisi', function ($d) {
                    // if ($d->jenis == 'keluar') {
                    //     return '<span class="badge badge-info">' . $d->ke . '</span>';
                    // } else {
                    return '<span class="badge badge-success">' . $d->dari . '</span>';
                    // }
                })
                // ->addColumn('tujuan', function ($d) {
                //     return $d->deskripsi;
                // })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' Unit';
                })
                ->addColumn('product', function ($d) {
                    return $d->produkk;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                <button class="btn btn-outline-primary"><i
                class="far fa-eye"></i> Detail</button>
                        </a>';
                })
                ->rawColumns(['divisi', 'action', 'logs'])
                ->make(true);

            return $g;
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailAll($id)
    {
        try {
            $data = NoseriTGbj::with('layout', 'detail', 'seri')->where('t_gbj_detail_id', $id)->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('layout', function ($d) {
                    if (isset($d->layout->ruang)) {
                        return $d->layout->ruang;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('seri', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
                })
                ->addColumn('title', function ($d) {
                    return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
                })
                ->rawColumns(['checkbox', 'layout'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailHistory($id)
    {
        try {
            $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();

            return datatables()->of($data1)
                ->addIndexColumn()
                ->addColumn('so', function ($d) {
                    if (isset($d->header->pesanan_id)) {
                        return $d->header->pesanan->so;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('po', function ($d) {
                    if (isset($d->header->pesanan_id)) {
                        return $d->header->pesanan->no_po;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('logs', function ($d) {
                    if (isset($d->header->pesanan_id)) {
                        if ($d->header->pesanan->log_id == 9) {
                            $ax = "<span class='badge badge-pill badge-secondary'>" . $d->header->pesanan->log->nama . "</span>";
                        } else if ($d->header->pesanan->log_id == 6) {
                            $ax = "<span class='badge badge-pill badge-warning'>" . $d->header->pesanan->log->nama . "</span>";
                        } elseif ($d->header->pesanan->log_id == 8) {
                            $ax = "<span class='badge badge-pill badge-info'>" . $d->header->pesanan->log->nama . "</span>";
                        } elseif ($d->header->pesanan->log_id == 11) {
                            $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                        } elseif ($d->header->pesanan->log_id == 10) {
                            $ax = "<span class='badge badge-pill badge-success'>" . $d->header->pesanan->log->nama . "</span>";
                        } else {
                            $ax = "<span class='badge badge-pill badge-danger'>" . $d->header->pesanan->log->nama . "</span>";
                        }

                        return $ax;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('date_in', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM YYYY');
                    } else {
                        return "-";
                    }
                })
                ->addColumn('date_out', function ($d) {
                    if (isset($d->header->tgl_keluar)) {
                        return date('d-m-Y', strtotime($d->header->tgl_keluar));
                    } else {
                        return "-";
                    }
                })
                ->addColumn('divisi', function ($d) {
                    if ($d->header->jenis == 'keluar') {
                        return '<span class="badge badge-info">' . $d->header->divisi->nama . '</span>';
                    } else {
                        return '<span class="badge badge-success">' . $d->header->darii->nama . '</span>';
                    }
                })
                ->addColumn('tujuan', function ($d) {
                    return $d->header->deskripsi;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty . ' ' . $d->produk->satuan->nama;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                            <button type="button" class="btn btn-outline-info"><i
                            class="far fa-eye"> Detail</i></button>
                        </a>';
                })
                ->rawColumns(['divisi', 'action', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailHistorySeri($id)
    {
        try {
            $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $id)->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noser', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('posisi', function ($d) {
                    return $d->layout_id == null ? '-' : $d->layout->ruang;
                    // return $d
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function getDetailHistory1($id)
    {
        $header = GudangBarangJadi::with('produk')->where('id', $id)->first();
        $data = GudangBarangJadi::with('produk')->where('id', $id)->get();
        $data1 = TFProduksiDetail::with('header', 'produk', 'noseri')->where('gdg_brg_jadi_id', $id)->get();
        return view('page.gbj.tp.show', compact('data', 'data1', 'header'));
    }

    function getNonSODone()
    {
        try {
            $data = TFProduksi::leftJoin('divisi as p', 'p.id', '=', 't_gbj.ke')
                ->leftJoin('t_gbj_detail as tgd', 'tgd.t_gbj_id', '=', 't_gbj.id')
                ->leftJoin('gdg_barang_jadi as gbj', 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin('produk as pp', 'pp.id', '=', 'gbj.produk_id')
                ->where([
                    ['t_gbj.jenis', '=', 'keluar'],
                    // ['t_gbj.status_id', '=', 2],
                ])->whereNull('t_gbj.pesanan_id')
                ->selectRaw('t_gbj.id, tgd.gdg_brg_jadi_id,
                                concat(pp.nama," ",gbj.nama) as produkk, sum(tgd.qty) as qty')
                ->groupBy('gbj.id')
                ->orderByRaw('concat(pp.nama," ",gbj.nama)')
                ->get();
            $dt = datatables()->of($data)
                ->addIndexColumn()
                // ->editColumn('tgl_keluar', function($d){
                //     return Carbon::parse($d->tgl_keluar)->isoFormat('D MMM YYYY');
                // })
                ->editColumn('qty', function ($d) {
                    return $d->qty . ' Unit';
                })
                ->editColumn('aksi', function ($d) {
                    return '<a href="export_nonso/' . $d->gdg_brg_jadi_id . '">
                            <button class="btn btn-outline-primary"><i class="fas fa-eye"></i> Cetak</button>
                        </a>';
                })
                ->rawColumns(['aksi'])
                ->make(true);

            return $dt;
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function exportNonso($id)
    {
        try {
            $data = TFProduksiDetail::leftJoin('t_gbj as tg', 't_gbj_detail.t_gbj_id', '=', 'tg.id')
                ->leftJoin('divisi as p', 'p.id', '=', 'tg.ke')
                ->leftJoin('gdg_barang_jadi as gbj', 'gbj.id', '=', 't_gbj_detail.gdg_brg_jadi_id')
                ->leftJoin('produk as pp', 'pp.id', '=', 'gbj.produk_id')
                ->where([
                    ['tg.jenis', '=', 'keluar'],
                ])->whereNull('tg.pesanan_id')
                ->selectRaw('p.nama as nm_divisi, tg.tgl_keluar, tg.deskripsi, tg.id as tgdid, t_gbj_detail.id,
                                concat(pp.nama," ",gbj.nama) as produkk, t_gbj_detail.qty')
                ->where("t_gbj_detail.gdg_brg_jadi_id", "=", $id)
                ->first();

            return Excel::download(new NonsoExport($id), 'Laporan Tanpa SO ' . $data->produkk . '.xlsx');
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function get_data_waiting_approve(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting'
            ])->whereHas('noseri', function ($d) use ($request) {
                $d->where('gdg_barang_jadi_id', $request->gbjid);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" id="noseriid" name="id" value="' . $d->noseri_id . '">';
                })
                ->addColumn('noseri_lama', function ($d) {
                    return $d->data_lama;
                })
                ->addColumn('noseri', function ($d) {
                    return $d->data_baru == null ? '-' : $d->data_baru;
                })
                ->editColumn('action', function ($d) {
                    return $d->action == 'delete' ? '<span class="badge badge-danger">Hapus</span>' : '<span class="badge badge-info">Ubah</span>';
                })
                ->addColumn('requested', function ($d) {
                    return $d->actionn->nama;
                })
                ->rawColumns(['checkbox', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function delete_noseri(Request $request)
    {
        try {
            // dd($request->all());
            $check = NoseriTGbj::whereIn('noseri_id', $request->noseriid);
            $dataseri = [];

            foreach ($check->get() as $c) {
                $nbj = NoseriBarangJadi::find($c->noseri_id);
                array_push($dataseri, $nbj->is_ready);
            }
            if (count($dataseri) == 0) {
                $cek = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();

                foreach ($cek as $cc) {
                    $nbjj = NoseriBarangJadi::find($cc->id);
                    // return $nbjj;
                    NoseriBrgJadiLog::create([
                        'gbj_id' => $request->gbjid,
                        'noseri_id' => $cc->id,
                        'data_lama' => $nbjj->noseri,
                        'action' => 'delete',
                        'action_by' => $request->actionby,
                        'status' => 'waiting',
                        'remark' => $request->alasan
                    ]);
                    NoseriBarangJadi::find($cc->id)->update(['is_change' => 0, 'is_delete' => 1]);
                }
                return response()->json(['error' => false, 'msg' => 'Mohon Tunggu Persetujuan dari Manajer']);
            } else {
                if (empty(array_filter($dataseri))) {
                    if (count($check->get()) > 0) {
                        foreach ($check->get() as $d) {
                            $nbjj = NoseriBarangJadi::find($d->noseri_id);

                            NoseriBrgJadiLog::create([
                                'gbj_id' => $request->gbjid,
                                'noseri_id' => $d->noseri_id,
                                'data_lama' => $nbjj->noseri,
                                'action' => 'delete',
                                'action_by' => $request->actionby,
                                'status' => 'waiting',
                                'remark' => $request->alasan
                            ]);
                            NoseriBarangJadi::find($d->noseri_id)->update(['is_change' => 0, 'is_delete' => 1]);
                        }
                    }
                    return response()->json(['error' => false, 'msg' => 'Mohon Tunggu Persetujuan dari Manajer']);
                } else {
                    return response()->json(['error' => true, 'msg' => 'Noseri Ada yang Sedang Digunakan']);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_approve_noseri(Request $request)
    {
        try {
            $data = NoseriBarangJadi::where([
                'is_change' => 0,
                'is_delete' => 1
            ])->groupBy('gdg_barang_jadi_id')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('merk', function ($d) {
                    return $d->gudang->produk->merk;
                })
                ->addColumn('produk', function ($d) {
                    return $d->gudang->nama == null ? $d->gudang->produk->nama : $d->gudang->produk->nama . ' <b>' . $d->gudang->nama . '</b>';
                })
                ->addColumn('kelompok', function ($d) {
                    return $d->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#deletemodal" class="deletemodal" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-eye"></i> Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'produk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_update_noseri(Request $request)
    {
        try {
            $data = NoseriBarangJadi::where([
                'is_change' => 0,
                'is_delete' => 0
            ])->groupBy('gdg_barang_jadi_id')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('merk', function ($d) {
                    return $d->gudang->produk->merk;
                })
                ->addColumn('produk', function ($d) {
                    return $d->gudang->nama == null ? $d->gudang->produk->nama : $d->gudang->produk->nama . ' <b>' . $d->gudang->nama . '</b>';
                })
                ->addColumn('kelompok', function ($d) {
                    return $d->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('action', function ($d) {
                    return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->gdg_barang_jadi_id . '">
                                <button class="btn btn-outline-success btn-sm" type="button" >
                                <i class="far fa-eye"></i> Detail
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'produk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detail_list_delete_noseri(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting',
                'action' => 'delete'
            ])->whereHas('noseri', function ($d) use ($request) {
                $d->where('gdg_barang_jadi_id', $request->gbj);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child1" id="noseriid" name="id" value="' . $d->noseri_id . '">';
                })
                ->addColumn('produk', function ($d) {
                    return $d->noseri->gudang->nama == null ? $d->noseri->gudang->produk->nama : $d->noseri->gudang->produk->nama . ' <b>' . $d->noseri->gudang->nama . '</b>';
                })
                ->addColumn('merk', function ($d) {
                    return $d->noseri->gudang->produk->merk;
                })
                ->addColumn('noseri', function ($d) {
                    return $d->noseri->noseri;
                })
                ->addColumn('requested', function ($d) {
                    return $d->actionn->nama;
                })
                ->addColumn('tgl_aju', function ($d) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->isoFormat('D MMMM YYYY');
                })
                ->addColumn('action', function ($d) {
                    return '<button class="btn btn-outline-success btn-sm btnAlasan" type="button" data-id="' . $d->id . '">
                            <i class="far fa-eye"></i> Detail
                            </button>';
                })
                ->rawColumns(['checkbox', 'produk', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detail_list_update_noseri(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where([
                'status' => 'waiting',
                'action' => 'update',
            ])->whereHas('noseri', function ($d) use ($request) {
                $d->where('gdg_barang_jadi_id', $request->gbj);
            })->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child" id="noseriid" name="id" value="' . $d->noseri_id . '">';
                })
                ->addColumn('produk', function ($d) {
                    return $d->noseri->gudang->nama == null ? $d->noseri->gudang->produk->nama : $d->noseri->gudang->produk->nama . ' <b>' . $d->noseri->gudang->nama . '</b>';
                })
                ->addColumn('merk', function ($d) {
                    return $d->noseri->gudang->produk->merk;
                })
                ->addColumn('kelompok', function ($d) {
                    return $d->noseri->gudang->produk->KelompokProduk->nama;
                })
                ->addColumn('lama', function ($d) {
                    return $d->data_lama;
                })
                ->addColumn('baru', function ($d) {
                    return $d->data_baru;
                })
                ->addColumn('tgl_aju', function ($d) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->isoFormat('D MMMM YYYY');
                })
                ->addColumn('requested', function ($d) {
                    return $d->actionn->nama;
                })
                ->addColumn('action', function ($d) {
                    return '<button class="btn btn-outline-success btn-sm btnAlasan" type="button" data-id="' . $d->id . '">
                            <i class="far fa-eye"></i> Detail
                            </button>';
                })
                ->rawColumns(['checkbox', 'produk', 'action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }



    function edit_noseri(Request $request)
    {
        try {
            // dd($request->all());
            $dataseri = [];
            $data = NoseriBarangJadi::whereIn('id', $request->data);
            $check = NoseriBarangJadi::whereIn('noseri', $request->new)->get();
            if (count($check) > 0) {
                foreach ($check as $d) {
                    array_push($dataseri, $d->noseri);
                }
                return response()->json(['error' => true, 'msg' => 'Noseri ' . implode(', ', $dataseri) . ' Sudah Terdaftar']);
            } else {
                foreach ($data->get() as $k => $c) {
                    NoseriBrgJadiLog::create([
                        'gbj_id' => $request->gbjid,
                        'noseri_id' => $c->id,
                        'data_lama' => $c->noseri,
                        'data_baru' => $request->new[$k],
                        'action' => 'update',
                        'action_by' => $request->actionby,
                        'status' => 'waiting',
                        'remark' => $request->alasan
                    ]);
                    NoseriBarangJadi::find($c->id)->update(['noseri' => $request->new[$k], 'is_change' => 0]);
                }

                return response()->json(['error' => false, 'msg' => 'Mohon Tunggu Persetujuan dari Manager']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function proses_delete_noseri(Request $request)
    {
        try {
            $check = NoseriTGbj::whereIn('noseri_id', $request->noseriid);
            $dataseri = [];

            foreach ($check->get() as $c) {
                $nbj = NoseriBarangJadi::find($c->noseri_id);
                array_push($dataseri, $nbj->is_ready);
            }
            if ($request->is_acc == 'rejected') {
                $obj = [
                    'produk' => Produk::find(GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->nama,
                    'noseri' => NoseriBarangJadi::whereIn('id', $request->noseriid)->get()->pluck('noseri'),
                    'status' => $request->is_acc == 'rejected' ? 'Ditolak' : 'Disetujui',
                    'komentar' => $request->komentar,
                    'oleh' => User::find($request->accby)->nama
                ];
                if (count($dataseri) == 0) {
                    $cek = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();

                    foreach ($cek as $cc) {
                        NoseriBrgJadiLog::where('noseri_id', $cc->id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'rejected', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                        NoseriBarangJadi::find($cc->id)->update(['is_change' => 1, 'is_delete' => 0]);
                    }
                } else {
                    foreach ($check->get() as $ddd) {
                        NoseriBrgJadiLog::where('noseri_id', $ddd->noseri_id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'rejected', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                        NoseriBarangJadi::find($ddd->noseri_id)->update(['is_change' => 1, 'is_delete' => 0]);
                    }
                }

                SystemLog::create([
                    'tipe' => 'GBJ - Manager',
                    'subjek' => 'Penolakan Hapus Noseri Gudang',
                    'response' => json_encode($obj),
                    'user_id' => $request->accby
                ]);
                return response()->json(['error' => false, 'msg' => 'Penolakan Berhasil Dilakukan']);
            } else {
                $obj = [
                    'produk' => Produk::find(GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->nama,
                    'noseri' => NoseriBarangJadi::whereIn('id', $request->noseriid)->get()->pluck('noseri'),
                    'status' => $request->is_acc == 'rejected' ? 'Ditolak' : 'Disetujui',
                    'komentar' => $request->komentar,
                    'oleh' => User::find($request->accby)->nama
                ];
                if (count($dataseri) == 0) {
                    $cekk = NoseriBarangJadi::whereIn('id', $request->noseriid)->get();
                    SystemLog::create([
                        'tipe' => 'GBJ - Manager',
                        'subjek' => 'Persetujuan Hapus Noseri Gudang',
                        'response' => json_encode($obj),
                        'user_id' => $request->accby
                    ]);
                    foreach ($cekk as $ckc) {
                        NoseriBrgJadiLog::where('noseri_id', $ckc->id)->where([
                            'action' => 'delete',
                            'status' => 'waiting'
                        ])->update(['status' => 'approve', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                        NoseriBarangJadi::find($ckc->id)->delete();
                    }
                    return response()->json(['error' => false, 'msg' => 'Noseri Berhasil Dihapus']);
                } else {
                    if (empty(array_filter($dataseri))) {
                        SystemLog::create([
                            'tipe' => 'GBJ - Manager',
                            'subjek' => 'Persetujuan Hapus Noseri Gudang',
                            'response' => json_encode($obj),
                            'user_id' => $request->accby
                        ]);
                        foreach ($check->get() as $d) {
                            NoseriBrgJadiLog::where('noseri_id', $d->noseri_id)->where([
                                'action' => 'delete',
                                'status' => 'waiting'
                            ])->update(['status' => 'approved', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                            NoseriTGbj::where('noseri_id', $d->noseri_id)->delete();
                            NoseriBarangJadi::find($d->noseri_id)->delete();
                        }
                        return response()->json(['error' => false, 'msg' => 'Noseri Berhasil Dihapus']);
                    } else {
                        $obj1 = [
                            'produk' => Produk::find(GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->nama,
                            'noseri' => NoseriBarangJadi::whereIn('id', $request->noseriid)->get()->pluck('noseri'),
                            'status' => $request->is_acc == 'rejected' ? 'Ditolak' : 'Disetujui',
                            'komentar' => $request->komentar,
                            'oleh' => User::find($request->accby)->nama,
                            'alasan' => 'Noseri Sedang Digunakan'
                        ];
                        SystemLog::create([
                            'tipe' => 'GBJ - Manager',
                            'subjek' => 'Persetujuan Hapus Noseri Gudang Gagal',
                            'response' => json_encode($obj1),
                            'user_id' => $request->accby
                        ]);
                        foreach ($check->get() as $dd) {
                            NoseriBrgJadiLog::where('noseri_id', $dd->noseri_id)->where([
                                'action' => 'delete',
                                'status' => 'waiting'
                            ])->update(['status' => 'rejected', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                            NoseriBarangJadi::find($dd->noseri_id)->update(['is_change' => 1]);
                        }
                        return response()->json(['error' => true, 'msg' => 'Noseri Ada yang Sedang Digunakan']);
                    }
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function proses_update_noseri(Request $request)
    {
        try {
            $obj = [
                'produk' => Produk::find(GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->produk_id)->nama . ' ' . GudangBarangJadi::find(NoseriBarangJadi::whereIn('id', $request->noseriid)->first()->gdg_barang_jadi_id)->nama,
                'noseri' => NoseriBarangJadi::whereIn('id', $request->noseriid)->get()->pluck('noseri'),
                'status' => $request->is_acc == 'rejected' ? 'Ditolak' : 'Disetujui',
                'komentar' => $request->komentar,
                'oleh' => User::find($request->accby)->nama
            ];
            if ($request->is_acc == 'rejected') {
                $a = NoseriBrgJadiLog::whereIn('noseri_id', $request->noseriid)->where([
                    'action' => 'update',
                    'status' => 'waiting'
                ])->get()->pluck('data_lama');
                for ($i = 0; $i < count($a); $i++) {
                    NoseriBrgJadiLog::where('noseri_id', $request->noseriid[$i])->where([
                        'action' => 'update',
                        'status' => 'waiting'
                    ])->update(['status' => 'rejected', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                    NoseriBarangJadi::where('id', $request->noseriid[$i])->update(['is_change' => 1, 'noseri' => $a[$i]]);
                }
                SystemLog::create([
                    'tipe' => 'GBJ - Manager',
                    'subjek' => 'Penolakan Perubahan Noseri',
                    'response' => json_encode($obj),
                    'user_id' => $request->accby
                ]);
                return response()->json(['error' => false, 'msg' => 'Noseri Batal Diubah']);
            } else {
                // return 'acc';
                $data = NoseriBarangJadi::whereIn('id', $request->noseriid);
                foreach ($data->get() as $k => $c) {
                    NoseriBrgJadiLog::where('noseri_id', $c->id)->where([
                        'action' => 'update',
                        'status' => 'waiting'
                    ])->update(['status' => 'approved', 'acc_by' => $request->accby, 'komentar' => $request->komentar]);
                    NoseriBarangJadi::find($c->id)->update(['is_change' => 1]);
                }
                SystemLog::create([
                    'tipe' => 'GBJ - Manager',
                    'subjek' => 'Persetujuan Perubahan Noseri',
                    'response' => json_encode($obj),
                    'user_id' => $request->accby
                ]);
                return response()->json(['error' => false, 'msg' => 'Noseri Berhasil Diubah']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getRakit()
    {
        try {
            $data = DB::select("select tg.tgl_masuk, tgd.gdg_brg_jadi_id, concat(p.nama, ' ', gbj.nama) as produkk, count(tgn.noseri_id) as total from t_gbj_noseri tgn
            left join t_gbj_detail tgd on tgd.id = tgn.t_gbj_detail_id
            left join t_gbj tg on tg.id  = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            where tg.dari = 17 and tg.ke = 13
            group by tg.tgl_masuk, tgd.gdg_brg_jadi_id
            except
            select tg.tgl_masuk, tgd.gdg_brg_jadi_id, concat(p.nama, ' ', gbj.nama) as produkk, count(tgn2.noseri_id) from t_gbj_noseri tgn2
            left join t_gbj_detail tgd on tgd.id = tgn2.t_gbj_detail_id
            left join t_gbj tg on tg.id  = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            where tg.dari = 17 and tg.ke = 13 and tgn2.status_id = 3
            group by tg.tgl_masuk, tgd.gdg_brg_jadi_id");
            // return $data;
            $x = [];
            $y = [];
            foreach ($data as $k) {
                $x[] = $k->tgl_masuk;
                $y[] = $k->gdg_brg_jadi_id;
            }

            $datax = TFProduksiDetail::leftJoin('t_gbj as tg', 't_gbj_detail.t_gbj_id', '=', 'tg.id')
                ->leftJoin('gdg_barang_jadi as gbj', 'gbj.id', '=', 't_gbj_detail.gdg_brg_jadi_id')
                ->leftJoin('produk as p', 'p.id', '=', 'gbj.produk_id')
                ->select('tg.tgl_masuk', DB::raw("concat(p.nama, ' ', gbj.nama) as produkk"), DB::raw('sum(t_gbj_detail.qty) as total'), 't_gbj_detail.gdg_brg_jadi_id',)
                ->groupBy('tg.tgl_masuk')
                ->groupBy('t_gbj_detail.gdg_brg_jadi_id')
                ->where('tg.dari', 17)
                ->where('tg.ke', 13)
                ->whereIn('tg.tgl_masuk', $x)
                ->whereIn('t_gbj_detail.gdg_brg_jadi_id', $y)
                ->get();

            return datatables()->of($datax)
                ->addIndexColumn()
                ->addColumn('bppb', function ($d) {
                    // return '-';
                    $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                        $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                        $q->whereHas('header', function ($a) use ($d) {
                            $a->where('tgl_masuk', $d->tgl_masuk)->where('ke', 13)->where('dari', 17);
                        });
                    })->where('jenis', 'masuk')->first();

                    $nobppb = JadwalRakitNoseri::with('header')->where('noseri', $seri_done->seri->noseri)->first();
                    return $nobppb->header->no_bppb == '-' ? '-' : $nobppb->header->no_bppb;
                })
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMMM Y');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->produkk;
                })
                ->addColumn('jumlah', function ($d) {
                    $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                        $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                        $q->whereHas('header', function ($a) use ($d) {
                            $a->where('tgl_masuk', $d->tgl_masuk)->where('ke', 13)->where('dari', 17);
                        });
                    })->where('jenis', 'masuk')->where('status_id', 3)->get()->count();

                    return $d->total . '<br><span class="badge badge-dark"> Sisa Diterima ' . intval($d->total - $seri_done) . '</span>';
                })
                ->addColumn('action', function ($d) {
                    $seri_done = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                        $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                        $q->whereHas('header', function ($a) use ($d) {
                            $a->where('tgl_masuk', $d->tgl_masuk);
                            $a->where('dari', 17);
                        });
                    })->where('jenis', 'masuk')->where('status_id', 3)->get()->count();

                    $seri = NoseriTGbj::whereHas('detail', function ($q) use ($d) {
                        $q->where('gdg_brg_jadi_id', $d->gdg_brg_jadi_id);
                        $q->whereHas('header', function ($a) use ($d) {
                            $a->where('tgl_masuk', $d->tgl_masuk);
                            $a->where('dari', 17);
                        });
                    })->where('jenis', 'masuk')->get()->count();

                    if ($seri == $seri_done) {
                        return  '<a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-produk="' . $d->produkk . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>';
                    } else {
                        return  '
                            <a data-toggle="modal" data-target="#detailmodal" class="detailmodal" data-produk="' . $d->produkk . '"data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-info btn-sm" type="button" >
                                <i class="far fa-eye"></i>&nbsp;Detail
                                </button>
                            </a>
                            <a data-toggle="modal" data-target="#editmodal" class="editmodal" data-produk="' . $d->produkk . '" data-attr=""  data-id="' . $d->id . '" data-tgl="' . $d->tgl_masuk . '" data-brgid="' . $d->gdg_brg_jadi_id . '">
                                <button class="btn btn-outline-primary btn-sm" type="button" >
                                <i class="far fa-edit"></i>&nbsp;Terima
                                </button>
                            </a>
                           ';
                    }
                })
                ->rawColumns(['action', 'jumlah'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getRakitNoseri($id, $value)
    {
        try {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
                $q->where('gdg_brg_jadi_id', $id);
                $q->whereHas('header', function ($a) use ($value) {
                    $a->where('tgl_masuk', $value);
                });
            })->where('status_id', 3)->where('jenis', 'masuk')->get();
            return datatables()->of($data)
                ->addColumn('layout', function ($d) {
                    return $d->layout->ruang;
                })
                ->addColumn('noserii', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('title', function ($d) {
                    return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getTerimaRakit($id, $value)
    {
        try {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($id, $value) {
                $q->where('gdg_brg_jadi_id', $id);
                $q->whereHas('header', function ($a) use ($value) {
                    $a->where('tgl_masuk', $value)->where('dari', 17)->where('ke', 13);
                });
            })->where('status_id', null)->where('jenis', 'masuk')->get();
            $layout = Layout::where('jenis_id', 1)->orderBy('ruang')->get();
            $a = 0;
            return datatables()->of($data)
                ->addColumn('layout', function ($d) use ($layout, $a) {
                    $opt = '';
                    $selected = 7;
                    foreach ($layout as $l) {
                        $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                    }
                    $a++;
                    return '<select name="layout_id[]" id="layout_id" class="form-control layout">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('noserii', function ($d) {
                    return $d->seri->noseri . '<input type="hidden" name="noseri[]" id="noseri[]" value="' . $d->seri->noseri . '">';
                })
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child" value="' . $d->id . '">';
                })
                ->addColumn('title', function ($d) {
                    return $d->detail->produk->produk->nama . ' ' . $d->detail->produk->nama;
                })
                ->rawColumns(['checkbox', 'layout', 'noserii'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getDraftPerakitan(Request $request)
    {
        // detail
        try {
            if ($request->id) {
                $data = TFProduksiDetail::whereHas('header', function ($q) {
                    $q->where('status_id', 1);
                })->with('header', 'produk', 'noseri')->where('t_gbj_id', $request->id)->get();
                return datatables()->of($data)
                    ->addIndexColumn()
                    ->addColumn('nama_produk', function ($d) {
                        return $d->produk->produk->nama . ' ' . $d->produk->nama . '<input type="hidden" name="gdg_brg_jadi_id[]" id="gdg_brg_jadi_id" value="' . $d->gdg_brg_jadi_id . '">';
                    })
                    ->addColumn('jml', function ($d) {
                        return $d->qty . ' ' . $d->produk->satuan->nama . '<input type="hidden" name="qty[]" id="qty" value="' . $d->qty . '"><input type="hidden" name="tfid[]" id="tfid" value="' . $d->id . '">';
                    })
                    ->addColumn('kode_prd', function ($d) {
                        return $d->gdg_brg_jadi_id;
                    })
                    ->addColumn('action', function ($d) {
                        return '<a data-toggle="modal" data-target="#detail" class="detail" data-attr="" data-var="' . $d->produk->nama . '" data-nama="' . $d->produk->produk->nama . '" data-gbj=' . $d->gdg_brg_jadi_id . ' data-id="' . $d->id . '">
                                    <button class="btn btn-info"><i
                                    class="far fa-eye"></i> Detail</button>
                                </a>';
                    })
                    ->addColumn('in', function ($d) {
                        return Carbon::parse($d->header->tgl_masuk)->isoFormat('D MMM Y');
                    })
                    ->addColumn('from', function ($d) {
                        return $d->header->darii->nama;
                    })
                    ->addColumn('tujuan', function ($d) {
                        return $d->header->deskripsi;
                    })
                    ->rawColumns(['action', 'nama_produk', 'jml'])
                    ->make(true);
            } else {
                #header awal
                $data = TFProduksi::with('detail', 'darii')->where(['jenis' => 'masuk', 'status_id' => 1])->get();
                return datatables()->of($data)
                    ->addColumn('in', function ($d) {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM Y');
                    })
                    ->addColumn('from', function ($d) {
                        return $d->darii->nama;
                    })
                    ->addColumn('tujuan', function ($d) {
                        return $d->deskripsi;
                    })
                    ->addColumn('action', function ($d) {
                        return '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr=""  data-id="' . $d->id . '">
                                    <button class="btn btn-info"><i
                                    class="far fa-eye"></i> Detail</button>
                                </a>';
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getNoseriDraftRakit(Request $request)
    {
        try {
            //code...
            $data = NoseriTGbj::with('seri', 'layout')->where('t_gbj_detail_id', $request->t_gbj_detail_id)->get();
            $layout = Layout::where('jenis_id', 1)->get();
            return datatables()->of($data)
                ->addColumn('serii', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('posisi', function ($d) use ($layout) {
                    $opt = '';
                    foreach ($layout as $l) {
                        if ($d->layout_id == $l->id) {
                            $opt .= '<option value="' . $l->id . '" selected>' . $l->ruang . '</option>';
                        } else {
                            $opt .= '<option value="' . $l->id . '">' . $l->ruang . '</option>';
                        }
                    }
                    return '<select name="layout_id[]" id="layout_id[]" class="form-control">
                            ' . $opt . '
                            </select>';
                })
                ->addColumn('checkbox', function ($d) {
                    return '<input type="checkbox" class="cb-child-rancang" value="' . $d->id . '" data-id="' . $d->noseri_id . '">';
                })
                ->rawColumns(['checkbox', 'posisi'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function terimaRakit()
    {
        $layout = Layout::where('jenis_id', 1)->get();
        return view('page.gbj.dp', compact('layout'));
    }

    function ceknoseri(Request $request)
    {
        try {
            //code...
            $data = NoseriBarangJadi::whereIn('noseri', $request->noseri)->get();
            $datarakit = JadwalRakitNoseri::whereIn('noseri', $request->noseri)->get();
            $arr_seri = [];
            $arr_rakit = [];

            if (count($data) == 0 && count($datarakit) == 0) {
                return response()->json(['msg' => 'Noseri tersimpan']);
            } else {
                foreach ($data as $d) {
                    array_push($arr_seri, $d->noseri);
                }

                foreach ($datarakit as $c) {
                    array_push($arr_rakit, $c->noseri);
                }

                if (count($data) > 0) {
                    return response()->json(['error' => 'Nomor seri ' . implode(', ', $arr_seri) . ' sudah terdaftar di gudang']);
                }

                if (count($datarakit) > 0) {
                    return response()->json(['error' => 'Nomor seri ' . implode(', ', $arr_rakit) . ' sudah terdaftar di perakitan']);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function allTp()
    {
        $data1 = TFProduksi::with('pesanan')->where([
            ['jenis', '=', 'keluar'],
            ['status_id', '=', 2],
        ])->whereNotNull('pesanan_id')->get();
        return view('page.gbj.tp.tp', compact('data1'));
    }

    function getSODone()
    {
        try {
            //code...
            $data = TFProduksi::leftJoin('pesanan as p', 'p.id', '=', 't_gbj.pesanan_id')
                ->leftJoin('m_state as ms', 'ms.id', '=', 'p.log_id')
                ->leftJoin('ekatalog as e', 'e.pesanan_id', '=', 'p.id')
                ->leftJoin('customer as c_ekat', 'c_ekat.id', '=', 'e.customer_id')
                ->leftJoin('spa', 'spa.pesanan_id', '=', 'p.id')
                ->leftJoin('customer as c_spa', 'c_spa.id', '=', 'spa.customer_id')
                ->leftJoin('spb', 'spb.pesanan_id', '=', 'p.id')
                ->leftJoin('customer as c_spb', 'c_spb.id', '=', 'spb.customer_id')
                ->leftJoin('provinsi as prov', 'prov.id', '=', 'e.provinsi_id')
                ->where([
                    ['t_gbj.jenis', '=', 'keluar'],
                    ['t_gbj.status_id', '=', 2],
                ])->whereNotNull('t_gbj.pesanan_id')
                ->select(
                    'p.so',
                    'p.no_po',
                    'p.log_id',
                    'ms.nama as log_nama',
                    DB::raw("case
                                when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
                                when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
                                when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
                            end as divisi"),
                    'e.tgl_kontrak',
                    'p.id',
                    'prov.status'
                )
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('noso', function ($d) {
                    return $d->so;
                })
                ->addColumn('nopo', function ($d) {
                    return $d->no_po;
                })
                ->addColumn('logs', function ($d) {
                    if (isset($d->id)) {
                        if ($d->log_id == 9) {
                            $ax = "<span class='badge badge-pill badge-secondary'>" . $d->log_nama . "</span>";
                        } else if ($d->log_id == 6) {
                            $ax = "<span class='badge badge-pill badge-warning'>" . $d->log_nama . "</span>";
                        } elseif ($d->log_id == 8) {
                            $ax = "<span class='badge badge-pill badge-info'>" . $d->log_nama . "</span>";
                        } elseif ($d->log_id == 11) {
                            $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                        } elseif ($d->log_id == 10) {
                            $ax = "<span class='badge badge-pill badge-success'>" . $d->log_nama . "</span>";
                        } else {
                            $ax = "<span class='badge badge-pill badge-danger'>" . $d->log_nama . "</span>";
                        }

                        return $ax;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('tgl_kontrak', function ($d) {
                    if ($d->tgl_kontrak) {

                        if ($d->status == 1) {
                            return Carbon::parse($d->tgl_kontrak)->subWeeks(5)->format('d-m-Y');
                        }

                        if ($d->status == 2) {
                            return Carbon::parse($d->tgl_kontrak)->subWeeks(4)->format('d-m-Y');
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('aksi', function ($d) {

                    return '<td><a href="' . url('gbj/export_spb/' . $d->id . '') . '">
                            <button class="btn btn-outline-primary"><i class="fas fa-print"></i> Cetak</button>
                            </a></td>';
                })
                ->rawColumns(['aksi', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }
    // Export Excell
    function exportSpb($id)
    {
        $header = TFProduksi::where('pesanan_id', $id)->with('pesanan')->get()->pluck('pesanan.so');
        return Excel::download(new SpbExport($id), 'SPB.xlsx');
    }

    function download_template_noseri(Request $request)
    {
        try {
            $no = 1;

            $produk = GudangBarangJadi::with('produk', 'satuan', 'detailpesananproduk')->get()->sortBy('produk.nama');

            // spreadsheet
            $spreadsheet = new Spreadsheet();
            $spreadsheet->createSheet();

            // workshet noseri
            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('Noseri');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Noseri');

            $validation = $spreadsheet->getActiveSheet()->getCell('B2')
                ->getDataValidation();
            $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('Input error');
            $validation->setError('Value is not in list.');
            $validation->setPromptTitle('Pick from list');
            $validation->setPrompt('Please pick a value from the drop-down list.');

            $validation->setFormula1('\'Produk\'!$C$2:$C$288');
            // $validation->setFormula1('"Item A,Item B,Item C"');
            $validation->setSqref('B2:B10000');

            // check duplicate input noseri
            $duplicate = new Conditional();
            $duplicate->setConditionType(Conditional::CONDITION_DUPLICATES);
            $duplicate->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $duplicate->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
            $duplicate->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_YELLOW);

            $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('C2:C10000')->getConditionalStyles();
            $conditionalStyles[] = $duplicate;

            $spreadsheet->getActiveSheet()->getStyle('C2:C10000')->setConditionalStyles($conditionalStyles);

            // workshet master
            $spreadsheet->setActiveSheetIndex(1);
            $spreadsheet->getActiveSheet()->setTitle('Produk');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Merk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $noo = 2;
            foreach ($produk as $p) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $noo, $p->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $noo, $p->produk->merk);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $noo, $p->produk->nama . ' ' . $p->nama);
                $noo++;
                $no++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="Template Noseri.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function import_noseri(Request $request)
    {
        try {
            $file = $request->file('file_csv');
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize();

            $file->move(public_path('upload/noseri/'), $filename);

            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/noseri/' . $filename));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('C', $column_limit);
            $startcount = 2;
            $data = array();
            foreach ($row_range as $row) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'produk' => $sheet->getCell('B' . $row)->getValue(),
                    'noseri' => $sheet->getCell('C' . $row)->getValue()
                ];
                $startcount++;
            }

            foreach ($data as $d) {
                $aa[] = $d['noseri'];
                $bb[] = $d['produk'];
            }

            $check = NoseriBarangJadi::whereIn('noseri', $aa)->get()->pluck('noseri');
            $check_rakit = JadwalRakitNoseri::whereIn('noseri', $aa)->get()->pluck('noseri');
            $seri = [];
            $seri_rakit = [];
            $sheet1 = $sheet->toArray(null, true, true, true);
            $numrow = 1;
            $html = "<input type='hidden' name='namafile' value='" . $filename . "'>";
            $html .= "<table class='table table-bordered table-striped table-hover tableImport'>
                    <thead>
                    <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Noseri</th>
                    </tr>
                    </thead>
                    <tbody>";
            foreach ($sheet1 as $key => $row) {
                $a = $row['A'];
                $b = $row['B'];
                $c = $row['C'];
                if ($numrow > 1) {
                    $nis_td = (!empty($c)) ? "" : " style='background: #E07171;'";
                    $html .= "<tr>";
                    $html .= "<td" . $nis_td . ">" . $a . "</td>";
                    $html .= "<td" . $nis_td . ">" . $b . "</td>";
                    $html .= "<td" . $nis_td . ">" . $c . "</td>";
                    $html .= "</tr>";
                }
                $numrow++;
            }
            $html .= "</tbody></table>";

            if (count($check) > 0 || count($check_rakit) > 0) {
                foreach ($check as $item) {
                    array_push($seri, $item);
                }

                foreach ($check_rakit as $itemm) {
                    array_push($seri_rakit, $itemm);
                }
                return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri) . ' sudah terdaftar di gudang', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri)]);

                return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri_rakit) . ' sudah terdaftar di perakitan', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri_rakit)]);
            } else {
                return response()->json(['msg' => 'Noseri Sudah Bisa Diunggah', 'error' => false, 'data' => $html]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function import_noseri_to_db(Request $request)
    {
        try {
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/noseri/' . $request->namafile));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('C', $column_limit);
            $startcount = 2;
            $data = array();
            foreach ($row_range as $row) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'produk' => $sheet->getCell('B' . $row)->getValue(),
                    'noseri' => $sheet->getCell('C' . $row)->getValue()
                ];
                $startcount++;
            }

            foreach ($data as $d) {
                $aa[] = $d['noseri'];
                $bb[] = $d['produk'];
            }

            // array_unique($bb);
            $check = GudangBarangJadi::select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as produk"))
                ->whereIn(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $bb)
                ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                ->get()->pluck('id');
            // return ;
            foreach ($aa as $key => $nn) {
                $dat_arr[] = [
                    'gdg_barang_jadi_id' => GudangBarangJadi::select('gdg_barang_jadi.id')
                        ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $bb[$key])
                        ->join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                        ->first()->id,
                    'dari' => 13,
                    'noseri' => strtoupper($nn),
                    'jenis' => 'MASUK',
                    'is_ready' => 0,
                    'is_aktif' => 1,
                    'created_by' => $request->userid,
                    'created_at' => Carbon::now(),
                ];
            }
            NoseriBarangJadi::insert($dat_arr);
            $obj = [
                'produk' => $bb,
                'noseri' => $aa
            ];
            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Upload Noseri Excel',
                'response' => json_encode($obj),
                'user_id' => $request->userid
            ]);
            return response()->json(['msg' => 'Data Berhasil Diunggah', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function download_template_so(Request $request, $id)
    {
        try {
            $no = 1;

            $dataso = Pesanan::where('id', $id)->first();

            $dpp = DB::table(DB::raw('detail_pesanan dp'))
                ->select('dp.id', 'p.so', 'pp.nama as nama_paket', DB::raw('concat(p2.nama," ",gbj.nama) as produkk'), 'dp.jumlah', 'dpp.id as dppid', 'p.id as pesid', 'gbj.id as gbjid')
                ->leftJoin(DB::raw('detail_pesanan_produk dpp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
                ->leftJoin(DB::raw('penjualan_produk pp'), 'pp.id', '=', 'dp.penjualan_produk_id')
                ->leftJoin(DB::raw('pesanan p'), 'p.id', '=', 'dp.pesanan_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'dpp.gudang_barang_jadi_id')
                ->leftJoin(DB::raw('produk p2'), 'p2.id', '=', 'gbj.produk_id')
                ->where('dp.pesanan_id', $id)
                ->get();

            // spreadsheet
            $spreadsheet = new Spreadsheet();
            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('Template');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama SO');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Paket');
            $spreadsheet->getActiveSheet()->setCellValue('D1', 'Produk');
            $spreadsheet->getActiveSheet()->setCellValue('E1', 'Noseri');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);

            $so_val = $spreadsheet->getActiveSheet()->getCell('B2')
                ->getDataValidation();
            $so_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $so_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $so_val->setAllowBlank(false);
            $so_val->setShowInputMessage(true);
            $so_val->setShowErrorMessage(true);
            $so_val->setShowDropDown(true);
            $so_val->setErrorTitle('Input error');
            $so_val->setError('Value is not in list.');
            $so_val->setPromptTitle('Pilih SO');
            $so_val->setPrompt('Tolong pilih SO yang tersedia.');

            $so_val->setFormula1('\'Master Detail Sales Order\'!$B$2:$B$688');
            $so_val->setSqref('B2:B10000');

            $paket_val = $spreadsheet->getActiveSheet()->getCell('C2')
                ->getDataValidation();
            $paket_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $paket_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $paket_val->setAllowBlank(false);
            $paket_val->setShowInputMessage(true);
            $paket_val->setShowErrorMessage(true);
            $paket_val->setShowDropDown(true);
            $paket_val->setErrorTitle('Input error');
            $paket_val->setError('Value is not in list.');
            $paket_val->setPromptTitle('Pilih Paket');
            $paket_val->setPrompt('Tolong pilih paket yang tersedia.');

            $paket_val->setFormula1('\'Master Detail Sales Order\'!$C$2:$C$688');
            $paket_val->setSqref('C2:C10000');

            $produk_val = $spreadsheet->getActiveSheet()->getCell('D2')
                ->getDataValidation();
            $produk_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $produk_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $produk_val->setAllowBlank(false);
            $produk_val->setShowInputMessage(true);
            $produk_val->setShowErrorMessage(true);
            $produk_val->setShowDropDown(true);
            $produk_val->setErrorTitle('Input error');
            $produk_val->setError('Value is not in list.');
            $produk_val->setPromptTitle('Pilih produk');
            $produk_val->setPrompt('Tolong pilih produk yang tersedia.');

            $produk_val->setFormula1('\'Master Detail Sales Order\'!$D$2:$D$688');
            // $validation->setFormula1('"Item A,Item B,Item C"');
            $produk_val->setSqref('D2:D10000');

            $duplicate = new Conditional();
            $duplicate->setConditionType(Conditional::CONDITION_DUPLICATES);
            $duplicate->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $duplicate->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
            $duplicate->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_YELLOW);

            $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('E2:E10000')->getConditionalStyles();
            $conditionalStyles[] = $duplicate;

            $spreadsheet->getActiveSheet()->getStyle('E2:E10000')->setConditionalStyles($conditionalStyles);


            $spreadsheet->setActiveSheetIndex(1);
            $spreadsheet->getActiveSheet()->setTitle('Master Detail Sales Order');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nomor SO');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nama Paket');
            $spreadsheet->getActiveSheet()->setCellValue('D1', 'Produk');
            $spreadsheet->getActiveSheet()->setCellValue('E1', 'Jumlah');
            $spreadsheet->getActiveSheet()->setCellValue('F1', 'Jumlah Terkirim');
            $spreadsheet->getActiveSheet()->setCellValue('G1', 'Jumlah Sisa');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

            $detail_no = 2;
            $gbjid = [];
            foreach ($dpp as $dpp) {
                $datacek = NoseriTGbj::whereHas('detail', function ($q) use ($dpp) {
                    $q->where('gdg_brg_jadi_id', $dpp->gbjid);
                    $q->where('detail_pesanan_produk_id', $dpp->dppid);
                })->whereHas('detail.header', function ($q) use ($dpp) {
                    $q->where('pesanan_id', $dpp->pesid);
                })->get()->count();
                $spreadsheet->getActiveSheet()->setCellValue('A' . $detail_no, $dpp->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $detail_no, $dpp->so);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $detail_no, $dpp->nama_paket);
                $spreadsheet->getActiveSheet()->setCellValue('D' . $detail_no, $dpp->produkk);
                $spreadsheet->getActiveSheet()->setCellValue('E' . $detail_no, $dpp->jumlah);
                $spreadsheet->getActiveSheet()->setCellValue('F' . $detail_no, $datacek);
                $spreadsheet->getActiveSheet()->setCellValue('G' . $detail_no, $dpp->jumlah - $datacek);
                $detail_no++;
                $no++;
                $gbjid[] = $dpp->gbjid;
            }

            $noseri = DB::table(DB::raw('noseri_barang_jadi tn'))
                ->select('tn.id', DB::raw('concat(p.nama," ",gbj.nama) as produkk'), 'tn.noseri')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tn.gdg_barang_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where([
                    // 'is_rakit' => 0,
                    'is_aktif' => 1,
                    'is_ready' => 0,
                    // 'is_repair' => 0,
                    'is_change' => 1,
                    'is_delete' => 0,
                    // 'log_id' => 13,
                ])
                ->whereIn('gbj.id', $gbjid)->get();

            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex(2);
            $spreadsheet->getActiveSheet()->setTitle('Master Noseri Sales Order');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Noseri');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $noseri_no = 2;
            foreach ($noseri as $ns) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $noseri_no, $ns->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $noseri_no, $ns->produkk);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $noseri_no, $ns->noseri);
                $noseri_no++;
                $no++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="' . $dataso->so . '.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function preview_so(Request $request)
    {
        try {
            $file = $request->file('file_csv');
            $filename = $file->getClientOriginalName();

            $file->move(public_path('upload/so/'), $filename);

            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/so/' . $filename));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('E', $column_limit);
            $startcount = 2;
            $data = array();
            foreach ($row_range as $row) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'so' => $sheet->getCell('B' . $row)->getValue(),
                    'paket' => $sheet->getCell('C' . $row)->getValue(),
                    'produk' => $sheet->getCell('D' . $row)->getValue(),
                    'noseri' => $sheet->getCell('E' . $row)->getValue(),
                ];
                $startcount++;
            }

            foreach ($data as $d) {
                $seri[] = $d['noseri'];
                $produk[] = $d['produk'];
                $paket[] = $d['paket'];
                $so[] = $d['so'];
            }
            // $cek_rakit = JadwalRakitNoseri::whereIn('noseri', $seri)->get()->pluck('noseri');
            $cek = NoseriBarangJadi::whereIn('noseri', $seri)->get()->pluck('noseri');
            // $cek = $cek_gbj->merge($cek_rakit)->toArray();
            // return array_unique($cek);
            $no_seri = [];
            $sheet1 = $sheet->toArray(null, true, true, true);
            $numrow = 1;
            $html = "<input type='hidden' name='namafile' value='" . $filename . "'>";
            $html .= "<table class='table table-bordered table-striped table-hover tableImport'>
                    <thead>
                    <tr>
                    <th>No</th>
                    <th>Sales Order</th>
                    <th>Paket</th>
                    <th>Produk</th>
                    <th>Noseri</th>
                    </tr>
                    </thead>
                    <tbody>";
            foreach ($sheet1 as $key => $row) {
                $a = $row['A'];
                $b = $row['B'];
                $c = $row['C'];
                $d = $row['D'];
                $e = $row['E'];
                if ($numrow > 1) {
                    $nis_td = (!empty($c)) ? "" : " style='background: #E07171;'";
                    $html .= "<tr>";
                    $html .= "<td" . $nis_td . ">" . $a . "</td>";
                    $html .= "<td" . $nis_td . ">" . $b . "</td>";
                    $html .= "<td" . $nis_td . ">" . $c . "</td>";
                    $html .= "<td" . $nis_td . ">" . $d . "</td>";
                    $html .= "<td" . $nis_td . ">" . $e . "</td>";
                    $html .= "</tr>";
                }
                $numrow++;
            }
            $html .= "</tbody></table>";

            $a = Pesanan::where('id', $request->soid1)->first();
            if ($a->so == implode("", array_unique($so))) {
                if (count($cek) != count($seri)) {
                    $seri_final = [];
                    foreach ($cek as $item) {
                        array_push($no_seri, $item);
                    }

                    foreach ($seri as $ns) {
                        if (!in_array($ns, $no_seri)) {
                            array_push($seri_final, $ns);
                        }
                    }
                    return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri_final) . ' belum terdaftar', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri_final)]);
                } else {
                    return response()->json(['msg' => 'Sales Order Sudah Bisa Diunggah', 'error' => false, 'data' => $html]);
                }
            } else {
                return response()->json([
                    'error' => true,
                    'success' => false,
                    'msg' => 'Nomor Sales Order Tidak Sesuai'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function groupBy($arr, $criteria): array
    {
        return array_reduce($arr, function ($accumulator, $item) use ($criteria) {
            $key = (is_callable($criteria)) ? $criteria($item) : $item[$criteria];
            if (!array_key_exists($key, $accumulator)) {
                $accumulator[$key] = [];
            }

            array_push($accumulator[$key], $item);
            return $accumulator;
        }, []);
    }

    function store_so_to_db(Request $request)
    {
        try {
            // dd($request->all());
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/so/' . $request->namafile));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('E', $column_limit);
            $startcount = 2;
            $data = array();
            $dat_arr = array();
            $new_arr = array();
            $detail_arr = array();
            $noseri_arr = array();
            $dat_log = [];
            foreach ($row_range as $row) {
                $data[] = [
                    'no' => $sheet->getCell('A' . $row)->getValue(),
                    'so' => $sheet->getCell('B' . $row)->getValue(),
                    'paket' => $sheet->getCell('C' . $row)->getValue(),
                    'produk' => $sheet->getCell('D' . $row)->getValue(),
                    'noseri' => $sheet->getCell('E' . $row)->getValue(),
                ];
                $startcount++;
            }

            foreach ($data as $kh => $d) {
                $seri[] = $d['noseri'];
                $produk[] = $d['produk'];
                $paket[] = $d['paket'];
            }

            foreach ($produk as $key => $prd) {
                $dat_arr[] = [
                    'paket' => DetailPesananProduk::join('detail_pesanan as dp', 'dp.id', '=', 'detail_pesanan_produk.detail_pesanan_id')
                        ->join('penjualan_produk as pp', 'pp.id', '=', 'dp.penjualan_produk_id')
                        ->where('dp.pesanan_id', $request->soid)
                        ->where('pp.nama', $paket[$key])
                        ->where('detail_pesanan_produk.gudang_barang_jadi_id', GudangBarangJadi::join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                            ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $prd)
                            ->select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as name"))
                            ->first()->id)
                        ->select('pp.nama', 'detail_pesanan_produk.id')
                        ->first()->id,
                    'produk' => GudangBarangJadi::join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                        ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $prd)
                        ->select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as name"))
                        ->first()->id,
                    'serii' => $seri[$key],
                    'noseri' => NoseriBarangJadi::where('noseri', $seri[$key])->first()->id,
                ];
            }

            $arr = [];
            $arrm = [];
            foreach ($dat_arr as $da) {
                $arr[$da['paket']]['produk'] = $da['produk'];
                $arr[$da['paket']]['noseri'][] = $da['noseri'];
            }


            $a = TFProduksi::where('pesanan_id', $request->soid)->first();
            // return $a;
            if ($a) {
                foreach ($arr as $key => $values) {
                    $c = TFProduksiDetail::where('t_gbj_id', $a->id)->where('gdg_brg_jadi_id', $values['produk'])->where('detail_pesanan_produk_id', $key)->first();
                    if ($c) {
                        foreach ($values['noseri'] as $k => $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $c->id,
                                'noseri_id' => $v,
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => $request->userid
                            ]);

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->soid]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['produk']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - count($values['noseri']);
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => count($values['noseri']),
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $request->userid,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }

                        $obj = [
                            'data' => $arr,
                            'pesanan_so' => Pesanan::find($request->soid)->so,
                            'pesanan_po' => Pesanan::find($request->soid)->no_po,
                            'tgl_keluar' => Carbon::now()
                        ];

                        SystemLog::create([
                            'tipe' => 'GBJ',
                            'subjek' => 'Sales Order Noseri By Upload',
                            'response' => json_encode($obj),
                            'user_id' => $request->userid
                        ]);
                    } else {
                        $detail = TFProduksiDetail::create([
                            't_gbj_id' => $a->id,
                            'detail_pesanan_produk_id' => $key,
                            'gdg_brg_jadi_id' => $values['produk'],
                            'qty' => count($values['noseri']),
                            'jenis' => 'keluar',
                            'status_id' => 2,
                            'state_id' => 8,
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        foreach ($values['noseri'] as $k => $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $detail->id,
                                'noseri_id' => $v,
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => $request->userid
                            ]);

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->soid]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['produk']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - count($values['noseri']);
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => count($values['noseri']),
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $request->userid,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }

                        $obj = [
                            'data' => $arr,
                            'pesanan_so' => Pesanan::find($request->soid)->so,
                            'pesanan_po' => Pesanan::find($request->soid)->no_po,
                            'tgl_keluar' => Carbon::now()
                        ];

                        SystemLog::create([
                            'tipe' => 'GBJ',
                            'subjek' => 'Sales Order Noseri By Upload',
                            'response' => json_encode($obj),
                            'user_id' => $request->userid
                        ]);
                    }
                }
            } else {
                $header = TFProduksi::create([
                    'pesanan_id' => $request->soid,
                    'tgl_keluar' => Carbon::now(),
                    'ke' => 23,
                    'jenis' => 'keluar',
                    'status_id' => 2,
                    'state_id' => 8,
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                foreach ($arr as $key1 => $value1) {
                    $detail = TFProduksiDetail::create([
                        't_gbj_id' => $header->id,
                        'detail_pesanan_produk_id' => $key1,
                        'gdg_brg_jadi_id' => $value1['produk'],
                        'qty' => count($value1['noseri']),
                        'jenis' => 'keluar',
                        'status_id' => 2,
                        'state_id' => 8,
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);

                    foreach ($value1['noseri'] as $k => $v) {
                        NoseriTGbj::create([
                            't_gbj_detail_id' => $detail->id,
                            'noseri_id' => $v,
                            'status_id' => 2,
                            'state_id' => 8,
                            'jenis' => 'keluar',
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->soid]);
                    }

                    $gdg = GudangBarangJadi::whereIn('id', [$value1['produk']])->get()->toArray();
                    $i = 0;
                    foreach ($gdg as $vv) {
                        $vv['stok'] = $vv['stok'] - count($value1['noseri']);
                        // print_r($vv['stok']);
                        $i++;
                        GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                        GudangBarangJadiHis::create([
                            'gdg_brg_jadi_id' => $vv['id'],
                            'stok' => count($value1['noseri']),
                            'tgl_masuk' => Carbon::now(),
                            'jenis' => 'KELUAR',
                            'created_by' => $request->userid,
                            'created_at' => Carbon::now(),
                            'ke' => 23,
                            'tujuan' => $request->deskripsi,
                        ]);
                    }
                }

                $obj = [
                    'data' => $arr,
                    'pesanan_so' => Pesanan::find($request->soid)->so,
                    'pesanan_po' => Pesanan::find($request->soid)->no_po,
                    'tgl_keluar' => Carbon::now()
                ];

                SystemLog::create([
                    'tipe' => 'GBJ',
                    'subjek' => 'Sales Order Noseri By Upload',
                    'response' => json_encode($obj),
                    'user_id' => $request->userid
                ]);
            }

            $po = Pesanan::find($request->soid);

            if ($po->getJumlahPesanan() == $po->cekJumlahkirim()) {
                Pesanan::find($request->soid)->update(['log_id' => 8]);
            } else {
                Pesanan::find($request->soid)->update(['log_id' => 6]);
            }
            $del = new Filesystem;
            $del->cleanDirectory(public_path('upload/so/'));
            File::delete(public_path('upload/so/' . $request->namafile));
            return response()->json(['msg' => 'Data Terkirim ke QC']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function getListSODone()
    {
        try {
            $Ekatalog = collect(Ekatalog::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spa = collect(Spa::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());
            $Spb = collect(Spb::whereHas('Pesanan', function ($q) {
                $q->whereNotNull('no_po');
            })->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function template_tanpa_so()
    {
        try {
            // spreadsheet
            $spreadsheet = new Spreadsheet();
            $spreadsheet->createSheet();

            $spreadsheet->setActiveSheetIndex(0);
            $spreadsheet->getActiveSheet()->setTitle('Template');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'Tujuan');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Deskripsi');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Produk');
            $spreadsheet->getActiveSheet()->setCellValue('D1', 'Noseri');
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(45);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(20);

            $tujuan_val = $spreadsheet->getActiveSheet()->getCell('A2')
                ->getDataValidation();
            $tujuan_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $tujuan_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $tujuan_val->setAllowBlank(false);
            $tujuan_val->setShowInputMessage(true);
            $tujuan_val->setShowErrorMessage(true);
            $tujuan_val->setShowDropDown(true);
            $tujuan_val->setErrorTitle('Input error');
            $tujuan_val->setError('Value is not in list.');
            $tujuan_val->setPromptTitle('Pilih Tujuan');
            $tujuan_val->setPrompt('Tolong pilih Tujuan yang tersedia.');

            $tujuan_val->setFormula1('\'Master Tujuan\'!$B$2:$B$688');
            $tujuan_val->setSqref('A2:A10000');

            $produk_val = $spreadsheet->getActiveSheet()->getCell('C2')
                ->getDataValidation();
            $produk_val->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
            $produk_val->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
            $produk_val->setAllowBlank(false);
            $produk_val->setShowInputMessage(true);
            $produk_val->setShowErrorMessage(true);
            $produk_val->setShowDropDown(true);
            $produk_val->setErrorTitle('Input error');
            $produk_val->setError('Value is not in list.');
            $produk_val->setPromptTitle('Pilih Produk');
            $produk_val->setPrompt('Tolong pilih produk yang tersedia.');

            $produk_val->setFormula1('\'Master Produk\'!$B$2:$B$888');
            $produk_val->setSqref('C2:C10000');

            $duplicate = new Conditional();
            $duplicate->setConditionType(Conditional::CONDITION_DUPLICATES);
            $duplicate->getStyle()->getFont()->getColor()->setARGB(Color::COLOR_BLACK);
            $duplicate->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
            $duplicate->getStyle()->getFill()->getEndColor()->setARGB(Color::COLOR_YELLOW);

            $conditionalStyles = $spreadsheet->getActiveSheet()->getStyle('D2:D10000')->getConditionalStyles();
            $conditionalStyles[] = $duplicate;

            $spreadsheet->getActiveSheet()->getStyle('D2:D10000')->setConditionalStyles($conditionalStyles);

            $spreadsheet->setActiveSheetIndex(1);
            $spreadsheet->getActiveSheet()->setTitle('Master Tujuan');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            $tujuan = Divisi::whereNotIn('id', [1, 2, 3, 4, 5, 31])->get();
            $noo = 2;
            foreach ($tujuan as $t) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $noo, $t->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $noo, $t->nama);
                $noo++;
            }

            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex(2);
            $spreadsheet->getActiveSheet()->setTitle('Master Produk');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);

            $produk = DB::table('gdg_barang_jadi as gbj')
                ->join('produk as p', 'p.id', '=', 'gbj.produk_id')
                ->select('gbj.id', DB::raw('concat(p.nama," ",gbj.nama) as produkk'))
                // ->orderBy(DB::raw('concat(p.nama," ",gbj.nama) as produkk'))
                ->get();

            $no = 2;
            foreach ($produk as $p) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $no, $p->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $no, $p->produkk);
                $no++;
            }

            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex(3);
            $spreadsheet->getActiveSheet()->setTitle('Master Noseri');
            $spreadsheet->getActiveSheet()->setCellValue('A1', 'No');
            $spreadsheet->getActiveSheet()->setCellValue('B1', 'Nama Produk');
            $spreadsheet->getActiveSheet()->setCellValue('C1', 'Nomor Seri');
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);

            $noseri = DB::table(DB::raw('noseri_barang_jadi tn'))
                ->select('tn.id', DB::raw('concat(p.nama," ",gbj.nama) as produkk'), 'tn.noseri')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tn.gdg_barang_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where([
                    // 'is_rakit' => 0,
                    'is_aktif' => 1,
                    'is_ready' => 0,
                    // 'is_repair' => 0,
                    'is_change' => 1,
                    'is_delete' => 0,
                    // 'log_id' => 13,
                ])
                ->get();

            $noseri_no = 2;
            foreach ($noseri as $ns) {
                $spreadsheet->getActiveSheet()->setCellValue('A' . $noseri_no, $ns->id);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $noseri_no, $ns->produkk);
                $spreadsheet->getActiveSheet()->setCellValue('C' . $noseri_no, $ns->noseri);
                $noseri_no++;
                $no++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            // header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="Template.xlsx"'); // Set nama file excel nya
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function preview_tanpa_so(Request $request)
    {
        try {
            // dd($request->all());
            $file = $request->file('file_csv');
            $filename = $file->getClientOriginalName();

            $file->move(public_path('upload/nonso/'), $filename);

            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/nonso/' . $filename));

            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('E', $column_limit);
            $startcount = 2;
            $data = array();

            foreach ($row_range as $row) {
                $data[] = [
                    'tujuan' => $sheet->getCell('A' . $row)->getValue(),
                    'desk' => $sheet->getCell('B' . $row)->getValue(),
                    'produk' => $sheet->getCell('C' . $row)->getValue(),
                    'noseri' => $sheet->getCell('D' . $row)->getValue(),
                ];
                $startcount++;
            }

            foreach ($data as $d) {
                $tujuan[] = $d['tujuan'];
                $desk[] = $d['desk'];
                $produk[] = $d['produk'];
                $noseri[] = $d['noseri'];
            }

            $cek = NoseriBarangJadi::whereIn('noseri', $noseri)->get()->pluck('noseri');
            $no_seri = [];
            $sheet1 = $sheet->toArray(null, true, true, true);
            $numrow = 1;
            $html = "<input type='hidden' name='namafile' value='" . $filename . "'>";
            $html .= "<table class='table table-bordered table-striped table-hover tableImport'>
                    <thead>
                    <tr>
                    <th>Tujuan</th>
                    <th>Deskripsi</th>
                    <th>Produk</th>
                    <th>Noseri</th>
                    </tr>
                    </thead>
                    <tbody>";
            foreach ($sheet1 as $key => $row) {
                $a = $row['A'];
                $b = $row['B'];
                $c = $row['C'];
                $d = $row['D'];
                if ($numrow > 1) {
                    $nis_td = (!empty($c)) ? "" : " style='background: #E07171;'";
                    $html .= "<tr>";
                    $html .= "<td" . $nis_td . ">" . $a . "</td>";
                    $html .= "<td" . $nis_td . ">" . $b . "</td>";
                    $html .= "<td" . $nis_td . ">" . $c . "</td>";
                    $html .= "<td" . $nis_td . ">" . $d . "</td>";
                    $html .= "</tr>";
                }
                $numrow++;
            }
            $html .= "</tbody></table>";

            if (count($cek) != count($noseri)) {
                $seri_final = [];
                foreach ($cek as $item) {
                    array_push($no_seri, $item);
                }

                foreach ($noseri as $ns) {
                    if (!in_array($ns, $no_seri)) {
                        array_push($seri_final, $ns);
                    }
                }
                return response()->json(['msg' => 'Nomor seri ' . implode(', ', $seri_final) . ' belum terdaftar', 'error' => true, 'data' => $html, 'noseri' => implode(', ', $seri_final)]);
            } else {
                return response()->json(['msg' => 'File Sudah Bisa Diunggah', 'error' => false, 'data' => $html]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function store_nonso_to_db(Request $request)
    {
        try {
            $reader = new ReaderXlsx();
            $spreadsheet = $reader->load(public_path('upload/nonso/' . $request->namafile));
            $spreadsheet->setActiveSheetIndex(0);

            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range(2, $row_limit);
            $column_range = range('E', $column_limit);
            $startcount = 2;
            $data = array();
            $dat_arr = array();
            $new_arr = array();
            $detail_arr = array();
            $noseri_arr = array();
            $dat_log = [];
            foreach ($row_range as $row) {
                $data[] = [
                    'tujuan' => $sheet->getCell('A' . $row)->getValue(),
                    'desk' => $sheet->getCell('B' . $row)->getValue(),
                    'produk' => $sheet->getCell('C' . $row)->getValue(),
                    'noseri' => $sheet->getCell('D' . $row)->getValue(),
                ];
                $startcount++;
            }

            foreach ($data as $kh => $d) {
                $tujuan[] = $d['tujuan'];
                $desk[] = $d['desk'];
                $produk[] = $d['produk'];
                $noseri[] = $d['noseri'];
            }

            foreach ($produk as $key => $prd) {
                $dat_arr[] = [
                    'tujuan' => Divisi::where('nama', $tujuan[$key])->first()->id,
                    'produk' => GudangBarangJadi::join('produk', 'produk.id', 'gdg_barang_jadi.produk_id')
                        ->where(DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama)"), $prd)
                        ->select('gdg_barang_jadi.id', DB::raw("concat(produk.nama, ' ', gdg_barang_jadi.nama) as name"))
                        ->first()->id,
                    'serii' => $noseri[$key],
                    'desk' => $desk[$key],
                    'noseri' => NoseriBarangJadi::where('noseri', $noseri[$key])->first()->id,
                ];
            }
            // return $dat_arr;
            $arr = [];
            foreach ($dat_arr as $da) {
                $arr[$da['produk']]['tujuan'] = $da['tujuan'];
                $arr[$da['produk']]['desk'] = $da['desk'];
                $arr[$da['produk']]['noseri'][] = $da['noseri'];
            }

            // return $arr;
            foreach ($arr as $key => $value) {
                $header = TFProduksi::create([
                    'tgl_keluar' => Carbon::now(),
                    'ke' => $value['tujuan'],
                    'deskripsi' => $value['desk'],
                    'jenis' => 'keluar',
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                $detail = TFProduksiDetail::create([
                    't_gbj_id' => $header->id,
                    'gdg_brg_jadi_id' => $key,
                    'qty' => count($value['noseri']),
                    'jenis' => 'keluar',
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                foreach ($value['noseri'] as $k => $v) {
                    NoseriTGbj::create([
                        't_gbj_detail_id' => $detail->id,
                        'noseri_id' => $v,
                        'layout_id' => 1,
                        'status_id' => 2,
                        'jenis' => 'keluar',
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);

                    NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $value['tujuan']]);
                    // NoseriLog::create([
                    //     'gbj_id' => $value['prd'],
                    //     'noseri_id' => $v,
                    //     'nonso' => $header->id,
                    //     'log_id' => $key,
                    //     'created_by' => $request->userid
                    // ]);
                }
            }

            $obj = [
                'data' => $arr,
                'tgl_keluar' => Carbon::now()
            ];

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Pengeluaran Tanpa SO By Upload',
                'response' => json_encode($obj),
                'user_id' => $request->userid
            ]);

            $del = new Filesystem;
            $del->cleanDirectory(public_path('upload/nonso/'));
            File::delete(public_path('upload/nonso/' . $request->namafile));

            return response()->json(['msg' => 'Data Terkirim']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // store
    function storeNoseri(Request $request)
    {
        try {
            foreach ($request->noseri as $key => $value) {
                $detail = TFProduksiDetail::find($request->t_gbj_detail_id);
                $detail->status_id = 3;
                $detail->updated_at = Carbon::now();
                $detail->save();

                $header = TFProduksi::find($detail->t_gbj_id);
                $header->tgl_masuk = Carbon::now();
                $header->status_id = 3;
                $header->updated_at = Carbon::now();
                $header->save();

                $noserigbj = new NoseriBarangJadi();
                $noserigbj->gdg_barang_jadi_id = $detail->gdg_brg_jadi_id;
                $noserigbj->ke = $header->ke;
                $noserigbj->noseri = $value;
                $noserigbj->jenis = 'MASUK';
                $noserigbj->created_at = Carbon::now();
                $noserigbj->save();
            }

            $gdg = GudangBarangJadi::find($detail->gdg_brg_jadi_id);
            $gdg->stok = $gdg->stok + $detail->qty;
            $gdg->save();

            return response()->json(['msg', 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function StoreBarangJadi(Request $request)
    {
        try {
            $id = $request->id;
            if (($request->produk_id && $request->satuan_id) == null) {
                return response()->json(['msg' => 'Produk dan Satuan Harus Diisi', 'error' => true]);
            } else {
                if ($id) {
                    $brg_jadi = GudangBarangJadi::find($id);

                    if (empty($brg_jadi->id)) {
                        return response()->json(['msg' => 'Data not found']);
                    }

                    $brg_jadi->produk_id = $request->produk_id;
                    $brg_jadi->satuan_id = $request->satuan_id;
                    $brg_jadi->nama = $request->nama == null ? ' ' : $request->nama;
                    $brg_jadi->deskripsi = $request->deskripsi;
                    $image = $request->file('gambar');
                    if ($image) {
                        $path = 'upload/gbj/';
                        $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($path, $nameImage);
                        $brg_jadi->gambar = $nameImage;
                    }
                    $brg_jadi->dim_p = $request->dim_p;
                    $brg_jadi->dim_l = $request->dim_l;
                    $brg_jadi->dim_t = $request->dim_t;
                    $brg_jadi->status = $request->status;
                    $brg_jadi->updated_at = Carbon::now();
                    $brg_jadi->updated_by = $request->userid;
                    $brg_jadi->save();

                    $obj =  [
                        'produk' => Produk::find($request->produk_id)->nama,
                        'variasi' => $request->nama,
                        'satuan' => Satuan::find($request->satuan_id)->nama,
                    ];
                    SystemLog::create([
                        'tipe' => 'GBJ',
                        'subjek' => 'Perubahan Produk Gudang',
                        'response' => json_encode($obj),
                        'user_id' => $request->userid,
                    ]);
                } else {
                    $brg_jadi = new GudangBarangJadi();
                    $brg_jadi->produk_id = $request->produk_id;
                    $brg_jadi->satuan_id = $request->satuan_id;
                    $brg_jadi->nama = $request->nama == null ? ' ' : $request->nama;
                    $brg_jadi->stok = 0;
                    $brg_jadi->deskripsi = $request->deskripsi;
                    $image = $request->file('gambar');
                    if ($image) {
                        $path = 'upload/gbj/';
                        $nameImage = date('YmdHis') . "." . $image->getClientOriginalExtension();
                        $image->move($path, $nameImage);
                        $brg_jadi->gambar = $nameImage;
                    }
                    $brg_jadi->dim_p = $request->dim_p;
                    $brg_jadi->dim_l = $request->dim_l;
                    $brg_jadi->dim_t = $request->dim_t;
                    $brg_jadi->status = $request->status;
                    $brg_jadi->created_at = Carbon::now();
                    $brg_jadi->created_by = $request->userid;
                    $brg_jadi->save();

                    $obj =  [
                        'produk' => Produk::find($request->produk_id)->nama,
                        'variasi' => $request->nama,
                        'satuan' => Satuan::find($request->satuan_id)->nama,
                    ];
                    SystemLog::create([
                        'tipe' => 'GBJ',
                        'subjek' => 'Penambahan Produk Gudang',
                        'response' => json_encode($obj),
                        'user_id' => $request->userid,
                    ]);
                }
                return response()->json(['msg' => 'Successfully']);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeDraftRancang(Request $request)
    {
        try {
            $h = new TFProduksi();
            $h->tgl_masuk = $request->tgl_masuk;
            $h->dari = $request->dari;
            $h->deskripsi = $request->deskripsi;
            $h->status_id = 1;
            $h->jenis = 'masuk';
            $h->created_at = Carbon::now();
            $h->created_by = $request->userid;
            $h->save();

            foreach ($request->data as $key => $value) {
                $d = new TFProduksiDetail();
                $d->t_gbj_id = $h->id;
                $d->gdg_brg_jadi_id = $key;
                $d->qty = $value['jumlah'];
                $d->status_id = 1;
                $d->jenis = 'masuk';
                $d->created_at = Carbon::now();
                $d->created_by = $request->userid;
                $d->save();

                foreach ($value['noseri'] as $key1 => $value1) {
                    $nn = new NoseriBarangJadi();
                    $nn->gdg_barang_jadi_id = $key;
                    $nn->dari = $request->dari;
                    $nn->noseri = $value1;
                    $nn->layout_id = $value['layout'][$key1];
                    $nn->jenis = 'MASUK';
                    $nn->is_aktif = 0;
                    $nn->created_by = $request->userid;
                    $nn->save();

                    $n = new NoseriTGbj();
                    $n->t_gbj_detail_id = $d->id;
                    $n->noseri_id = $nn->id;
                    $n->layout_id = $value['layout'][$key1];
                    $n->jenis = 'masuk';
                    $n->status_id = 1;
                    $n->state_id = 2;
                    $n->created_by = $request->userid;
                    $n->save();
                }
            }

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeFinalRancang(Request $request)
    {
        try {
            $h = new TFProduksi();
            $h->tgl_masuk = $request->tgl_masuk;
            $h->dari = $request->dari;
            $h->deskripsi = $request->deskripsi;
            $h->status_id = 2;
            $h->jenis = 'masuk';
            $h->created_at = Carbon::now();
            $h->created_by = $request->userid;
            $h->save();

            foreach ($request->data as $key => $value) {
                $d = new TFProduksiDetail();
                $d->t_gbj_id = $h->id;
                $d->gdg_brg_jadi_id = $key;
                $d->qty = $value['jumlah'];
                $d->status_id = 2;
                $d->jenis = 'masuk';
                $d->created_at = Carbon::now();
                $d->created_by = $request->userid;
                $d->save();

                foreach ($value['noseri'] as $key1 => $value1) {
                    $nn = new NoseriBarangJadi();
                    $nn->gdg_barang_jadi_id = $key;
                    $nn->dari = $request->dari;
                    $nn->noseri = strtoupper($value1);
                    $nn->layout_id = $value['layout'][$key1];
                    $nn->jenis = 'MASUK';
                    $nn->is_aktif = 1;
                    $nn->created_by = $request->userid;
                    $nn->save();

                    $n = new NoseriTGbj();
                    $n->t_gbj_detail_id = $d->id;
                    $n->noseri_id = $nn->id;
                    $n->layout_id = $value['layout'][$key1];
                    $n->jenis = 'masuk';
                    $n->status_id = 2;
                    $n->state_id = 3;
                    $n->created_by = $request->userid;
                    $n->save();
                }

                $gdg = GudangBarangJadi::whereIn('id', [$key])->get()->toArray();

                foreach ($gdg as $vv) {
                    $vv['stok'] = $vv['stok'] + $value['jumlah'];

                    GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok'], 'updated_by' => $request->userid]);
                    GudangBarangJadiHis::create([
                        'gdg_brg_jadi_id' => $vv['id'],
                        'stok' => $value['jumlah'],
                        'tgl_masuk' => $request->tgl_masuk,
                        'jenis' => 'MASUK',
                        'created_by' => $request->userid,
                        'created_at' => Carbon::now(),
                        'dari' => $request->dari,
                        'tujuan' => $request->deskripsi,
                    ]);
                }
            }

            return response()->json(['msg' => 'Successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function finalDraftRakit(Request $request)
    {
        try {
            $header = TFProduksi::create([
                'tgl_keluar' => Carbon::now(),
                'ke' => Divisi::find($request->divisi)->id,
                'deskripsi' => $request->deskripsi,
                'jenis' => 'keluar',
                'created_at' => Carbon::now(),
                'created_by' => $request->userid
            ]);

            foreach ($request->produk as $key => $value) {
                $detail = TFProduksiDetail::create([
                    't_gbj_id' => $header->id,
                    'gdg_brg_jadi_id' => $value['prd'],
                    'qty' => $value['jml'],
                    'jenis' => 'masuk',
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                foreach ($value['noseri'] as $k => $v) {
                    $seri = NoseriBarangJadi::create([
                        'gdg_barang_jadi_id' =>  $value['prd'],
                        'dari' => $request->divisi,
                        'noseri' => strtoupper($v),
                        'layout_id' => $value['layout'][$k],
                        'jenis' => 'MASUK',
                        'is_aktif' => 1,
                        'created_by' => $request->userid,
                    ]);

                    NoseriTGbj::create([
                        't_gbj_detail_id' => $detail->id,
                        'noseri_id' => $seri->id,
                        'layout_id' => $value['layout'][$k],
                        'status_id' => 2,
                        'jenis' => 'masuk',
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);
                }

                $gdg = GudangBarangJadi::find($value['prd']);
                $stok = $gdg->stok + $value['jml'];
                $gdg->update(['stok' => $stok]);
            }

            $obj = [
                'dari' => Divisi::find($request->divisi)->nama,
                'deskripsi' => $request->deskripsi,
                'tgl_masuk' => $request->tgl_masuk,
                'data' => $request->produk
            ];

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Penerimaan Selain Perakitan',
                'response' => json_encode($obj),
                'user_id' => $request->userid,
            ]);

            return response()->json(['msg' => 'Data Berhasil Diterima', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function get_alasan_from_staff(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::find($request->id);

            return response()->json([
                'error' => false,
                'msg' => 'Data Berhasil Ditemukan',
                'data' => $data->remark
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getNoseriHistoryPerubahan(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::where('gbj_id', $request->gbj)->orderByDesc('id')->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('status', function ($q) {
                    if ($q->action == 'update') {
                        switch ($q->status) {
                            case 'rejected':
                                return '<span class="badge badge-danger">Perubahan Ditolak</span>';
                                break;

                            case 'approved':
                                return '<span class="badge badge-success">Perubahan Diterima</span>';
                                break;

                            default:
                                return '<span class="badge badge-dark">Menunggu Persetujuan</span>';
                                break;
                        }
                    } else {
                        switch ($q->status) {
                            case 'rejected':
                                return '<span class="badge badge-danger">Hapus Ditolak</span>';
                                break;

                            case 'approved':
                                return '<span class="badge badge-success">Hapus Diterima</span>';
                                break;

                            default:
                                return '<span class="badge badge-dark">Menunggu Persetujuan</span>';
                                break;
                        }
                    }
                })
                ->addColumn('aksi', function ($d) {
                    return '<a data-toggle="modal" data-target="#openModalHistory" class="openModalHistory" data-attr="" data-id="' . $d->id . '">
                                <button class="btn btn-outline-info btn-sm"><i class="fas fa-eye"></i>Detail</button>
                            </a>';
                })
                ->rawColumns(['status', 'aksi'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function headerCountNoseri($id)
    {
        $data = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
            $d->where('id', $id);
        })
            ->where([
                'is_aktif' => 1,
                'is_ready' => 0,
                'is_change' => 1,
                'is_delete' => 0
            ])->get()->count();

        $data_done = NoseriBarangJadi::whereHas('gudang', function ($d) use ($id) {
            $d->where('id', $id);
        })->where([
            'is_aktif' => 1,
            'is_ready' => 1,
            'is_change' => 1,
            'is_delete' => 0
        ])->get()->count();

        $data_wait = NoseriBrgJadiLog::where([
            'status' => 'waiting'
        ])->whereHas('noseri', function ($d) use ($id) {
            $d->where('gdg_barang_jadi_id', $id);
        })->get()->count();

        return response()->json([
            'belum' => $data,
            'sudah' => $data_done,
            'wait' => $data_wait
        ]);
    }

    function detailNoseriHistoryPerubahan(Request $request)
    {
        try {
            $data = NoseriBrgJadiLog::find($request->id);
            if (!$data->komentar) {
                return response()->json([
                    'error' => false,
                    'msg' => 'Komentar Kosong Belum Di ACC',
                    'data_mgr' => '-',
                    'data_stf' => $data->remark
                ]);
            } else {
                return response()->json([
                    'error' => false,
                    'msg' => 'Komentar ada sudah Di ACC',
                    'data_mgr' => $data->komentar,
                    'data_stf' => $data->remark
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'msg' => $e->getMessage()]);
        }
    }

    function deleteCekSO(Request $request)
    {
        try {
            // dd($request->all());
            $h = Pesanan::find($request->pesanan_id);
            foreach ($request->data as $key => $value) {
                $dpid = DetailPesananProduk::whereIn('id', [$key])->get()->pluck('detail_pesanan_id');
                DetailPesanan::whereIn('id', $dpid)->delete();
            }
            return response()->json(['msg' => 'Successfully', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function storeCekSO(Request $request)
    {
        try {
            $h = Pesanan::find($request->pesanan_id);
            foreach ($request->data as $key => $value) {
                $dpid = DetailPesananProduk::where('id', $key)->get()->pluck('detail_pesanan_id');
                DetailPesanan::whereIn('id', $dpid)->update(['jumlah' => $value[1]]);
                DetailPesananProduk::whereIn('id', [$key])
                    ->update(['status_cek' => 4, 'checked_by' => $request->userid, 'gudang_barang_jadi_id' => $value[0]]);
            }

            $h->status_cek = 4;
            $h->checked_by = $request->userid;
            $h->log_id = 6;
            $h->save();

            $obj = [
                'pesanan_so' => $h->so,
                'pesanan_po' => $h->no_po,
                'data' => $request->data,
                'status_cek' => 'Sudah Dicek'
            ];

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Persiapan Produk Gudang',
                'response' => json_encode($obj),
                'user_id' => $request->userid
            ]);

            return response()->json(['msg' => 'Successfully', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // select
    function select_layout()
    {
        $data = Layout::where('jenis_id', 1)->get();
        return response()->json($data);
    }

    function select_product()
    {
        $data = Produk::with('product')->get();
        return response()->json($data);
    }

    function select_product_by_id($id)
    {
        $data = Produk::with('product')->find($id);
        return response()->json($data);
    }

    function select_satuan()
    {
        $data = Satuan::all();
        return response()->json($data);
    }

    function select_divisi()
    {
        $data = Divisi::whereNotIn('id', [1, 2, 3, 4, 5, 31])->get();
        return response()->json($data);
    }

    function select_gbj()
    {
        $data = GudangBarangJadi::with('produk')->get();
        return response()->json($data);
    }

    // dashboard

    function getNoseriTerima(Request $request, $id)
    {
        try {
            $data = NoseriTGbj::whereHas('detail', function ($q) use ($id) {
                $q->where('t_gbj_detail_id', $id);
            })->get();
            return datatables()->of($data)
                ->addColumn('noser', function ($d) {
                    return $d->seri->noseri;
                })
                ->addColumn('posisi', function ($d) {
                    if (isset($d->layout->ruang)) {
                        return $d->layout->ruang;
                    } else {
                        return '-';
                    }
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }
    // produk
    function h1()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10, 20])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h2()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5, 9])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h3()
    {
        $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1, 4])->orderBy('stok', 'asc')->get();
        return count($data);
    }

    function h4()
    {

        $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 6 MONTH ) and date_sub(now(), INTERVAL 3 MONTH)');

        return count($data);
    }

    function h5()
    {
        $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 12 MONTH ) and date_sub(now(), INTERVAL 6 MONTH)');

        return count($data);
    }

    function h6()
    {
        $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 36 MONTH ) and date_sub(now(), INTERVAL 12 MONTH)');

        return count($data);
    }

    function h7()
    {
        $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
        from t_gbj_detail tgd
        left join t_gbj tg on tg.id = tgd.t_gbj_id
        left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
        left join produk p on p.id = gbj.produk_id
        left join m_satuan ms on ms.id = gbj.satuan_id
        where tgd.jenis = "masuk" and tg.tgl_masuk = date_sub(now(), interval 3 YEAR)');

        return count($data);
    }

    function getProdukstok1020()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [10, 20])->orderBy('stok', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->produk->nama . ' ' . $d->nama;
                })
                ->addColumn('jml', function ($d) {
                    return $d->stok . ' ' . $d->satuan->nama;
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukstok59()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [5, 9])->orderBy('stok', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->produk->nama . ' ' . $d->nama;
                })
                ->addColumn('jml', function ($d) {
                    return $d->stok . ' ' . $d->satuan->nama;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal2" data-attr=""  data-id="' . $d->gdg_brg_jadi_id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukstok14()
    {
        try {
            $data = GudangBarangJadi::with('produk')->whereBetween('stok', [1, 4])->orderBy('stok', 'asc')->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->produk->nama . ' ' . $d->nama;
                })
                ->addColumn('jml', function ($d) {
                    return $d->stok . ' ' . $d->satuan->nama;
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukIn36()
    {
        try {
            $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 6 MONTH ) and date_sub(now(), INTERVAL 3 MONTH)');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {

                        $a = Carbon::now()->diffInDays($d->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukIn612()
    {
        try {
            $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 12 MONTH ) and date_sub(now(), INTERVAL 6 MONTH)');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {

                        $a = Carbon::now()->diffInDays($d->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProduk1236()
    {
        try {
            $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
            from t_gbj_detail tgd
            left join t_gbj tg on tg.id = tgd.t_gbj_id
            left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            where tgd.jenis = "masuk" and tg.tgl_masuk BETWEEN date_sub(now(), INTERVAL 36 MONTH ) and date_sub(now(), INTERVAL 12 MONTH)');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {

                        $a = Carbon::now()->diffInDays($d->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProduk36Plus()
    {
        try {
            $data = DB::select('SELECT tg.tgl_masuk, CONCAT(p.nama," ", gbj.nama) as prduk, CONCAT(tgd.qty," ",ms.nama) as qty, tgd.id
                from t_gbj_detail tgd
                left join t_gbj tg on tg.id = tgd.t_gbj_id
                left join gdg_barang_jadi gbj on gbj.id = tgd.gdg_brg_jadi_id
                left join produk p on p.id = gbj.produk_id
                left join m_satuan ms on ms.id = gbj.satuan_id
                where tgd.jenis = "masuk" and tg.tgl_masuk = date_sub(now(), interval 3 YEAR)');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->tgl_masuk)) {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY');
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->rawColumns(['tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getProdukByLayout(Request $request)
    {
        try {
            $data = DB::select('SELECT CONCAT(p.nama," ",gbj.nama) as prduk, CONCAT(COUNT(nbj.layout_id)," ",ms.nama)  as jumlah, nbj.layout_id, ml.ruang
            from noseri_barang_jadi nbj
            left join m_layout ml on ml.id = nbj.layout_id
            left join gdg_barang_jadi gbj on gbj.id = nbj.gdg_barang_jadi_id
            left join produk p on p.id = gbj.produk_id
            left join m_satuan ms on ms.id = gbj.satuan_id
            group by nbj.gdg_barang_jadi_id, nbj.layout_id');
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('prd', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jml', function ($d) {
                    return $d->jumlah;
                })
                ->addColumn('layout', function ($d) {
                    if (isset($d->layout_id)) {
                        return $d->ruang;
                    } else {
                        return '-';
                    }
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // penerimaan
    function hh1()
    {
        $data = DB::table(DB::raw('t_gbj_detail tgd'))
            ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
            ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
            ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
            ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
            ->where('tgd.jenis', '=', 'masuk')
            ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 1 day)')
            ->get();
        return count($data);
    }

    function hh2()
    {
        $data = DB::table(DB::raw('t_gbj_detail tgd'))
            ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
            ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
            ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
            ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
            ->where('tgd.jenis', '=', 'masuk')
            ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 2 day)')
            ->get();
        return count($data);
    }

    function hh3()
    {
        $data = DB::table(DB::raw('t_gbj_detail tgd'))
            ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
            ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
            ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
            ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
            ->where('tgd.jenis', '=', 'masuk')
            ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 3 day)')
            ->get();
        return count($data);
    }

    function getPenerimaanProduk1(Request $request)
    {
        try {
            $data = DB::table(DB::raw('t_gbj_detail tgd'))
                ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
                ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where('tgd.jenis', '=', 'masuk')
                ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 1 day)')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        $c = Carbon::now()->diffInDays($d->tgl_masuk);
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->prduk . '" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getPenerimaanProduk2(Request $request)
    {
        try {
            $data = DB::table(DB::raw('t_gbj_detail tgd'))
                ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
                ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where('tgd.jenis', '=', 'masuk')
                ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 2 day)')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        $c = Carbon::now()->diffInDays($d->tgl_masuk);
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->prduk . '" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getPenerimaanProduk3(Request $request)
    {
        try {
            $data = DB::table(DB::raw('t_gbj_detail tgd'))
                ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
                ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where('tgd.jenis', '=', 'masuk')
                ->whereRaw('tg.tgl_masuk = date_sub(now(), interval 3 day)')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if (isset($d->header->tgl_masuk)) {
                        $c = Carbon::now()->diffInDays($d->tgl_masuk);
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $c . ' Hari</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-brg="' . $d->prduk . '" data-attr=""  data-id="' . $d->id . '">
                                <button class="btn btn-outline-primary" type="button" >
                                <i class="fas fa-paper-plane"></i>
                                </button>
                            </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function getPenerimaanAll()
    {
        try {
            $data = DB::table(DB::raw('t_gbj_detail tgd'))
                ->select('tg.tgl_masuk', DB::raw('CONCAT(p.nama," ",gbj.nama) as prduk'), 'tgd.qty', 'tgd.id')
                ->leftJoin(DB::raw('t_gbj tg'), 'tg.id', '=', 'tgd.t_gbj_id')
                ->leftJoin(DB::raw('gdg_barang_jadi gbj'), 'gbj.id', '=', 'tgd.gdg_brg_jadi_id')
                ->leftJoin(DB::raw('produk p'), 'p.id', '=', 'gbj.produk_id')
                ->where('tgd.jenis', '=', 'masuk')
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('tgl_masuk', function ($d) {
                    if ($d->tgl_masuk) {

                        $a = Carbon::now()->diffInDays($d->tgl_masuk);

                        if ($a == 1) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-info">Lewat ' . $a . ' Hari</span>';
                        } else if ($a == 2) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-warning">Lewat ' . $a . ' Hari</span>';
                        } else if ($a >= 3) {
                            return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY') . '<br><span class="badge badge-danger">Lewat ' . $a . ' Hari</span>';
                        }
                    } else {
                        return Carbon::parse($d->tgl_masuk)->isoFormat('D MMM YYYY');
                    }
                })
                ->addColumn('product', function ($d) {
                    return $d->prduk;
                })
                ->addColumn('jumlah', function ($d) {
                    return $d->qty;
                })
                ->addColumn('action', function ($d) {
                    return  '<a data-toggle="modal" data-target="#editmodal" class="editmodal" data-attr="" data-brg="' . $d->prduk . '" data-id="' . $d->id . '">
                            <button class="btn btn-outline-primary" type="button" >
                            <i class="fas fa-paper-plane"></i>
                            </button>
                        </a>';
                })
                ->rawColumns(['action', 'tgl_masuk'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    // penjualan
    function he1()
    {
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select(
                'p.id',
                'p.so',
                'p.no_po',
                'p.log_id',
                DB::raw('count(dpp.gudang_barang_jadi_id)'),
                DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                'ms.nama as log_nama',
                DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
            )
            ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
            ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
            ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
            ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
            ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end = date_sub(now(), interval 1 day)')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function he2()
    {
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select(
                'p.id',
                'p.so',
                'p.no_po',
                'p.log_id',
                DB::raw('count(dpp.gudang_barang_jadi_id)'),
                DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                'ms.nama as log_nama',
                DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
            )
            ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
            ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
            ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
            ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
            ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end = date_sub(now(), interval 2 day)')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function he3()
    {
        $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
            ->select(
                'p.id',
                'p.so',
                'p.no_po',
                'p.log_id',
                DB::raw('count(dpp.gudang_barang_jadi_id)'),
                DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                'ms.nama as log_nama',
                DB::raw("case
        when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
        when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
        when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
        when p.so is null then c_ekat.nama
        end as divisi")
            )
            ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
            ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
            ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
            ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
            ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
            ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
            ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
            ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end <= date_sub(now(), interval 3 day)')
            ->groupBy('p.id')
            ->get();

        return count($data);
    }

    function list_tf1()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
                ->select(
                    'p.id',
                    'p.so',
                    'p.no_po',
                    'p.log_id',
                    DB::raw('count(dpp.gudang_barang_jadi_id)'),
                    DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                    DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                    DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                    'ms.nama as log_nama',
                    DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
                )
                ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
                ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
                ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
                ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
                ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
                ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
                ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end = date_sub(now(), interval 1 day)')
                ->groupBy('p.id')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('tgl_batas', function ($d) {
                    if ($d->batas) {
                        return $d->batas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log_nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_tf2()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
                ->select(
                    'p.id',
                    'p.so',
                    'p.no_po',
                    'p.log_id',
                    DB::raw('count(dpp.gudang_barang_jadi_id)'),
                    DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                    DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                    DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                    'ms.nama as log_nama',
                    DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
                )
                ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
                ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
                ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
                ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
                ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
                ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
                ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end = date_sub(now(), interval 2 day)')
                ->groupBy('p.id')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('tgl_batas', function ($d) {
                    if ($d->batas) {
                        return $d->batas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log_nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function list_tf3()
    {
        try {
            $data = DB::table(DB::raw('detail_pesanan_produk dpp'))
                ->select(
                    'p.id',
                    'p.so',
                    'p.no_po',
                    'p.log_id',
                    DB::raw('count(dpp.gudang_barang_jadi_id)'),
                    DB::raw('sum(case when dpp.status_cek = 4 then 1 else 0 end) as total_cek'),
                    DB::raw('sum(case when dpp.status_cek is null then 1 else 0 end) as total_uncek'),
                    DB::raw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end as batas'),
                    'ms.nama as log_nama',
                    DB::raw("case
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPA' then c_spa.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'SPB' then c_spb.nama
            when substring_index(substring_index(p.so, '/', 2), '/', -1) = 'EKAT' then c_ekat.nama
            when p.so is null then c_ekat.nama
            end as divisi")
                )
                ->leftJoin(DB::raw('detail_pesanan dp'), 'dpp.detail_pesanan_id', '=', 'dp.id')
                ->leftJoin(DB::raw('pesanan p'), 'dp.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('ekatalog e'), 'e.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('provinsi p2'), 'p2.id', '=', 'e.provinsi_id')
                ->leftJoin(DB::raw('m_state ms'), 'ms.id', '=', 'p.log_id')
                ->leftJoin(DB::raw('spa s'), 's.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('spb s2'), 's2.pesanan_id', '=', 'p.id')
                ->leftJoin(DB::raw('customer c_ekat'), 'c_ekat.id', '=', 'e.customer_id')
                ->leftJoin(DB::raw('customer c_spa'), 'c_spa.id', '=', 's.customer_id')
                ->leftJoin(DB::raw('customer c_spb'), 'c_spb.id', '=', 's2.customer_id')
                ->whereRaw('case when p2.status = 1 then DATE_SUB(e.tgl_kontrak, INTERVAL 35 DAY) else DATE_SUB(e.tgl_kontrak, INTERVAL 28 DAY) end <= date_sub(now(), interval 3 day)')
                ->groupBy('p.id')
                ->get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->no_po;
                })
                ->addColumn('nama_customer', function ($data) {
                    return $data->divisi;
                })
                ->addColumn('tgl_batas', function ($d) {
                    if ($d->batas) {
                        return $d->batas;
                    } else {
                        return '-';
                    }
                })
                ->addColumn('status_penjualan', function ($data) {
                    if ($data->log_id) {
                        return '<span class="badge badge-light">' . $data->log_nama . '</span>';
                    } else {
                        return '-';
                    }
                })
                ->addColumn('action', function ($d) {
                    $x = explode('/', $d->so);
                    for ($i = 1; $i < count($x); $i++) {
                        if ($x[1] == 'EKAT') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="ekatalog"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPA') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spa"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        } elseif ($x[1] == 'SPB') {
                            return '<a data-toggle="modal" data-target="#salemodal" class="salemodal" data-attr="" data-value="spb"  data-id="' . $d->id . '">
                                        <button class="btn btn-outline-primary" type="button" >
                                            <i class="fas fa-paper-plane"></i>
                                        </button>
                                    </a>';
                        }
                    }
                })
                ->rawColumns(['action', 'tgl_batas', 'status_penjualan'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function detailsale($id, $value)
    {
        try {
            if ($value == "ekatalog") {
                $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Ekatalog', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $detail_id = array();
                foreach ($detail_pesanan as $d) {
                    $detail_id[] = $d->id;
                }

                $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
            } else if ($value == "spa") {
                $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spa', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $detail_id = array();
                foreach ($detail_pesanan as $d) {
                    $detail_id[] = $d->id;
                }

                $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
            } else if ($value == "spb") {
                $detail_pesanan  = DetailPesanan::whereHas('Pesanan.Spb', function ($q) use ($id) {
                    $q->where('pesanan_id', $id);
                })->get();
                $detail_id = array();
                foreach ($detail_pesanan as $d) {
                    $detail_id[] = $d->id;
                }

                $g = DetailPesananProduk::whereIn('detail_pesanan_id', $detail_id)->get();
            }

            return datatables()->of($g)
                ->addIndexColumn()
                ->addColumn('nama_produk', function ($data) {
                    if (empty($data->gudangbarangjadi->nama)) {
                        return $data->gudangbarangjadi->produk->nama;
                    } else {
                        return $data->gudangbarangjadi->produk->nama . ' ' . $data->gudangbarangjadi->nama;
                    }
                })
                ->addColumn('jumlah', function ($data) {
                    return $data->detailpesanan->jumlah . ' ' . $data->gudangbarangjadi->satuan->nama;
                })
                ->addColumn('tipe', function ($data) {
                    return $data->gudangbarangjadi->produk->nama;
                })
                ->addColumn('merk', function ($data) {
                    return $data->gudangbarangjadi->produk->merk;
                })
                ->addColumn('button', function ($data) {
                    return '<a type="button" class="noserishow" data-id="' . $data->gudang_barang_jadi_id . '"><i class="fas fa-search"></i></a>';
                })
                ->rawColumns(['button'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function outSO()
    {
        try {
            $data = DB::table('detail_pesanan_produk as dpp')
                ->select(DB::raw('concat(p.nama, " ", gbj.nama) as prd'), DB::raw('sum(dp.jumlah) as jumlah'), 'gbj.stok')
                ->join('gdg_barang_jadi as gbj', 'gbj.id', '=', 'dpp.gudang_barang_jadi_id')
                ->join('detail_pesanan as dp', 'dp.id', '=', 'dpp.detail_pesanan_id')
                ->join('produk as p', 'p.id', '=', 'gbj.produk_id')
                ->where('jumlah', '>', 'gbj.stok')
                ->groupBy('dpp.gudang_barang_jadi_id')
                ->orderBy(DB::raw('concat(p.nama, " ", gbj.nama)'))
                ->get();
            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('produk', function ($d) {
                    return $d->prd;
                })
                ->addColumn('permintaan', function ($d) {
                    return $d->jumlah;
                })
                ->addColumn('current_stok', function ($d) {
                    return $d->stok;
                })
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function updateSeriLayout(Request $request)
    {
        try {
            $data = NoseriBarangJadi::whereIn('id', $request->cekid)->get();
            foreach ($data as $d) {
                for ($i = 0; $i < count($request->layout); $i++) {
                    NoseriBarangJadi::where('id', $request->cekid[$i])->update(['layout_id' => json_decode($request->layout[$i], true)]);
                }
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function addSeri(Request $request)
    {
        try {
            $count = count($request->no_seri);
            for ($i = 0; $i < $count; $i++) {
                NoseriBarangJadi::create([
                    'noseri' => strtoupper($request->no_seri[$i]),
                    'layout_id' => $request->layout[$i],
                    'gdg_barang_jadi_id' => $request->id,
                    'dari' => $request->dari,
                    'created_by' => $request->created_by,
                    'jenis' => 'MASUK',
                    'is_aktif' => 1
                ]);
            }

            $a = GudangBarangJadi::where('id', $request->id)->first();
            $stok = $a->stok + $count;
            // return $stok;
            GudangBarangJadi::where('id', $request->id)->update(['stok' => $stok]);

            $pid = GudangBarangJadi::find($request->id)->produk_id;
            $obj_seri = [
                $request->no_seri
            ];
            $obj = [
                'produk' => Produk::find($pid)->nama . ' ' . GudangBarangJadi::find($request->id)->nama,
                'jumlah' => $count,
                'noseri' => $request->no_seri,
            ];

            SystemLog::create([
                'tipe' => 'GBJ',
                'subjek' => 'Penambahan Noseri Produk',
                'response' => json_encode($obj),
                'user_id' => $request->created_by,
            ]);
            return response()->json(['success' => 'Sukses', 'error' => false]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    public function getHariBatasKontrak($value, $limit)
    {
        if ($limit == 2) {
            $days = '35';
        } else {
            $days = '28';
        }
        return Carbon::parse($value)->subDays($days);
    }

    function TfbySOFinal(Request $request)
    {
        try {
            $a = TFProduksi::where('pesanan_id', $request->pesanan_id)->first();
            if ($a) {
                foreach ($request->data as $key => $values) {
                    $c = TFProduksiDetail::where('t_gbj_id', $a->id)->where('gdg_brg_jadi_id', $values['prd'])->where('detail_pesanan_produk_id', $key)->first();
                    if ($c) {
                        // return 'aa';
                        foreach ($values['noseri'] as $k => $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $c->id,
                                'noseri_id' => $v,
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => $request->userid
                            ]);

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['prd']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - $values['jumlah'];
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => $values['jumlah'],
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $request->userid,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }

                        $obj = [
                            'produk' => Produk::find(GudangBarangJadi::find($values['prd'])->produk_id)->nama . ' ' . GudangBarangJadi::find($values['prd'])->nama,
                            'dpp_id' => $key,
                            'pesanan_so' => Pesanan::find($request->pesanan_id)->so,
                            'pesanan_po' => Pesanan::find($request->pesanan_id)->no_po,
                            'jumlah' => count($values['noseri']),
                            'noseri' => NoseriBarangJadi::whereIn('id', $values['noseri'])->get()->pluck('noseri'),
                            'tgl_keluar' => Carbon::now()
                        ];

                        SystemLog::create([
                            'tipe' => 'GBJ',
                            'subjek' => 'Sales Order Noseri By Sistem',
                            'response' => json_encode($obj),
                            'user_id' => $request->userid
                        ]);
                    } else {
                        $dd = TFProduksiDetail::create([
                            't_gbj_id' => $a->id,
                            'detail_pesanan_produk_id' => $key,
                            'gdg_brg_jadi_id' => $values['prd'],
                            'qty' => $values['jumlah'],
                            'jenis' => 'keluar',
                            'status_id' => 2,
                            'state_id' => 8,
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        $did = $dd->id;
                        $checked = $request->noseri_id;
                        foreach ($values['noseri'] as $k => $v) {
                            NoseriTGbj::create([
                                't_gbj_detail_id' => $did,
                                'noseri_id' => $v,
                                'status_id' => 2,
                                'state_id' => 8,
                                'jenis' => 'keluar',
                                'created_at' => Carbon::now(),
                                'created_by' => $request->userid
                            ]);

                            NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                        }

                        $gdg = GudangBarangJadi::whereIn('id', [$values['prd']])->get()->toArray();
                        $i = 0;
                        foreach ($gdg as $vv) {
                            $vv['stok'] = $vv['stok'] - $values['jumlah'];
                            // print_r($vv['stok']);
                            $i++;
                            GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                            GudangBarangJadiHis::create([
                                'gdg_brg_jadi_id' => $vv['id'],
                                'stok' => $values['jumlah'],
                                'tgl_masuk' => Carbon::now(),
                                'jenis' => 'KELUAR',
                                'created_by' => $request->userid,
                                'created_at' => Carbon::now(),
                                'ke' => 23,
                                'tujuan' => $request->deskripsi,
                            ]);
                        }

                        $obj = [
                            'produk' => Produk::find(GudangBarangJadi::find($values['prd'])->produk_id)->nama . ' ' . GudangBarangJadi::find($values['prd'])->nama,
                            'dpp_id' => $key,
                            'pesanan_so' => Pesanan::find($request->pesanan_id)->so,
                            'pesanan_po' => Pesanan::find($request->pesanan_id)->no_po,
                            'jumlah' => count($values['noseri']),
                            'noseri' => NoseriBarangJadi::whereIn('id', $values['noseri'])->get()->pluck('noseri'),
                            'tgl_keluar' => Carbon::now()
                        ];

                        SystemLog::create([
                            'tipe' => 'GBJ',
                            'subjek' => 'Sales Order Noseri By Sistem',
                            'response' => json_encode($obj),
                            'user_id' => $request->userid
                        ]);
                    }
                }
            } else {
                // return 'b';
                $d = TFProduksi::create([
                    'pesanan_id' => $request->pesanan_id,
                    'tgl_keluar' => Carbon::now(),
                    'ke' => 23,
                    'jenis' => 'keluar',
                    'status_id' => 2,
                    'state_id' => 8,
                    'created_at' => Carbon::now(),
                    'created_by' => $request->userid
                ]);

                $hid = $d->id;
                foreach ($request->data as $key1 => $value1) {
                    $dd = TFProduksiDetail::create([
                        't_gbj_id' => $hid,
                        'detail_pesanan_produk_id' => $key1,
                        'gdg_brg_jadi_id' => $value1['prd'],
                        'qty' => $value1['jumlah'],
                        'jenis' => 'keluar',
                        'status_id' => 2,
                        'state_id' => 8,
                        'created_at' => Carbon::now(),
                        'created_by' => $request->userid
                    ]);

                    $did = $dd->id;
                    $checked = $request->noseri_id;
                    foreach ($value1['noseri'] as $k => $v) {
                        NoseriTGbj::create([
                            't_gbj_detail_id' => $did,
                            'noseri_id' => $v,
                            'status_id' => 2,
                            'state_id' => 8,
                            'jenis' => 'keluar',
                            'created_at' => Carbon::now(),
                            'created_by' => $request->userid
                        ]);

                        NoseriBarangJadi::find($v)->update(['is_ready' => 1, 'used_by' => $request->pesanan_id]);
                    }

                    $gdg = GudangBarangJadi::whereIn('id', [$key1])->get()->toArray();
                    $i = 0;
                    foreach ($gdg as $vv) {
                        $vv['stok'] = $vv['stok'] - $value1['jumlah'];
                        // print_r($vv['stok']);
                        $i++;
                        GudangBarangJadi::find($vv['id'])->update(['stok' => $vv['stok']]);
                        GudangBarangJadiHis::create([
                            'gdg_brg_jadi_id' => $vv['id'],
                            'stok' => $value1['jumlah'],
                            'tgl_masuk' => Carbon::now(),
                            'jenis' => 'KELUAR',
                            'created_by' => $request->userid,
                            'created_at' => Carbon::now(),
                            'ke' => 23,
                            'tujuan' => $request->deskripsi,
                        ]);
                    }
                }

                $obj = [
                    'data' => $request->data,
                    'pesanan_so' => Pesanan::find($request->pesanan_id)->so,
                    'pesanan_po' => Pesanan::find($request->pesanan_id)->no_po,
                    'tgl_keluar' => Carbon::now()
                ];

                SystemLog::create([
                    'tipe' => 'GBJ',
                    'subjek' => 'Sales Order Noseri By Sistem',
                    'response' => json_encode($obj),
                    'user_id' => $request->userid
                ]);
            }

            $po = Pesanan::find($request->pesanan_id);

            if ($po->getJumlahPesanan() == $po->cekJumlahkirim()) {
                Pesanan::find($request->pesanan_id)->update(['log_id' => 8]);
            } else {
                Pesanan::find($request->pesanan_id)->update(['log_id' => 6]);
            }
            return response()->json(['msg' => 'Data Terkirim ke QC']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function get_so_batal()
    {
        try {
            $Ekatalog = collect(Pesanan::has('Ekatalog')->whereIn('log_id', [20])->get());
            $Spa = collect(Pesanan::has('Spa')->whereIn('log_id', [20])->get());
            $Spb = collect(Pesanan::has('Spb')->whereIn('log_id', [20])->get());

            $data = $Ekatalog->merge($Spa)->merge($Spb);
            $x = [];
            foreach ($data as $k) {
                $x[] = $k->id;
            }

            $datax = TFProduksi::whereIn('pesanan_id', $x)->get();

            return datatables()->of($datax)
                ->addIndexColumn()
                ->addColumn('so', function ($data) {
                    return $data->pesanan->so;
                })
                ->addColumn('no_po', function ($data) {
                    return $data->pesanan->no_po;
                })
                ->addColumn('nama_customer', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    for ($i = 1; $i < count($name); $i++) {
                        if ($name[1] == 'EKAT') {
                            return $data->pesanan->Ekatalog->Customer->nama;
                        } elseif ($name[1] == 'SPA') {
                            return $data->pesanan->Spa->Customer->nama;
                        } elseif ($name[1] == 'SPB') {
                            return $data->pesanan->Spb->Customer->nama;
                        }
                    }
                })
                ->addColumn('aksi', function ($data) {
                    $name = explode('/', $data->pesanan->so);
                    for ($i = 1; $i < count($name); $i++) {
                        if ($name[1] == 'EKAT') {
                            $a = '<a data-toggle="modal" data-target="#btndetail" class="btndetail" data-attr="" data-value="ekatalog"  data-id="' . $data->pesanan->id . '" data-alasan="' . $data->pesanan->ket_batal . '" data-tgl="' . Carbon::createFromFormat('Y-m-d', $data->pesanan->tgl_batal)->isoFormat('D MMMM YYYY') . '">
                                    <button class="btn btn-outline-info btn-sm" type="button">
                                        <i class="fas fa-eye"></i>&nbsp;Detail
                                    </button>
                                </a>';
                        } elseif ($name[1] == 'SPA') {
                            $a = '<a data-toggle="modal" data-target="#btndetail" class="btndetail" data-attr="" data-value="spa"  data-id="' . $data->pesanan->id . '" data-alasan="' . $data->pesanan->ket_batal . '" data-tgl="' . Carbon::createFromFormat('Y-m-d', $data->pesanan->tgl_batal)->isoFormat('D MMMM YYYY') . '">
                                    <button class="btn btn-outline-info btn-sm" type="button">
                                        <i class="fas fa-eye"></i>&nbsp;Detail
                                    </button>
                                </a>';
                        } elseif ($name[1] == 'SPB') {
                            $a = '<a data-toggle="modal" data-target="#btndetail" class="btndetail" data-attr="" data-value="spb"  data-id="' . $data->pesanan->id . '" data-alasan="' . $data->pesanan->ket_batal . '" data-tgl="' . Carbon::createFromFormat('Y-m-d', $data->pesanan->tgl_batal)->isoFormat('D MMMM YYYY') . '">
                                    <button class="btn btn-outline-info btn-sm" type="button">
                                        <i class="fas fa-eye"></i>&nbsp;Detail
                                    </button>
                                </a>';
                        }
                    }
                    return $a;
                })
                ->addColumn('logs', function ($d) {
                    if ($d->pesanan->log_id == 9) {
                        $ax = "<span class='badge badge-pill badge-secondary'>" . $d->pesanan->log->nama . "</span>";
                    } else if ($d->pesanan->log_id == 6) {
                        $ax = "<span class='badge badge-pill badge-warning'>" . $d->pesanan->log->nama . "</span>";
                    } elseif ($d->pesanan->log_id == 8) {
                        $ax = "<span class='badge badge-pill badge-info'>" . $d->pesanan->log->nama . "</span>";
                    } elseif ($d->pesanan->log_id == 11) {
                        $ax = "<span class='badge badge-pill badge-dark'>Logistik</span>";
                    } else {
                        $ax = "<span class='badge badge-pill badge-danger'>" . $d->pesanan->log->nama . "</span>";
                    }

                    return $ax;
                })
                ->rawColumns(['aksi', 'logs'])
                ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function proses_so_batal(Request $request)
    {
        try {
            $check = TFProduksi::where('pesanan_id', $request->pesananid)->first();
            if ($check) {
                $chk_detail = TFProduksiDetail::whereIn('t_gbj_id', [$check->id])->get();
                $did = [];
                foreach ($chk_detail as $detail) {
                    $did[] = $detail->id;
                }
                $nid = [];
                $nidd = [];
                $chk_noseri = NoseriTGbj::whereIn('t_gbj_detail_id', $did)->get();
                foreach ($chk_noseri as $noseri) {
                    $nid[] = $noseri->id;
                    $nidd[] = $noseri->noseri_id;
                }

                $seri = NoseriBarangJadi::whereIn('id', $nidd)->get();
                $seri_qc = NoseriDetailPesanan::whereIn('t_tfbj_noseri_id', $nid)->get();
                if (count($seri_qc) != 0) {
                    return response()->json([
                        'error' => true,
                        'msg' => 'Mohon Tunggu Proses Batal dari QC'
                    ]);
                } else {
                    NoseriBarangJadi::whereIn('id', $nidd)->update(['is_ready' => 0, 'used_by' => null]);
                    NoseriTGbj::whereIn('t_gbj_detail_id', $did)->delete();
                    TFProduksiDetail::where('t_gbj_id', $check->id)->delete();
                    TFProduksi::where('pesanan_id', $request->pesananid)->delete();
                    return response()->json([
                        'error' => false,
                        'msg' => 'Proses Restock Berhasil'
                    ]);
                }
            } else {
                return 'tidak ada';
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage(),
            ]);
        }
    }

    function export_noseri_gudang(Request $request)
    {
        return Excel::download(new NoseriGudangExport(), 'NoseriBarangJadi.xlsx');
    }

    function tfgbmp_store(Request $request)
    {
        //dd(count($request->detail_t_gbj));
        $validator = Validator::make($request->all(), [
            'no_transfer' => 'required',
            'divisi' => 'required',
            'tanggal' => 'required',
            'ket' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'gagal'
            ]);
        } else {
            $tf = TFProduksi::create([
                'no_transaksi' => $request->no_transfer,
                'tgl_keluar' => $request->tanggal,
                'ke' => $request->divisi,
                'dari' => 11,
                'status_id' => 1,
                'jenis' => 'keluar',
                'deskripsi' => $request->ket,
                'created_by' => 11,


            ]);
            for ($i = 0; $i < count($request->detail_t_gbj); $i++) {
                TFProduksiDetail::create([
                    't_gbj_id' => $tf->id,
                    'detail_stok_divisi_part_id' => $request->detail_t_gbj[$i]['detail_stok_divisi_part_id'],
                    'qty' => $request->detail_t_gbj[$i]['jumlah'],
                    'jenis' => 'keluar',
                ]);
            }

            return response()->json([
                'status' => 'berhasil'
            ]);
        }
    }

    function tfgbmp_data()
    {
        $data = array();
        $tf = TFProduksi::with(['Divisi'])->where('ke', 11)->orWhere('dari', 11)->get();
        foreach ($tf as $key => $d) {
            $data[$key] = array(
                'id' => $d->id,
                'no_transaksi' => $d->no_transaksi,
                'divisi' => $d->ke == 11 ?  $d->darii->nama : $d->divisi->nama,
                'tanggal_transfer' => $d->tgl_masuk == NULL ? Carbon::parse($d->tgl_keluar)->format('d M Y')  : Carbon::parse($d->tgl_masuk)->format('d M Y'),
                'jenis' => $d->jenis,
                'status' => $d->Status->nama,
                'ket' => $d->deskripsi
            );
        }

        return response()->json(['data' => $data]);
    }
    function tfgbmp_detail($id)
    {
        $data = array();
        $tf = TFProduksi::with(['Divisi'])->find($id);
        $data['header'] = array(
            'id' => $tf->id,
            'no_transaksi' => $tf->no_transaksi,
            'divisi' => $tf->ke == 11 ?  $tf->darii->nama : $tf->divisi->nama,
            'tanggal_transfer' => $tf->tgl_masuk == NULL ? Carbon::parse($tf->tgl_keluar)->format('d M Y')  : Carbon::parse($tf->tgl_masuk)->format('d M Y'),
            'jenis' => $tf->jenis,
            'status' => $tf->Status->nama,
            'ket' => $tf->deskripsi
        );

        foreach ($tf->detail as $key_b => $e) {
            $data['header']['part'][$key_b] = array(
                'id' => $e->id,
                'lot_number' => $e->DetailStokDivisiPart->Lotnumber->number,
                'nama' => $e->DetailStokDivisiPart->StokDivisiPart->Sparepart->nama,
                'stok' => $e->qty
            );
        }


        return response()->json(['data' => $data]);
    }
}
