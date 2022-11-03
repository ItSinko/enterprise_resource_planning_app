<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

use App\Models\inventory\Alatuji;
use App\Models\inventory\Peminjaman;
use App\Models\inventory\AlatSN;
use App\Models\inventory\Klasifikasi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToArray;
use Illuminate\Validation\ValidationException;

class AlatujiController extends Controller
{
    function gantiNama($date,  $r, $fileNM){

        //ambil nama dan extensi file
        $a = $r->file($fileNM)->getClientOriginalName();

        //ganti char + menjadi _
        $a = str_replace("+","_",$a);

        //ambil extensi file
        $aExt = $r->file($fileNM)->extension();

        //hilangkan extensi pada nama full, tambah tanggal, tambah extensi file
        return
        pathinfo($a, PATHINFO_FILENAME).'_'.$date.'.'.$aExt;
    }

    function countTglLebih($data, $keys){
        $a = count($keys);
        $count = 0;
        for($i=1;$i<=$a;$i++){
            $b = Carbon::createFromFormat('Y-m-d' ,$data[$keys[$i-1]]->jadwal_perawatan);
            if($b->lt(Carbon::now())){
                $count++;
            }
        }
        return $count;
    }

    public function dashboard()
    {
        $total = AlatSN::count();
        $ter = AlatSN::where('status_pinjam_id', 16)->count();
        $req = AlatSN::where('status_pinjam_id', 17)->count();
        $not = AlatSN::where('kondisi_id', 10)->count();
        $use = AlatSN::where('status_pinjam_id', 15)->count();
        $ext = AlatSN::where('status_pinjam_id', 14)->count();
        $mel = DB::table('erp_kalibrasi.peminjaman')->where('tgl_batas', '>', Carbon::now()->format('Y-m-d', 'Asia/Jakarta'))->where('status_id', '15')->count();
        $ve1 = DB::table('erp_kalibrasi.verifikasi')->select(DB::raw('count(DISTINCT serial_number_id) as total'))->whereMonth('jadwal_perawatan', Carbon::now()->month)->whereYear('jadwal_perawatan', Carbon::now()->year)->get();
        $pe1 = DB::table('erp_kalibrasi.perawatan')->select(DB::raw('count(DISTINCT serial_number_id) as total'))->whereMonth('jadwal_perawatan', Carbon::now()->month)->whereYear('jadwal_perawatan', Carbon::now()->year)->get();
        $ve2 = DB::table('erp_kalibrasi.verifikasi')->select(DB::raw('count(DISTINCT serial_number_id) as total'))->where('jadwal_perawatan', '<', Carbon::now()->format('Y-m'))->get();
        $pe2 = DB::table('erp_kalibrasi.perawatan')->select(DB::raw('count(DISTINCT serial_number_id) as total'))->where('jadwal_perawatan', '<', Carbon::now()->format('Y-m'))->get();
        $ve3 = DB::table('erp_kalibrasi.verifikasi')->select(DB::raw('count(DISTINCT serial_number_id) as total'))->where('jadwal_perawatan', '>=', Carbon::now()->addMonth(1)->format('Y-m'))->where('jadwal_perawatan', '<=', Carbon::now()->addMonth(2)->format('Y-m'))->get();
        $pe3 = DB::table('erp_kalibrasi.perawatan')->select(DB::raw('count(DISTINCT serial_number_id) as total'))->where('jadwal_perawatan', '>=', Carbon::now()->addMonth(1)->format('Y-m'))->where('jadwal_perawatan', '<=', Carbon::now()->addMonth(2)->format('Y-m'))->get();

        $totalPeminjaman = array();
        for($i=1;$i<=12;$i++){
            $a = Peminjaman::whereMonth('tgl_kembali', date($i))->whereYear('tgl_kembali', date('Y'))->count();
            array_push($totalPeminjaman, $a);
        }

        $data = [
            'total' => $total,
            'tersedia' => $ter,
            'permintaan' => $req,
            'not' => $not,
            'dipinjam' => $use,
            'external' => $ext,
            'batasPinjam' => $mel,
            'verifikasiNow' => $ve1[0]->total,
            'perawatanNow' => $pe1[0]->total,
            'verifikasiOld' => $ve2[0]->total,
            'perawatanOld' => $pe2[0]->total,
            'verifikasiNext' => $ve3[0]->total,
            'perawatanNext' => $pe3[0]->total,
            'total_peminjaman' => $totalPeminjaman,
        ];
        return view('page.lab.dashboard', [
            'data' => json_encode($data),
        ]);
    }

    function get_data_alatuji()
    {
        try {
            $data = DB::table(DB::raw('erp_kalibrasi.alatuji_sn as2'))
                    ->select(DB::raw('concat(a.kd_alatuji,"-",as2.no_urut) as kode_alat'),
                    'as2.serial_number','s.nama_merk','as2.tgl_masuk', 'as2.id_serial_number',
                    DB::raw('concat(ml.ruang,"/",ml.rak) as lokasi'),'as2.kondisi_id', 'a.sop_alatuji', 'a.manual_alatuji',
                    'ms.nama as kondisi','a.id_alatuji','k.nama_klasifikasi','a.nm_alatuji', 'as2.status_pinjam_id')
                    ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'),'a.id_alatuji','=','as2.alatuji_id')
                    ->leftJoin(DB::raw('erp_kalibrasi.merk s'),'s.id_merk','=','as2.merk_id')
                    ->leftJoin(DB::raw('erp_spa.m_layout ml'),'ml.id','=','as2.layout_id')
                    ->leftJoin(DB::raw('erp_spa.m_status ms'),'ms.id','=','as2.kondisi_id')
                    ->leftJoin(DB::raw('erp_kalibrasi.klasifikasi k'),'k.id_klasifikasi','=','a.klasifikasi_id')
                    ->get();

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('kondisi_id', function($d){
                        return $d->kondisi_id == 9 ?
                        '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                        <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                        </div>'
                        :
                        '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                        <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                        </div>';
                    })
                    ->editColumn('status', function($d){
                        if($d->status_pinjam_id == 16){
                            return
                            '<span class="badge w-100 bc-success">
                                <span class="text-success">Tersedia</span>
                            </span>';
                        }elseif($d->status_pinjam_id == 15){
                            return
                            '<span class="badge w-100 bc-primary">
                            <span class="text-primary">Sedang Di Pinjam</span>
                            </span>';
                        }elseif($d->status_pinjam_id == 17){
                            return
                            '<span class="badge w-100 bc-warning">
                            <span class="text-warning">Pengajuan</span>
                            </span>';
                        }elseif($d->status_pinjam_id == 14){
                            return
                            '<span class="badge w-100 bc-warning">
                            <span class="text-warning">Eksternal</span>
                            </span>';
                        }else{
                            return
                            '<span class="badge w-100 bc-danger">
                            <span class="text-danger">Not Ok</span>
                            </span>';
                        }
                    })
                    ->editColumn('aksi', function($d){
                        return '
                        <a class="btn btn-sm btn-outline-primary py-0 w-100" href="/lab/alatuji/detail/'.$d->id_serial_number.'">
                        Detail
                        </a>';
                    })
                    ->editColumn('sop_alatuji', function($d){
                        return $d->sop_alatuji !=null ? $d->sop_alatuji : 'Dokumen Belum di Cantumkan';
                    })
                    ->editColumn('manual_alatuji', function($d){
                        return $d->manual_alatuji !=null ? $d->manual_alatuji : 'Dokumen Belum di Cantumkan';
                    })
                    ->editColumn('nama_klasifikasi', function($d){
                        return $d->nama_klasifikasi.' - klasifikasi';
                    })
                    ->editColumn('nm_alatuji', function($d){
                        return $d->nm_alatuji.' - nama';
                    })
                    ->rawColumns(['kondisi_id', 'status', 'aksi'])
                    ->make(true);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function tambahalat()
    {
        $klasifikasi = DB::table('erp_kalibrasi.klasifikasi')->get();
        $satuan = DB::table('erp_spa.m_satuan')->whereNotIn('id', [1,2,3])->get();

        return view('page.lab.tambah_alat', [
            'klasifikasi' => $klasifikasi,
            'satuan' => $satuan,
        ]);
    }

    function tambahbarang()
    {
        $klasifikasi = DB::table('erp_kalibrasi.klasifikasi')->select('nama_klasifikasi', 'id_klasifikasi')->get();
        $nama = DB::table('erp_kalibrasi.alatuji')->select('nm_alatuji', 'id_alatuji')->get();
        $merk = DB::table('erp_kalibrasi.merk')->select('id_merk', 'nama_merk')->get();
        $lokasi = DB::table('erp_spa.m_layout')->select('id', 'ruang')->whereNotIn('id', [1,2,3,4,5,6,7])->get();
        $lokasi = $lokasi->unique('ruang');

        return view('page.lab.tambah_barang', [
            'klasifikasi' => $klasifikasi,
            'nama' => $nama,
            'merk' => $merk,
            'lokasi' => $lokasi,
        ]);
    }

    function get_data_no_urut($alatuji_id){
        // cek nomor urut serial number alat uji
        $nourut = AlatSN::where('alatuji_id', $alatuji_id)->get();
        $nourut = $nourut->map(function($item, $key) {
            return (int)$item->no_urut;
        });
        return
        $nourut->isNotEmpty() ? max($nourut->toArray()) : 1;
    }

    function edit_alat($id)
    {
        $data =
            DB::table(DB::raw('erp_kalibrasi.alatuji_sn as2'))
            ->select(
                'as2.serial_number', 'as2.alatuji_id' ,'as2.tgl_masuk',
                'as2.merk_id', 'as2.id_serial_number', 'as2.layout_id',
                'as2.no_urut', 'a.desk_alatuji', 'a.klasifikasi_id', 'as2.tipe')
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'),'a.id_alatuji','=','as2.alatuji_id')
            ->where('as2.id_serial_number', $id)->first();

        $klasifikasi = DB::table('erp_kalibrasi.klasifikasi')->select('nama_klasifikasi', 'id_klasifikasi')->get();
        $nama = DB::table('erp_kalibrasi.alatuji')->select('nm_alatuji', 'id_alatuji')->get();
        $merk = DB::table('erp_kalibrasi.merk')->select('id_merk', 'nama_merk')->get();
        $sn = DB::table('erp_kalibrasi.alatuji_sn')->where('id_serial_number', $id)->select('alatuji_id', 'tgl_masuk', 'serial_number')->first();
        $lokasi = DB::table('erp_spa.m_layout')->select('id', 'ruang')->whereNotIn('id', [1,2,3,4,5,6,7])->get();
        $lokasi = $lokasi->unique('ruang');

        return view('page.lab.edit_alat', [
            'data' => $data,
            'klasifikasi' => $klasifikasi,
            'nama' => $nama,
            'merk' => $merk,
            'sn' => $sn,
            'lokasi' => $lokasi,
        ]);
    }

    function store_editalat(Request $request)
    {

        //ambil data dokumen alatuji
        $doc_old = DB::table('erp_kalibrasi.alatuji')->select('manual_alatuji', 'sop_alatuji', 'gbr_alatuji')->where('id_alatuji', $request->id_alatuji)->first();
        
        // cek apakah nmor seri telah terdaftar
        // jika data old = data new -> tidak keluar peringatan
        // jika data old =/= data new -> cek serial number & update
        $SN_old = AlatSN::where('id_serial_number', $request->id_serial_number)->first();
        if($SN_old->serial_number != $request->serialNM){
            //data old =/= new
            $cek_sn = AlatSN::
            where('alatuji_id', $request->id_alatuji)
            ->where('merk_id', $request->merk)
            ->where('serial_number', $request->serialNM)
            ->count();
            if($cek_sn >= 1){
                throw ValidationException::withMessages(['serialNM' => 'Serial Number telah Terdaftar']);
            }
        }

        // update alatuji
        DB::table('erp_kalibrasi.alatuji')->where('id_alatuji', $request->id_alatuji)->update([
            'klasifikasi_id'   => $request->klasifikasi,
            'nm_alatuji'     => $request->namaalat,
            'desk_alatuji' => $request->fungsi,
        ]);

        //format no urut
        if($request->noUrut < 10){
            $request->noUrut = '0'.$request->noUrut;
        }

        // update alatuji_sn
        DB::table('erp_kalibrasi.alatuji_sn')->where('id_serial_number', $request->id_serial_number)->update([
            'merk_id' => $request->merk,
            'serial_number' => $request->serialNM,
            'tgl_masuk' => $request->tgl_masuk,
            'kondisi_id' => $request->kondisi,
            'layout_id' => $request->lokasi,
            'no_urut' => $request->noUrut
        ]);

        if($request->has('sop'))
        {
            $request->validate([
                'sop' => 'mimes:doc,pdf,docx,zip|max:10000'
            ]);

            $date = Carbon::now()->format('Y-m-d', 'Asia/Jakarta');
            $sop = $this->gantiNama($date, $request,'sop');

            // cek jika alat uji sudah memiliki dokumen, pindah di folder dokumen lama
            if($doc_old->sop_alatuji != null)
            {
                Storage::move('public/sop/'.$doc_old->sop_alatuji, 'public/dokumen_lama/sop_lama/'.$doc_old->sop_alatuji);
            }

            // update nama dokumen alatuji
            Alatuji::where('id_alatuji', $request->id_alatuji)->update([
                'sop_alatuji' => $sop,
            ]);

            // simpan dokumen alat uji
            $request->file('sop')->storeAs('public/sop', $sop);
        }

        if($request->has('manual'))
        {
            $request->validate([
                'manual' => 'mimes:doc,pdf,docx,zip|max:10000'
            ]);

            $date = Carbon::now()->format('Y-m-d', 'Asia/Jakarta');
            $manual = $this->gantiNama($date, $request,'manual');

            // cek jika alat uji sudah memiliki dokumen, pindah di folder dokumen lama
            if($doc_old->manual_alatuji != null)
            {
                Storage::move('public/manual/'.$doc_old->manual_alatuji, 'public/dokumen_lama/manual_lama/'.$doc_old->manual_alatuji);
            }

            // update nama dokumen alatuji
            Alatuji::where('id_alatuji', $request->id_alatuji)->update([
                'manual_alatuji' => $manual,
            ]);

            // simpan dokumen alat uji
            $request->file('manual')->storeAs('public/manual', $manual);
        }

        if($request->has('gambar'))
        {
            $request->validate([
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:2048'
            ]);

            $date = Carbon::now()->format('Y-m-d', 'Asia/Jakarta');
            $gmbr = $this->gantiNama($date, $request,'gambar');

            // cek jika alat uji sudah memiliki dokumen, pindah di folder dokumen lama
            if($doc_old->manual_alatuji != null)
            {
                Storage::move('public/gambar/'.$doc_old->gbr_alatuji, 'public/dokumen_lama/gambar_lama/'.$doc_old->gbr_alatuji);
            }

            // update nama dokumen alatuji
            Alatuji::where('id_alatuji', $request->id_alatuji)->update([
                'gbr_alatuji' => $gmbr,
            ]);

            // simpan dokumen alat uji
            $request->file('gambar')->storeAs('public/gambar', $gmbr);
        }

        // user log
        $obj = [
            'alat_uji' => $request->namaalat,
            'serial_num' => $request->serialNM,
            'tgl_edit' => Carbon::now()->format('Y-m-d'),
            'diedit_oleh' => auth()->user()->nama,
        ];

        DB::table('erp_spa.tbl_log')->insert([
            'tipe' => 'QC',
            'subjek' => 'Edit data alat uji - '.$request->namaalat,
            'response' => json_encode($obj),
            'user_id' => auth()->user()->id,
        ]);

        return back()->with(['editSuccess' => '1']);
    }

    function store_pinjam(Request $request)
    {

        $request->validate([
            'serial_number_id' => 'required',
            'tgl_peminjaman' => 'required',
            'tgl_pengembalian' => 'required',
        ],[
            'required' => 'kolom :attribute harus di isi'
        ]);

        $data = DB::table(DB::raw('erp_kalibrasi.alatuji_sn as2'))->select(
            'as2.serial_number', 'a.nm_alatuji'
        )
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'as2.alatuji_id')
        ->where('as2.id_serial_number', $request->serial_number_id)
        ->first();

        Peminjaman::create([
            'serial_number_id'  => $request->serial_number_id,
            'tgl_pinjam'        => $request->tgl_peminjaman,
            'tgl_batas'         => $request->tgl_pengembalian,
            'status_id'         => '17',
            'penanggung_jawab'  => $request->pj,
            'peminjam_id'       => auth()->user()->id,
            'created_by'        => auth()->user()->id,
        ]);

        AlatSN::find($request->serial_number_id)
        ->update(['status_pinjam_id' => '17']);

        // user log
        $obj = [
            'alat_uji' => $data->nm_alatuji,
            'serial_num' => $data->serial_number,
            'tgl_pinjam' => $request->tgl_peminjaman,
            'dipinjam_oleh' => auth()->user()->nama,
            'batas_kembali' => $request->tgl_pengembalian,
        ];

        DB::table('erp_spa.tbl_log')->insert([
            'tipe' => 'QC',
            'subjek' => 'Pinjam alat uji - '.$data->nm_alatuji,
            'response' => json_encode($obj),
            'user_id' => auth()->user()->id,
        ]);

        return redirect()->route('alatuji.detail', ['id' => $request->serial_number_id])->with(['success' => '1','pinjamSuccess' => '0']);
    }

    function peminjaman_hist($id, $role, $uid)
    {
        try{

            $data = '';
            if($role == 1){
                $data = DB::table(DB::raw('erp_kalibrasi.peminjaman p'))
                        ->select(
                            'u.nama', 'p.ket_tambahan', 'p.kondisi_awal', 'p.penanggung_jawab',
                            'p.kondisi_akhir', 'p.created_at', 'p.tgl_pinjam',
                            'p.tgl_kembali', 'p.tgl_batas', 'p.status_id', 'p.id_peminjaman'
                        )
                        ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', '=', 'p.peminjam_id')
                        ->where('p.serial_number_id', '=', $id)
                        ->get();
            }else{
                $data = DB::table(DB::raw('erp_kalibrasi.peminjaman p'))
                        ->select(
                            'u.nama', 'p.ket_tambahan', 'p.kondisi_awal',
                            'p.kondisi_akhir', 'p.created_at', 'p.tgl_pinjam',
                            'p.tgl_kembali', 'p.tgl_batas', 'p.status_id', 'p.id_peminjaman'
                        )
                        ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', '=', 'p.peminjam_id')
                        ->where('p.serial_number_id', '=', $id)
                        ->where('u.id', '=', $uid)
                        ->get();
            }

            return DataTables::of($data)
                    ->addIndexColumn()
                    ->editColumn('kondisi_awal', function($d){
                        return $d->kondisi_awal != null ? (
                            $d->kondisi_awal == 9 ?
                            '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                            <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                            </div>'
                            :
                            '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                            <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                            </div>'
                        ):'-';
                    })
                    ->editColumn('kondisi_akhir', function($d){
                        return $d->kondisi_akhir != null ? (
                            $d->kondisi_akhir == 9 ?
                            '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                            <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                            </div>'
                            :
                            '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                            <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                            </div>'
                        ):'-';
                    })
                    ->editColumn('created_at', function($d){
                        return $d->created_at != null ?
                        Carbon::parse($d->created_at)->format('d-m-Y')
                        :null;
                    })
                    ->editColumn('tgl_pinjam', function($d){
                        return $d->tgl_pinjam != null ?
                        Carbon::parse($d->tgl_pinjam)->format('d-m-Y')
                        :'-';
                    })
                    ->editColumn('tgl_kembali', function($d){
                        return $d->tgl_kembali != null ?
                        Carbon::parse($d->tgl_kembali)->format('d-m-Y')
                        :'-';
                    })
                    ->editColumn('tgl_batas', function($d){
                        return $d->tgl_batas != null ?
                        Carbon::parse($d->tgl_batas)->format('d-m-Y')
                        :'-';
                    })
                    ->editColumn('nama', function($d){
                        return $d->penanggung_jawab == null ? $d->nama : $d->penanggung_jawab;
                    })
                    ->editColumn('status_id', function($d) use($role){
                        if($d->status_id == 17){
                            if($role == 1){
                                return
                                '<button onclick="pinjamData('.$d->id_peminjaman.')" class="btn badge w-100 bg-warning">
                                <span class="text-dark">Konfirmasi</span>
                                </button>';
                            }else{
                                return
                                '<span class="badge w-100 bc-warning">
                                <span class="text-warning">Permintaan Pinjam</span>
                                </span>';
                            }
                        }elseif($d->status_id == 15){
                            if($role == 1){
                                return
                                '<button onclick="dikembalikanData('.$d->id_peminjaman.')" class="badge w-100 bg-success">
                                <span class="text-white">Sedang Dipinjam</span>
                                </button>';
                            }else{
                                return
                                '<span class="badge w-100 bc-success">
                                <span class="text-success">Sedang Dipinjam</span>
                                </span>';
                            }
                        }elseif($d->status_id == 18){
                            return
                            '<span class="badge w-100 bc-danger">
                            <span class="text-danger">Ditolak</span>
                            </span>';
                        }else{
                            return
                            '<span class="badge w-100 bc-success">
                            <span class="text-success">Selesai</span>
                            </span>';
                        }
                    })
                    ->rawColumns(['kondisi_awal', 'kondisi_akhir', 'status_id'])
                    ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'status' => 'gagal',
                'msg' => $e->getMessage()
            ]);
        }
    }

    function perawatan_hist($id)
    {
        try {
            $data = DB::table(DB::raw('erp_kalibrasi.perawatan p'))
            ->select(
                'p.tgl_perawatan', 'p.kelengkapan', 'p.fungsi',
                'p.hasil_fisik', 'p.hasil_fungsi', 'p.tindak_lanjut',
                'p.keterangan', 'u.nama'
            )
            ->leftjoin(DB::raw('erp_spa.users u'), 'u.id', '=', 'p.created_by')
            ->where('p.serial_number_id', '=', $id)
            ->get();

            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('hasil_fisik', function($d){
                return $d->hasil_fisik == 9 ?
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                </div>'
                :
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                </div>';
            })
            ->editColumn('hasil_fungsi', function($d){
                return $d->hasil_fungsi == 9 ?
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                </div>'
                :
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                </div>';
            })
            ->addColumn('operator', 'nama_operator')
            ->rawColumns(['hasil_fisik', 'hasil_fungsi'])
            ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'status' => 'gagal',
                'msg' => $e->getMessage()
            ]);
        }
    }

    function detail($id, $x = null)
    {
        try{
            $data =
            DB::table(DB::raw('erp_kalibrasi.alatuji_sn as2'))
            ->select(
                DB::raw('concat(a.kd_alatuji,"-",as2.no_urut) as kode_alat'),
                DB::raw('concat(ml.ruang,"/",ml.rak) as lokasi'),
                'a.nm_alatuji', 'a.desk_alatuji', 'a.kd_alatuji', 'as2.total_waktu',
                'kls.nama_klasifikasi', 'supl.nama_merk', 'as2.kondisi_id',
                'as2.serial_number', 'as2.tgl_masuk', 'ms.nama as kondisi', 'as2.tipe',
                'as2.total_penggunaan', 'a.sop_alatuji', 'a.manual_alatuji', 'u.nama',
                'as2.sert_kalibrasi', 'a.gbr_alatuji', 'as2.status_pinjam_id', 'as2.barcode'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'),'a.id_alatuji', '=', 'as2.alatuji_id')
            ->leftJoin(DB::raw('erp_kalibrasi.klasifikasi kls'), 'kls.id_klasifikasi', '=', 'a.klasifikasi_id')
            ->leftJoin(DB::raw('erp_kalibrasi.merk supl'), 'supl.id_merk', '=', 'as2.merk_id')
            ->leftJoin(DB::raw('erp_spa.m_layout ml'),'ml.id','=','as2.layout_id')
            ->leftJoin(DB::raw('erp_spa.m_status ms'),'ms.id','=','as2.kondisi_id')
            ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', '=', 'as2.dipinjam_oleh')
            ->where('as2.id_serial_number', '=', $id)
            ->first();

            $data->kondisi_id == 9 ?
            $data->kondisi = '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                            <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                            </div>'
            :
            $data->kondisi = '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                                <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                                </div>';

            function cekDokum($d, $nm, $path)
            {
                if($d->$nm == null)
                {
                    return 'Dokumen Belum Dicantumkan';
                }else{
                    return '<a href="'.asset('storage/'.$path.'/'.$d->$nm).'" target="_blank" rel="noopener noreferrer">'.$path.'</a>';
                }
            }
            $data->sop_alatuji = cekDokum($data, 'sop_alatuji', 'sop');
            $data->manual_alatuji = cekDokum($data, 'manual_alatuji', 'manual');
            $data->sert_kalibrasi = cekDokum($data, 'sert_kalibrasi', 'sert_kalibrasi');

            $pengguna_terakhir =
            DB::table(DB::raw('erp_kalibrasi.peminjaman p'))
            ->select('u.nama')
            ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', 'p.peminjam_id')
            ->where('p.serial_number_id', $id)
            ->latest('p.updated_at')
            ->first();

            if($pengguna_terakhir == null){
                $pengguna_terakhir = 'Belum pernah di pinjam';
            }else{
                $pengguna_terakhir = $pengguna_terakhir->nama;
            }

            if($data->total_waktu == null){
                $data->total_waktu = 'Belum Pernah Di Pinjam';
            }else{
                $data->total_waktu = $data->total_waktu.' Hari';
            }

            if($data->total_penggunaan == null){
                $data->total_penggunaan = 'Belum Pernah Di Pinjam';
            }else{
                $data->total_penggunaan = $data->total_penggunaan.' Kali';
            }

            return view('page.lab.detail', [
                'data' => $data,
                'id' => $id,
                'x' => $x,
                'last_user' => $pengguna_terakhir
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'status' => 'gagal',
                'msg' => $e->getMessage()
            ]);
        }
    }

    function peminjaman_terima_data($id)
    {
        try {

            $data =
            DB::table(DB::raw('erp_kalibrasi.peminjaman p'))
            ->select(
                'p.serial_number_id', 'p.id_peminjaman',
                'p.tgl_pinjam', 'p.tgl_batas',
                'p.created_at', 'u.nama',
                'p.penanggung_jawab',
            )
            ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', 'p.peminjam_id')
            ->where('p.id_peminjaman', $id)
            ->first();

            $data->tgl_pinjam = Carbon::parse($data->tgl_pinjam)->format('d M Y');
            $data->tgl_batas = Carbon::parse($data->tgl_batas)->format('d M Y');
            $data->created_at = Carbon::parse($data->created_at)->format('d M Y');
            $data->nama = $data->penanggung_jawab == null ? $data->nama : $data->penanggung_jawab;

            return Response::json($data);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'status' => 'gagal',
                'msg' => $e->getMessage()
            ]);
        }
    }

    function store_konfirmasi(Request $request)
    {
        $request->validate([
            'peminjaman_konfirm_id' => 'required',
            'status_peminjaman' => 'required',
            'kondisi_peminjaman' => 'required',
        ],[
            'required' => 'kolom :attribute harus di isi'
        ]);

        $data = DB::table(DB::raw('erp_kalibrasi.peminjaman p'))->select(
            'as2.serial_number', 'a.nm_alatuji', 'u.nama',
        )
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn as2'), 'as2.id_serial_number', '=', 'p.serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'as2.alatuji_id')
        ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', '=', 'p.peminjam_id')
        ->where('p.id_peminjaman', $request->peminjaman_konfirm_id)
        ->first();

        $x = 15;
        if($request->kondisi_peminjaman == 10 or $request->status_peminjaman == 18)
        {
            $request->validate([
                'keterangan_konfirmasi' => 'required',
            ],[
                'required' => 'kolom :attribute harus di isi jika ditolak / not ok'
            ]);

            // jika di tolak alat OK maka status alat akan menjadi tidak di pinjam
            // jika di tolak alat NOT OK maka status alat akan menjadi NOT OK
            if($request->status_peminjaman == 18){
                $x = 16;
            }
            if($request->kondisi_peminjaman == 10){
                $x = 10;
            }
        }
        AlatSN::find($request->alatuji_konfirm_id)
        ->update([
            'status_pinjam_id' => $x,
            'kondisi_id' => $x
        ]);

        Peminjaman::where('id_peminjaman', $request->peminjaman_konfirm_id)
        ->update([
            'status_id' => $request->status_peminjaman,
            'kondisi_awal' => $request->kondisi_peminjaman,
            'ket_tambahan' => $request->keterangan_konfirmasi,
            'diberikan_oleh' => auth()->user()->id,
        ]);

        // user log
        $status = $request->status_peminjaman == 18 ? 'Ditolak' : 'Dipinjam';
        $obj = [
            'alat_uji' => $data->nm_alatuji,
            'serial_num' => $data->serial_number,
            'tgl_terima' => Carbon::now()->format('Y-m-d', 'Asia/Jakarta'),
            'dipinjam_oleh' => $data->nama,
            'di_acc_oleh' => auth()->user()->nama,
            'status' => $status,
        ];

        DB::table('erp_spa.tbl_log')->insert([
            'tipe' => 'QC',
            'subjek' => 'Konfirmasi peminjaman alat uji - '.$data->nm_alatuji,
            'response' => json_encode($obj),
            'user_id' => auth()->user()->id,
        ]);

        return back()->with(['pinjamSuccess' => '1', 'success' => '1']);
    }

    function store_kembali(Request $request)
    {

        $request->validate([
            'peminjaman_kembali_id' => 'required',
            'kondisi_kembali' => 'required',
            'tgl_kembali' => 'required',
            'jumlah_penggunaan' => 'required',
            'waktu_penggunaan' => 'required',
        ],[
            'required' => 'kolom :attribute harus di isi'
        ]);

        $data = DB::table(DB::raw('erp_kalibrasi.peminjaman p'))->select(
            'as2.serial_number', 'a.nm_alatuji', 'u.nama',
        )
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn as2'), 'as2.id_serial_number', '=', 'p.serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'as2.alatuji_id')
        ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', '=', 'p.peminjam_id')
        ->where('p.id_peminjaman', $request->peminjaman_kembali_id)
        ->first();

        $x = 16;
        if($request->kondisi_kembali == 10)
        {
            $request->validate([
                'keterangan_kembali' => 'required',
            ],[
                'required' => 'kolom :attribute harus di isi jika not ok'
            ]);
            $x = 10;
        }

        //update peminjaman
        Peminjaman::where('id_peminjaman', $request->peminjaman_kembali_id)
        ->update([
            'status_id' => '8',
            'kondisi_akhir' => $request->kondisi_kembali,
            'tgl_kembali' => $request->tgl_kembali,
            'ket_tambahan' => $request->keterangan_kembali,
            'jumlah_penggunaan' => $request->jumlah_penggunaan,
            'waktu_penggunaan' => $request->waktu_penggunaan,
            'diberikan_oleh' => auth()->user()->id,
        ]);

        $data_penggunaan =
        AlatSN::select('total_penggunaan', 'total_waktu')
        ->find($request->id_alat_uji);
        $penggunaan_old = (int)$data_penggunaan->total_penggunaan;
        $waktu_old = (int)$data_penggunaan->total_waktu;

        $total_penggunaan = $penggunaan_old + (int)$request->jumlah_penggunaan;
        $total_waktu = $waktu_old + (int)$request->waktu_penggunaan;

        //update data talat uji sn
        AlatSN::find($request->id_alat_uji)
        ->update([
            'total_penggunaan' => $total_penggunaan,
            'total_waktu' => $total_waktu,
            'status_pinjam_id' => $x,
            'kondisi_id' => $x
        ]);

        // user log
        $obj = [
            'alat_uji' => $data->nm_alatuji,
            'serial_num' => $data->serial_number,
            'tgl_kembali' => $request->tgl_kembali,
            'dipinjam_oleh' => $data->nama,
            'diterima_oleh' => auth()->user()->nama,
        ];

        DB::table('erp_spa.tbl_log')->insert([
            'tipe' => 'QC',
            'subjek' => 'Pengembalian alat uji - '.$data->nm_alatuji,
            'response' => json_encode($obj),
            'user_id' => auth()->user()->id,
        ]);

        return back()->with(['pinjamSuccess' => '1', 'success' => '1']);
    }

    function store_jenisalat(Request $request)
    {

        $request->validate([
            'klasifikasi' => 'required',
            'satuan' => 'required',
            'nama_alat' => 'required|unique:erp_kalibrasi.alatuji,nm_alatuji',
            'fungsi_alat' => 'required',
            'kode_alat' => 'required|unique:erp_kalibrasi.alatuji,kd_alatuji',
        ],[
            'required' => 'kolom :attribute harus di isi',
            'unique' => ':attribute telah terdaftar'
        ]);

        // cek jika data dari nama alat uji sudah ada pada database


        $manual = null;
        $sop = null;
        $gambar = null;
        $date = Carbon::now()->format('Y-m-d', 'Asia/Jakarta');
        if($request->has('manual_book'))
        {
            $request->validate([
                'manual_book' => 'mimes:doc,pdf,docx,zip|max:10000',
            ]);
            $manual = $this->gantiNama($date, $request,'manual_book');
        }
        if($request->has('sop'))
        {
            $request->validate([
                'sop' => 'mimes:doc,pdf,docx,zip|max:10000',
            ]);
            $sop = $this->gantiNama($date, $request,'sop');
        }
        if($request->has('gambar'))
        {
            $request->validate([
                'gambar' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);
            $gambar = $this->gantiNama($date, $request,'gambar');
        }

        // insert query
        Alatuji::create([
            'kd_alatuji' => $request->kode_alat,
            'klasifikasi_id' => $request->klasifikasi,
            'nm_alatuji' => $request->nama_alat,
            'desk_alatuji' => $request->fungsi_alat,
            'sop_alatuji' => $sop,
            'manual_alatuji' => $manual,
            'gbr_alatuji' => $gambar,
            'satuan_alatuji' => $request->satuan,
            'created_by' => auth()->user()->id,
            'stok_alatuji' => '0', // untuk pengembangan kedepannya
        ]);

        if($request->has('manual_book'))
        {
            $request->file('manual_book')->storeAs('public/manual/', $manual);
        }
        if($request->has('sop'))
        {
            $request->file('sop')->storeAs('public/sop/', $sop);
        }
        if($request->has('gambar'))
        {
            $request->file('gambar')->storeAs('public/gambar/', $gambar);
        }

        $k = DB::table(DB::raw('erp_kalibrasi.klasifikasi'))->where('id_klasifikasi', $request->klasifikasi)->first();
        // user log
        $obj = [
            'alat_uji' => $request->nama_alat,
            'kode_alat' => $request->kode_alat,
            'jenis_alat' => $k->nama_klasifikasi,
        ];

        DB::table('erp_spa.tbl_log')->insert([
            'tipe' => 'QC',
            'subjek' => 'Penambahan jenis alat uji - '.$request->nama_alat,
            'response' => json_encode($obj),
            'user_id' => auth()->user()->id,
        ]);

        return back()->with(['success' => '1']);
    }

    function store_tambahbarang(Request $request)
    {
        $request->merk == 0 ? $request->merk = null : 'nothing';
        $request->validate([
            'klasifikasi' => 'required',
            'nama' => 'required',
            'serial_number' => 'required',
            'tipe' => 'required',
            'tanggal_masuk' => 'required',
            'kondisi' => 'required',
            'lokasi' => 'required',
        ],[
            'required' => 'Kolom :attribute harus di isi'
        ]);

        if($request->checkmerk == 'ada'){
            $request->validate([
                'merk' => 'required'
            ],[
                'required' => 'Kolom :attribute harus di isi'
            ]);
        }
        if($request->checkmerk == 'tidak'){
            $request->validate([
                'merkbaru' => 'required|unique:erp_kalibrasi.merk,nama_merk',
            ],[
                'required' => 'Kolom Merk Baru harus di isi',
                'unique' => 'Merek '.$request->merkbaru.' telah terdaftar'
            ]);

            // daftarkan merk
            DB::table('erp_kalibrasi.merk')->insert([
                'nama_merk' => $request->merkbaru,
                'created_by' => auth()->user()->id,
            ]);
            $request->merk = DB::table('erp_kalibrasi.merk')->select('id_merk')->latest('created_at')->first()->id_merk;
        }

        // cek apakah nmor seri telah terdaftar
        $cek_sn = AlatSN::
        where('alatuji_id', $request->nama)
        ->where('merk_id', $request->merk)
        ->where('serial_number', $request->serial_number)
        ->count();
        if($cek_sn >= 1){
            throw ValidationException::withMessages(['serial_number' => 'Serial Number telah Terdaftar']);
        }

        $sertif = null;
        if($request->has('sert_kalibrasi'))
        {
            $date = Carbon::now()->format('Y-m-d', 'Asia/Jakarta');
            $request->validate([
                'sert_kalibrasi' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);
            $sertif = $this->gantiNama($date, $request,'sert_kalibrasi');
        }

        // cek nomor urut serial number alat uji
        $nourut = AlatSN::where('alatuji_id', $request->nama)->get()->count();//-->update untuk ambil nilai yang paling besar
        $nourut++;
        if($nourut < 10){$nourut = '0'.$nourut;}

        AlatSN::create([
            'alatuji_id' => $request->nama,
            'no_urut' => $nourut,
            'tgl_masuk' => $request->tanggal_masuk,
            'serial_number' => $request->serial_number,
            'tipe' => $request->tipe,
            'layout_id' => $request->lokasi,
            'merk_id' => $request->merk,
            'kondisi_id' => $request->kondisi,
            'sert_kalibrasi' => $sertif,
            'status_pinjam_id' => '16',
            'created_by' => auth()->user()->id,
        ]);

        if($request->has('sert_kalibrasi'))
        {
            $request->file('sert_kalibrasi')->storeAs('public/sert_kalibrasi/', $sertif);
        }

        // buat barcode
        $alat_new = AlatSN::latest('created_at')->first();

        // klasifikasi+no urut+tahun masuk+kode
        // BC00119TOCO01 -> BC 001 2019 TOCO-01
        $K = Klasifikasi::select('kd_klasifikasi')->where('id_klasifikasi', $request->klasifikasi)->first()->kd_klasifikasi;
        $N = $alat_new->id_serial_number;
        if((int)$N<10){
            $N = '00'.(int)$N;
        }
        if((int)$N>=10 && (int)$N<100){
            $N = '0'.(int)$N;
        }
        $T = Carbon::parse($request->tanggal_masuk)->format('y');
        $KD = Alatuji::where('id_alatuji', $request->nama)->select('kd_alatuji')->first()->kd_alatuji;
        $barcode = $K.$N.$T.$KD.$nourut;

        AlatSN::where('id_serial_number', $alat_new->id_serial_number)->update([
            'barcode' => $barcode
        ]);

        if($request->has('sert_kalibrasi'))
        {
            $request->file('sert_kalibrasi')->storeAs('public/sert_kalibrasi/', $sertif);
        }

        // user log
        $merek = DB::table(DB::raw('erp_kalibrasi.merk'))->where('id_merk', $request->merk)->first();
        $obj = [
            'alat_uji' => $request->nama_alat,
            'serial_num' => $request->serial_number,
            'merek_alat' => $merek->nama_merk,
            'tanggal_masuk' => $request->tanggal_masuk
        ];

        DB::table('erp_spa.tbl_log')->insert([
            'tipe' => 'QC',
            'subjek' => 'Penambahan Serial Number alat uji - '.$request->nama_alat,
            'response' => json_encode($obj),
            'user_id' => auth()->user()->id,
        ]);

        return back()->with(['success' => '1']);
    }

    function get_data_dashboard_permintaan(){
        $data = DB::table(DB::raw('erp_kalibrasi.peminjaman p'))
        ->select(
            'u.nama', 'a.nm_alatuji', 'as2.serial_number', 'as2.kondisi_id', 'as2.id_serial_number', 'p.kondisi_awal', 'p.tgl_pinjam',
            'p.tgl_batas', 'p.status_id', 'p.id_peminjaman'
        )
        ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', '=', 'p.peminjam_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn as2'), 'as2.id_serial_number', '=', 'p.serial_number_id')
        ->leftjoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', 'as2.alatuji_id')
        ->where('p.status_id', 17)
        ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('kondisi_id', function($d){
                return $d->kondisi_id == 9 ?
                '<i class="fa-solid fa-circle-check text-success"></i>'
                :
                '<i class="fa-solid fa-circle-xmark text-danger"></i>'
                ;
            })
            ->addColumn('aksi', function($d){
                return
                '<button onclick="pinjamData('.$d->id_peminjaman.')" class="btn badge w-100 bg-warning">
                <span class="text-dark">Konfirmasi</span>
                </button>';
            })
            ->rawColumns(['kondisi_id', 'aksi'])
            ->make(true);
    }

    function get_data_dashboard_pengembailan(){
        $data = DB::table(DB::raw('erp_kalibrasi.peminjaman p'))
        ->select(
            'u.nama', 'a.nm_alatuji', 'as2.serial_number', 'p.kondisi_awal', 'p.tgl_pinjam',
            'p.tgl_batas', 'p.status_id', 'p.id_peminjaman', 'as2.id_serial_number'
        )
        ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', '=', 'p.peminjam_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn as2'), 'as2.id_serial_number', '=', 'p.serial_number_id')
        ->leftjoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', 'as2.alatuji_id')
        ->where('p.status_id', 15)
        ->get();

        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('kondisi_awal', function($d){
                return $d->kondisi_awal == 9 ?
                '<i class="fa-solid fa-circle-check text-success"></i>'
                :
                '<i class="fa-solid fa-circle-xmark text-danger"></i>'
                ;
            })
            ->addColumn('aksi', function($d){
                return
                '<button onclick="dikembalikanData('.$d->id_peminjaman.')" class="badge w-100 bg-success">
                <span class="text-white">Sedang Dipinjam</span>
                </button>';
            })
            ->rawColumns(['kondisi_awal', 'aksi'])
            ->make(true);
    }

    function get_data_dashboard_mt_sekarang($x){
        $a = 2;
        $x == 'p' ? $a = 2 : $a = 3;
        $x == 'p' ? $x = 'perawatan' : $x = 'verifikasi';
        $data = DB::table(DB::raw('erp_kalibrasi.'.$x.' p'))
        ->select(
            DB::raw('MAX(p.id_'.$x.') as id_perawatan'),
            DB::raw('MAX(p.jadwal_perawatan) as jadwal_perawatan'),
            'a.nm_alatuji', 'as2.serial_number', 'p.tgl_perawatan', 'as2.id_serial_number')
        ->groupBy('serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn as2'), 'as2.id_serial_number', '=', 'p.serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'as2.alatuji_id')
        ->whereMonth('p.jadwal_perawatan', Carbon::now()->month)
        ->whereYear('p.jadwal_perawatan', Carbon::now()->year)->get();

        return
        DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('detail', function($d) use($a){
                return
                '<a class="btn btn-sm btn-outline-primary py-0" href="'.route("alatuji.detail", ["id"=>$d->id_serial_number, "x"=>$a]).'">
                Detail
                </a>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    function get_data_dashboard_mt_terlewati($x){
        $a = 2;
        $x == 'p' ? $a = 2 : $a = 3;
        $x == 'p' ? $x='perawatan' : $x='verifikasi';
        $data = DB::table(DB::raw('erp_kalibrasi.'.$x.' p'))
        ->select(
            DB::raw('MAX(id_'.$x.') as id_perawatan'),
            DB::raw('MAX(jadwal_perawatan) as jadwal_perawatan'),
            'a.nm_alatuji', 'as2.serial_number', 'p.tgl_perawatan', 'p.jadwal_perawatan', 'as2.id_serial_number')
        ->groupBy('serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn as2'), 'as2.id_serial_number', '=', 'p.serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'as2.alatuji_id')
        ->where('p.jadwal_perawatan', '<', Carbon::now()->format('Y-m'))
        ->get();
        return
        DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('detail', function($d) use($a){
                return
                '<a class="btn btn-sm btn-outline-primary py-0" href="'.route("alatuji.detail", ["id"=>$d->id_serial_number, "x"=>$a]).'">
                Detail
                </a>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }

    function get_data_dashboard_mt_reminder($x){
        $a = 2;
        $x == 'p' ? $a = 2 : $a = 3;
        $x == 'p' ? $x='perawatan' : $x='verifikasi';

        $d1 =
        DB::table('erp_kalibrasi.verifikasi')
        ->select(DB::raw('count(DISTINCT serial_number_id) as total'))
        ->where('jadwal_perawatan', '>=', Carbon::now()->addMonth(1)->format('Y-m'))
        ->where('jadwal_perawatan', '<=', Carbon::now()->addMonth(2)->format('Y-m'))
        ->get();

        $get_data = DB::table(DB::raw('erp_kalibrasi.'.$x.' p'))
        ->select(
            DB::raw('MAX(p.id_'.$x.') as id_perawatan'),
            DB::raw('MAX(p.jadwal_perawatan) as jadwal_perawatan'),
            'p.id_'.$x, 'a.nm_alatuji', 'as2.serial_number', 'p.tgl_perawatan', 'as2.id_serial_number')
        ->groupBy('serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn as2'), 'as2.id_serial_number', '=', 'p.serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'as2.alatuji_id')
        ->where('p.jadwal_perawatan', '>=', Carbon::now()->addMonth(1)->format('Y-m'))
        ->where('p.jadwal_perawatan', '<=', Carbon::now()->addMonth(2)->format('Y-m'))
        ->get();
        $data = collect([]);
        foreach($get_data as $y){
            $data->push([
                'id_perawatan' => $y->id_perawatan,
                'jadwal_perawatan' => $y->jadwal_perawatan,
                'nm_alatuji' => $y->nm_alatuji,
                'serial_number' => $y->serial_number,
                'tgl_perawatan' => $y->tgl_perawatan,
                'id_serial_number' => $y->id_serial_number
            ]);
            if($x == 'perawatan'){
                $data->last() + array('id_perawatan' => $y->id_perawatan);
            }else{
                $data->last() + array('id_verifikasi' => $y->id_verifikasi);
            }
        }

        return
        DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('detail', function($d) use($a){
                return
                '<a class="btn btn-sm btn-outline-primary py-0" href="'.route("alatuji.detail", ["id"=>$d['id_serial_number'], "x"=>$a]).'">
                Detail
                </a>';
            })
            ->rawColumns(['detail'])
            ->make(true);
    }


    function get_data_not_ok(){
        $a =
        DB::table(DB::raw('erp_kalibrasi.alatuji_sn a2'))
        ->select(
            'a2.serial_number', 'a.nm_alatuji', 'a2.kondisi_id', 'a2.id_serial_number'
        )
        ->where('a2.kondisi_id', 10)
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', 'a2.alatuji_id')
        ->get();

        return
        DataTables::of($a)
        ->addIndexColumn()
        ->editColumn('kondisi_id', function($d){
            return $d->kondisi_id == 9 ?
            '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
            <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
            </div>'
            :
            '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
            <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
            </div>';
        })
        ->addColumn('detail', function($d){
            return
            '<a class="btn btn-sm btn-outline-primary py-0" href="'.route("alatuji.detail", ["id"=>$d->id_serial_number]).'">
            Detail
            </a>';
        })
        ->rawColumns(['detail', 'kondisi_id'])
        ->make(true);
    }

    function get_data_pj(){
        $data =
        DB::table(DB::raw('erp_kalibrasi.peminjaman p'))
        ->select('p.penanggung_jawab')
        ->groupBy('p.penanggung_jawab')
        ->get();

        $d = $data->map(function($item, $key){
            return $item->penanggung_jawab;
        });

        return $d;
    }

    function get_data_tipe(){
        $data =
        DB::table(DB::raw('erp_kalibrasi.alatuji_sn p'))
        ->select('p.tipe')
        ->groupBy('p.tipe')
        ->get();

        $d = $data->map(function($item, $key){
            return $item->tipe;
        });

        return $d;
    }

}

