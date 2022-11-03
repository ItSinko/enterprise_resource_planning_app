<?php

namespace App\Http\Controllers\inventory;

use App\Http\Controllers\Controller;

use App\Models\inventory\Kalibrasi;
use App\Models\inventory\AlatSN;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class KalibrasiPerbaikanController extends Controller
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

    function index($jenis, $id)
    {
        try
        {
            $data =
            DB::table(DB::raw('erp_kalibrasi.alatuji_sn al'))
            ->select(
                DB::raw('concat(a.kd_alatuji,"-",al.no_urut) as kode_alat'),
                'al.serial_number', 'al.status_pinjam_id', 'a.desk_alatuji', 'k.tgl_kirim', 'a.gbr_alatuji'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'),'a.id_alatuji', '=', 'al.alatuji_id')
            ->leftJoin(DB::raw('erp_kalibrasi.'.$jenis.' k'), 'k.serial_number_id', '=', 'al.alatuji_id')
            ->where('al.id_serial_number', '=', $id)
            ->first();

            if($data->status_pinjam_id == 16){
                $data->status_pinjam_id =
                '<span class="badge bc-success">
                    <span class="text-success">Tersedia</span>
                </span>';
            }elseif($data->status_pinjam_id == 15){
                $data->status_pinjam_id =
                '<span class="badge bc-primary">
                <span class="text-primary">Sedang Di Pinjam</span>
                </span>';
            }elseif($data->status_pinjam_id == 17){
                $data->status_pinjam_id =
                '<span class="badge bc-warning">
                <span class="text-warning">Pengajuan</span>
                </span>';
            }elseif($data->status_pinjam_id == 14){
                $data->status_pinjam_id =
                '<span class="badge bc-warning">
                <span class="text-warning">Eksternal</span>
                </span>';
            }else{
                $data->status_pinjam_id =
                '<span class="badge bc-danger">
                <span class="text-danger">Not Ok</span>
                </span>';
            }

            $tgl = DB::table(DB::raw('erp_kalibrasi.'.$jenis.' k'))
            ->select('tgl_kirim')
            ->where('k.serial_number_id', $id)
            ->orderByDesc('k.tgl_kirim')
            ->first();
            if($tgl == null){
                $tgl = 'Alat belum di lakukan '.$jenis;
                $data->tgl_kirim = $tgl;
            }else{
                $data->tgl_kirim = $tgl->tgl_kirim;
            }

            $user = DB::table(DB::raw('erp_spa.users'))->select('*')->get();

            return view('page.lab.kalibrasi_perbaikan', [
                'data' => $data,
                'id' => $id,
                'jenis' => $jenis,
                'user' => $user,
            ]);

        } catch (\Illuminate\Database\QueryException $e) {
            return abort(404);
        }
    }

    function store_mt(Request $request)
    {
        // try {

            // external
            if($request->dilakukan == 'External')
            {
                $request->validate([
                    'tanggal_pengajuan' => 'required',
                    'masalah' => 'required',
                    'surat_jalan' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                    'memo' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                ],[
                    'required' => 'kolom :attribute harus di isi'
                ]);

                $data = DB::table(DB::raw('erp_kalibrasi.alatuji_sn al'))->select(
                    'al.serial_number', 'a.nm_alatuji'
                )
                ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'al.alatuji_id')
                ->where('al.id_serial_number', $request->serial_number_id)
                ->first();

                //ganti nama gambar
                $memo = null;
                $surat_jalan = null;
                if($request->has('surat_jalan') or $request->has('memo'))
                {
                    $date = Carbon::now()->format('Y-m-d', 'Asia/Jakarta');
                    $memo = $this->gantiNama($date, $request,'memo');
                    $surat_jalan = $this->gantiNama($date, $request,'surat_jalan');
                }
                //ganti nama gambar end

                DB::table('erp_kalibrasi.'.strtolower($request->jenis_mt))->insert([
                //Kalibrasi::create([
                    'serial_number_id' => $request->serial_number_id,
                    'masalah' => $request->masalah,
                    'tgl_kirim' => $request->tanggal_pengajuan,
                    'pengirim_id' => auth()->user()->id,
                    'surat_jalan' => $surat_jalan,
                    'memo' => $memo,
                    'jenis_id' => '14',//external
                    'status_id' => '11',//proses
                    'created_by' => auth()->user()->id,
                ]);

                AlatSN::find($request->serial_number_id)
                ->update(['status_pinjam_id' => '14']);

                if($request->has('surat_jalan') or $request->has('memo'))
                {
                    $request->file('surat_jalan')->storeAs('public/kalibrasiperbaikan', $surat_jalan);
                    $request->file('memo')->storeAs('public/kalibrasiperbaikan', $memo);
                }

                // user log
                $obj = [
                    'alat_uji' => $data->nm_alatuji,
                    'serial_num' => $data->serial_number,
                    'tgl_'.$request->jenis_mt => $request->tanggal_pengajuan,
                    'pj_dilakukan_oleh' => auth()->user()->nama,
                ];

                DB::table('erp_spa.tbl_log')->insert([
                    'tipe' => 'QC',
                    'subjek' => 'Pengiriman '.$request->jenis_mt.' alat uji External - '.$data->nm_alatuji,
                    'response' => json_encode($obj),
                    'user_id' => auth()->user()->id,
                ]);
            }

            // internal
            if($request->dilakukan == 'Internal')
            {
                $request->validate([
                    'tanggal_pengajuan' => 'required',
                    'masalah' => 'required',
                    'operator' => 'required',
                    'cekFungsi' => 'required',
                    'cekFisik' => 'required',
                    'tindak_lanjut' => 'required',
                ],[
                    'required' => 'kolom :attribute harus di isi'
                ]);

                $data = DB::table(DB::raw('erp_kalibrasi.alatuji_sn al'))->select(
                    'al.serial_number', 'a.nm_alatuji'
                )
                ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=', 'al.alatuji_id')
                ->where('al.id_serial_number', $request->serial_number_id)
                ->first();

                $pj = DB::table('erp_spa.users')->where('id', $request->operator)->first();

                // input data
                DB::table('erp_kalibrasi.'.strtolower($request->jenis_mt))->insert([
                    'serial_number_id' => $request->serial_number_id,
                    'masalah' => $request->masalah,
                    'tgl_kirim' => $request->tanggal_pengajuan,
                    'pengirim_id' => auth()->user()->id,
                    'pelaksana_id' => $request->operator,
                    'tindak_lanjut' => $request->tindak_lanjut,
                    'hasil_fungsi' => $request->cekFungsi,
                    'hasil_fisik' => $request->cekFisik,
                    'status_id' => '12',//selesai
                    'jenis_id' => '13',//internal
                    'created_by' => auth()->user()->id,
                ]);

                // update tabel alat uji
                if($request->cekFungsi == '10' OR $request->cekFisik == '10'){
                    AlatSN::find($request->serial_number_id)
                    ->update([
                        'status_pinjam_id' => '10',
                        'kondisi_id' => '10',
                    ]);
                }
                if($request->cekFungsi == '9' AND $request->cekFisik == '9'){
                    AlatSN::find($request->serial_number_id)
                    ->update([
                        'kondisi_id' => '9',
                        'status_pinjam_id' => '16'
                    ]);
                }

                // user log
                $obj = [
                    'alat_uji' => $data->nm_alatuji,
                    'serial_num' => $data->serial_number,
                    'tgl_'.$request->jenis_mt => $request->tanggal_pengajuan,
                    'pj_dilakukan_oleh' => $pj->nama,
                ];

                DB::table('erp_spa.tbl_log')->insert([
                    'tipe' => 'QC',
                    'subjek' => $request->jenis_mt.' alat uji Internal - '.$data->nm_alatuji,
                    'response' => json_encode($obj),
                    'user_id' => auth()->user()->id,
                ]);
            }

            $request->jenis_mt == 'kalibrasi' ? $alertScc = 'kalibSuccess' : $alertScc = 'perbSuccess';

            return redirect()->route('alatuji.detail', ['id' => $request->serial_number_id])->with(['success' => '1',$alertScc => '3']);

        // } catch (\Exception $e) {
        //     return response()->json([
        //         'error' => true,
        //         'msg' => $e->getMessage()
        //     ]);
        // }
    }

    function maintenance_hist($id, $jenis)
    {
        try {

            $data =
            DB::table(DB::raw('erp_kalibrasi.'.$jenis.' k'))
            ->select(
                'k.serial_number_id', 'k.tgl_kirim', 'k.jenis_id',
                'k.masalah', 'k.hasil_fisik', 'k.hasil_fungsi', 'k.status_id',
                'k.id_'.$jenis,
            )
            ->where('k.serial_number_id', $id)
            ->get();

            $jenis == 'kalibrasi' ? $j = 'id_kalibrasi' : $j = 'id_perbaikan';

            return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('hasil_fisik', function($d){
                return $d->hasil_fisik == 9 ?
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                </div>'
                :
                (
                    $d->hasil_fisik == 10 ?
                    '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                    <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                    </div>'
                    :
                    '-'
                );
            })
            ->editColumn('hasil_fungsi', function($d){
                return $d->hasil_fungsi == 9 ?
                '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Dapat Di Gunakan">
                <i class="fa fa-check-circle text-success fa-lg" aria-hidden="true"></i>
                </div>'
                :
                (
                    $d->hasil_fungsi == 10 ?
                    '<div data-bs-toggle="tooltip" data-bs-placement="top" title="Alat Tidak Dapat Di Gunakan">
                    <i class="fa fa-times-circle text-danger fa-lg" aria-hidden="true"></i>
                    </div>'
                    :
                    '-'
                );
            })
            ->editColumn('status_id', function($d) use($jenis){
                return $d->status_id == 12 ?
                '<span class="badge w-100 bc-success">
                <span class="text-success">Selesai</span>
                </span>'
                :
                '<a href="/lab/alatuji/mt/konfirmasi/'.$jenis.'/'.($jenis == "kalibrasi" ? $d->id_kalibrasi : $d->id_perbaikan).'" class="badge w-100 bg-warning">
                <span class="text-dark">Proses</span>
                </a>';
            })
            ->editColumn('jenis_id', function($d){
                return $d->jenis_id == 13 ?
                '<span class="badge w-100 bc-primary">
                <span class="text-primary">Internal</span>
                </span>'
                :
                '<span class="badge w-100 bc-warning">
                <span class="text-warning">External</span>
                </span>';
            })
            ->addColumn('aksi', function($d) use($j, $jenis){
                return '<button class="badge border border-primary bg-white text-primary getDataMT" data-id="'.$d->$j.'" data-jenis="'.$jenis.'">Detail</button>';
            })
            // ->editColumn('biaya', function($d){
            //     return $d->biaya != null ? "Rp " . number_format($d->biaya,2,',','.') : '-';
            // })
            // ->editColumn('surat_jalan', function($d) use($jenis){
            //     return $d->surat_jalan != null ?
            //     '<span onclick="getData('.($jenis == "kalibrasi" ? $d->id_kalibrasi : $d->id_perbaikan).', '.($jenis == "kalibrasi" ? '0' : '1').', 0)">
            //     <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-earmark-text-fill text-primary btn-hover" viewBox="0 0 16 16">
            //     <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z"/>
            //     </svg></span>'
            //     :'-';
            // })
            // ->editColumn('memo', function($d) use($jenis){
            //     return $d->memo != null ?
            //     '<span onclick="getData('.($jenis == "kalibrasi" ? $d->id_kalibrasi : $d->id_perbaikan).', '.($jenis == "kalibrasi" ? '0' : '1').', 1)">
            //     <svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-file-earmark-text-fill text-primary btn-hover" viewBox="0 0 16 16"><path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0zM9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1zM4.5 9a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1h-7zM4 10.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm.5 2.5a.5.5 0 0 1 0-1h4a.5.5 0 0 1 0 1h-4z"/>
            //     </svg></span>'
            //     :'-';
            // })
            // ->editColumn('supplier_id', function($d){
            //     return $d->supplier_id != null ? $d->nama_supplier : '-';
            // })
            ->rawColumns(['hasil_fisik', 'hasil_fungsi', 'status_id', 'jenis_id', 'aksi'])
            ->make(true);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function confirm($jenis, $id)
    {
        try {
            $data = DB::table(DB::raw('erp_kalibrasi.'.$jenis.' k'))
            ->select(
                DB::raw('concat(a.kd_alatuji,"-",sn.no_urut) as kode_alat'),
                'tgl_kirim', 'a.nm_alatuji', 'sn.serial_number', 'k.id_'.$jenis, 'a.gbr_alatuji'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn sn'), 'sn.id_serial_number', 'k.serial_number_id')
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', 'sn.alatuji_id')
            ->where('k.id_'.$jenis, $id)
            ->first();

            $perusahaan = DB::table('erp_kalibrasi.merk')->get();

            return view('page.lab.kalibrasi_perbaikan_selesai', [
                'data' => $data,
                'id' => $id,
                'jenis' => $jenis,
                'perusahaan' => $perusahaan,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }

    function store_external(Request $request)
    {
        $request->validate([
            'tgl_terima' => 'required',
            'perusahaan' => 'required',
            'biaya' => 'required',
            'cekFungsi' => 'required',
            'cekFisik' => 'required',
            'tindak_lanjut' => 'required',
        ],[
            'required' => 'kolom :attribute harus di isi'
        ]);

        $data = DB::table(DB::raw('erp_kalibrasi.'.$request->jenis.' k'))
        ->select(
            'al.serial_number', 'a.nm_alatuji'
        )
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn al'), 'al.id_serial_number', '=', 'k.serial_number_id')
        ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', '=','al.alatuji_id')
        ->where('k.id_'.$request->jenis, $request->id_mt)
        ->first();

        //$id = kalibrasi::select('serial_number_id')->where('id_'.$request->jenis, $request->id_mt)->first();
        $id = DB::table('erp_kalibrasi.'.$request->jenis)->select('serial_number_id')->where('id_'.$request->jenis, $request->id_mt)->first();

        if($request->perusahaan == 0){
            $request->perusahaan = null;
        }

        $sertif = null;
        if($request->has('sertif_kalibrasi'))
        {
            $date = Carbon::now()->format('Y-m-d', 'Asia/Jakarta');
            $request->validate([
                'sertif_kalibrasi' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ]);
            $sertif = $this->gantiNama($date, $request,'sertif_kalibrasi');
        }

        DB::table('erp_kalibrasi.'.$request->jenis)
        ->where('id_'.$request->jenis, $request->id_mt)
        ->where('status_id', '11')//proses
        ->update([
            'tgl_terima' => $request->tgl_terima,
            'penerima_id' => auth()->user()->id,
            'tindak_lanjut' => $request->tindak_lanjut,
            'hasil_fisik' => $request->cekFisik,
            'hasil_fungsi' => $request->cekFungsi,
            'merk_id' => $request->perusahaan,
            'biaya' => $request->biaya,
            'status_id' => '12',
        ]);

        $alatuji_id =
        DB::table('erp_kalibrasi.'.$request->jenis)
        ->where('id_'.$request->jenis, $request->id_mt)
        ->select('serial_number_id')->first();

        // update tabel alat uji
        if($request->cekFungsi == '10' OR $request->cekFisik == '10'){
            AlatSN::find($alatuji_id->serial_number_id)
            ->update([
                'status_pinjam_id' => '10',
                'kondisi_id' => '10',
            ]);
        }
        if($request->cekFungsi == '9' AND $request->cekFisik == '9'){
            AlatSN::find($alatuji_id->serial_number_id)
            ->update([
                'kondisi_id' => '9',
                'status_pinjam_id' => '16'
            ]);
        }

        if($request->has('sertif_kalibrasi'))
        {
            AlatSN::find($alatuji_id->serial_number_id)
            ->update([
                'sert_kalibrasi' => $sertif,
            ]);
            $request->file('sertif_kalibrasi')->storeAs('public/sert_kalibrasi/', $sertif);
        }

        // user log
        $obj = [
            'alat_uji' => $data->nm_alatuji,
            'serial_num' => $data->serial_number,
            'tgl_'.$request->jenis => Carbon::now()->format('Y-m-d', 'Asia/Jakarta'),
            'pj_diterima_oleh' => auth()->user()->nama,
        ];

        DB::table('erp_spa.tbl_log')->insert([
            'tipe' => 'QC',
            'subjek' => 'Penerimaan '.$request->jenis.' alat uji External - '.$data->nm_alatuji,
            'response' => json_encode($obj),
            'user_id' => auth()->user()->id,
        ]);

        $request->jenis == 'kalibrasi' ? $alertScc = 'kalibSuccess' : $alertScc = 'perbSuccess';

        return redirect()->route('alatuji.detail', ['id' => $id->serial_number_id])->with(['success' => '1' ,$alertScc => '3']);
    }

    function gambar_show($id, $jenis, $tipe)
    {
        function x($data, $x){return $data->$x;}
        $tipe = $tipe == 0 ? 'surat_jalan' : 'memo';
        $jenis = $jenis == 0 ? 'kalibrasi' : 'perbaikan';
        //$data = Kalibrasi::select($jenis)->find($id);
        $data = DB::table('erp_kalibrasi.'.$jenis)->select($tipe)->where('id_'.$jenis, $id)->first();
        return x($data, $tipe);
    }

    function data_show($id, $jenis)
    {
        try {

            $data =
            DB::table(DB::raw('erp_kalibrasi.'.$jenis.' k'))
            ->select(
                'k.serial_number_id', 'k.tgl_kirim', 'k.tgl_terima','k.jenis_id',
                'k.masalah', 'k.hasil_fisik', 'k.hasil_fungsi', 'k.status_id',
                'k.id_'.$jenis, 'a.nm_alatuji', 'sn.serial_number', 'k.memo',
                'k.surat_jalan', 'k.tindak_lanjut', 's.nama_merk', 'k.biaya',
                'u.nama'
            )
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji_sn sn'), 'sn.id_serial_number', 'k.serial_number_id')
            ->leftJoin(DB::raw('erp_kalibrasi.alatuji a'), 'a.id_alatuji', 'sn.alatuji_id')
            ->leftJoin(DB::raw('erp_kalibrasi.merk s'), 's.id_merk', 'k.merk_id')
            ->leftJoin(DB::raw('erp_spa.users u'), 'u.id', 'k.pelaksana_id')
            ->where('k.id_'.$jenis, $id)
            ->first();

            $data->jenis_id == 13 ? $data->jenis_id = 'internal' : $data->jenis_id = 'eksternal';

            // ganti format biaya
            $data->biaya != null ? $data->biaya = "Rp " . number_format($data->biaya,2,',','.') : $data->biaya = '-';

            return json_encode($data);

        }  catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'msg' => $e->getMessage()
            ]);
        }
    }


}
