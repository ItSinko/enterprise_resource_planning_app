<?php

namespace App\Http\Controllers;

use stdClass;
use Carbon\Carbon;
use App\Models\HasilMeeting;
use App\Models\HasilNotulen;
use Illuminate\Http\Request;
use App\Models\JadwalMeeting;
use App\Models\PesertaMeeting;
use App\Models\kesehatan\Karyawan;
use Illuminate\Support\Facades\DB;
use App\Models\RiwayatJadwalMeeting;
use PDF;

class MeetingController extends Controller
{
    //
    public function store_jadwal_meet(Request $request){
        //dd($request->all());
        DB::beginTransaction();
        try {
            //code...
            $max = JadwalMeeting::whereYear('created_at', (Carbon::now()->format('Y')))->max('urutan');
          $jm =   JadwalMeeting::create([
                "urutan"=> $max +1,
                "judul"=> $request->judul,
                "tgl_meeting"=>$request->tanggal,
                "mulai"=>  $request->mulai,
                "selesai"=>$request->selesai,
                "lokasi"=>  $request->lokasi,
                "status"=> 1, //1=belum 2=dilaksanakan
                "notulen"=>  $request->notulen,
                "moderator"=>  $request->moderator,
                "deskripsi"=> $request->deskripsi
            ]);

            if(count($request->peserta) > 0){
                foreach($request->peserta as $p){
                    PesertaMeeting::create([
                        'meeting_id' => $jm->id,
                        'karyawan_id' => $p,
                        'status' => 0, //0=Unknown 1=Tdk Hadir 2=Hadir
                        'ket' => NULL,
                    ]);
                }
            }



            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Tambahkan'.$th->getMessage(),
            ], 500);
        }
    }

    public function store_notulen_meet(Request $request){
        DB::beginTransaction();
        try {
            //code...
            HasilNotulen::create([
                'meeting_id' => $request->meeting_id,
                'pic_id' => $request->karyawan_id,
                'uraian' => $request->uraian
            ]);

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Tambahkan',
            ], 500);
        }
    }
    public function store_hasil_meet(Request $request){
        DB::beginTransaction();
        try {
            //code...
            HasilMeeting::create([
                'meeting_id' => $request->meeting_id,
                'isi' => $request->isi
            ]);

            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Ditambahkan',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Tambahkan',
            ], 500);
        }
    }
    public function update_hasil_meet(Request $request,$id){
        DB::beginTransaction();
        try {
            //code...
            $h = HasilMeeting::find($id);
            $h->isi = $request->isi;
            $h->save();


            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Diubah',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Diubah',
            ], 500);
        }
    }

    public function delete_hasil_meet($id){
        DB::beginTransaction();
        try {
            //code...
            $h = HasilMeeting::find($id)->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Di hapus',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di hapus',
            ], 500);
        }
    }

    public function show_hasil_meet($id){
        try {
            //code...
            //code...
        $data = HasilMeeting::where('meeting_id',$id)->get();
        if($data->isEmpty()){
            $obj = array();
        }else{
            foreach($data as $d){
                $obj[] = (object)[
                    "id"=> $d->id,
                    "isi"=> $d->isi,
                ];
            }
        }
        return response()->json($obj);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 200,
                'message' => 'Gagal',
            ], 500);
        }
    }

    public function edit_hasil_meet($id){
        try {
        $data = HasilMeeting::find($id);
        if(!$data){
            $data = array();
        }
        return response()->json($data);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 200,
                'message' => 'Gagal'. $th->getMessage(),
            ], 500);
        }
    }
    public function update_jadwal_meet(Request $request,$id){
         DB::beginTransaction();
        try {
            $data = JadwalMeeting::find($id);
            if($data){

                $obj = new stdClass();
                $obj->urutan =  $data->urutan;
                $obj->judul = $data->judul;
                $obj->tgl_meeting =  $data->tgl_meeting;
                $obj->mulai =  $data->mulai;
                $obj->selesai=  $data->selesai;
                $obj->lokasi =  $data->lokasi;
                $obj->status =  $data->status;
                $obj->notulen =   $data->notulen;
                $obj->moderator =   $data->moderator;
                $obj->deskripsi =  $data->deskripsi;

             if(count($data->PesertaMeeting) > 0){
                foreach($data->PesertaMeeting as $p){
                    $peserta[] = (object)[
                        'id' => $p->id,
                        'karyawan_id' => $p->id,
                        'nama' => $p->Karyawan->nama,
                        'status' => $p->status,
                        'ket' => $p->ket,
                    ];
                }
                 $obj->peserta = $peserta;
                 PesertaMeeting::where('meeting_id', $id)->delete();
           }else{
            $obj->peserta = array();
           }

           RiwayatJadwalMeeting::create([
            'meeting_id' => $id,
            'isi' => json_encode($obj),
            'user_id' => 2,
            'ket' => $request->alasan
            ]);

           $data->deskripsi =  $request->deskripsi;
           $data->tgl_meeting =  $request->tanggal;
           $data->mulai =  $request->mulai;
           $data->selesai =  $request->selesai;
           $data->lokasi = $request->lokasi;
           $data->notulen = $request->notulen;
           $data->moderator = $request->moderator;
           $data->save();

           if(count($request->peserta) > 0){
            foreach($request->peserta as $p){
                PesertaMeeting::create([
                    'meeting_id' => $id,
                    'karyawan_id' => $p,
                    'status' => 0, //0=Unknown 1=Tdk Hadir 2=Hadir
                    'ket' => NULL,
                ]);
            }
         }
         DB::commit();
           return response()->json([
            'status' => 200,
            'message' => 'Berhasil Diubah',
        ], 200);
            }else{
                DB::rollBack();
                return response()->json([
                    'status' => 200,
                    'message' => 'Jadwal Tidak ditemukan',
                ], 500);
            }

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Ubah'.$th->getMessage(),
            ], 500);
        }
    }

    public function show_jadwal_meet(){

    try {
        //code...
        $data = JadwalMeeting::all();

        if($data->isEmpty()){
            $obj = array();
        }else{
            foreach($data as $d){
                $obj[] = (object)[
                    "id"=> $d->id,
                    "urutan"=> 'Meet-'.$d->urutan,
                    "judul"=> $d->judul,
                    "tanggal"=> $d->tgl_meeting,
                    "mulai"=>  $d->mulai,
                    "selesai"=>$d->selesai,
                    "lokasi"=>  $d->lokasi,
                    "status"=>  $d->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting', //1=belum 2=dilaksanakan
                    "notulen"=>  $d->notulen,
                    "moderator"=>  $d->moderator,
                    "deskripsi"=> $d->deskripsi,
                    "peserta" => PesertaMeeting::select('karyawan_id')->where('meeting_id', $d->id)->get()->map(function($item){
                        return $item->karyawan_id;
                    }),
                ];
            }
        }
        return response()->json($obj);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => 200,
            'message' => 'Gagal',
            'error' => $th->getMessage()
        ], 500);
    }
    }

    public function show_notulen_meet($id){
    try {
        //code...
        $data = DB::table('erp_meetings.hasil_notulen')
        ->select('hasil_notulen.id','pic.nama as pic','verif.nama as verif','hasil_notulen.checked_at','hasil_notulen.hasil','hasil_notulen.uraian','hasil_notulen.ket')
        ->leftjoin('erp_kesehatan.karyawans as pic', 'pic.id', '=', 'hasil_notulen.pic_id')
        ->leftjoin('erp_kesehatan.karyawans as verif', 'verif.id', '=', 'hasil_notulen.verif_id')
        ->where('hasil_notulen.meeting_id', $id)
        ->get();
        if($data->isEmpty()){
            $obj = array();
        }else{
            foreach($data as $d){
                $obj[] = (object)[
                    "id"=> $d->id,
                    "pic"=> $d->pic,
                    "uraian"=> $d->uraian,
                    "dicek_oleh"=> $d->verif,
                    "tgl_cek"=> $d->checked_at,
                    "hasil_notulen"=> $d->hasil,
                    "ket"=> $d->ket,
                ];
            }
        }
        return response()->json($obj);
    } catch (\Throwable $th) {
        return response()->json([
            'status' => 200,
            'message' => 'Gagal',
        ], 500);
    }
    }
    public function detail_jadwal_meet($id){
    try {
        //code...
        $data = JadwalMeeting::find($id);
        if($data){
            $obj = new stdClass();
            $obj->urutan =  'Meet-'.$data->urutan;
            $obj->judul = $data->judul;
            $obj->tgl_meeting =  $data->tgl_meeting;
            $obj->mulai =  $data->mulai;
            $obj->selesai=  $data->selesai;
            $obj->lokasi =  $data->lokasi;
            $obj->status =  $data->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting'; //1=belum 2=dilaksanakan
            $obj->notulen =   Karyawan::find($data->notulen)->nama;
            $obj->moderator =   Karyawan::find($data->moderator)->nama;
            $obj->deskripsi =  $data->deskripsi;

            if(count($data->RiwayatJadwalMeeting) > 0){
                foreach($data->RiwayatJadwalMeeting as $p){
                    $riwayat[] =  json_decode($p->isi);
                    $riwayat[count($riwayat)-1]->alasan_perubahan_meeting = $p->ket;
                    $riwayat[count($riwayat)-1]->urutan = 'Meet-'.$data->urutan;
                    $riwayat[count($riwayat)-1]->notulen = Karyawan::find($data->notulen)->nama;
                    $riwayat[count($riwayat)-1]->moderator = Karyawan::find($data->moderator)->nama;
                    $riwayat[count($riwayat)-1]->status =  $data->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting'; //1=belum 2=dilaksanakanq
                }
                 $obj->riwayat = $riwayat;
           }else{
                $obj->riwayat = array();
           }

            return response()->json($obj);

        }else{
            $data = array();
        }

    } catch (\Throwable $th) {
        return response()->json([
            'status' => 200,
            'message' => 'Gagal',
        ], 500);
    }

    }

    public function cetakUndangan($id) {
        return PDF::loadView('page.meeting.undangan', compact('id'))->setPaper('a4', 'potrait')->stream('undangan.pdf');
    }

    public function cetakHasil($id)
    {
        return PDF::loadView('page.meeting.hasil', compact('id'))->setPaper('a4', 'potrait')->stream('undangan.pdf');
    }
}
