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
use Kreait\Firebase\Exception\FirebaseException;
use Google\Cloud\Firestore\FirestoreClient;
use PDF;
use Illuminate\Support\Str;

class MeetingController extends Controller
{
    //
    public function store_jadwal_meet(Request $request){
        DB::beginTransaction();
        try {
            //code...
            $max = JadwalMeeting::whereYear('created_at', now()->year)->max('urutan')+1;
            $jm =   JadwalMeeting::create([
                "urutan" => $max,
                "judul" => $request->judul,
                "tgl_meeting" => $request->tanggal,
                "mulai" =>  $request->mulai,
                "selesai" => $request->selesai,
                "lokasi" =>  $request->lokasi,
                "status" => 1, //1=belum 2=dilaksanakan
                "notulen" =>  $request->notulen,
                "moderator" =>  $request->moderator,
                "deskripsi" => $request->deskripsi
            ]);

            if (count($request->peserta) > 0) {
                foreach ($request->peserta as $p) {
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
            ], 500);
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return response()->json([
                'status' => 200,
                'message' => 'Gagal Di Tambahkan'.$th->getMessage(),
            ], 500);
        }
    }

    public function store_notulen_meet(Request $request)
    {
        dd($request->all());
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
    public function store_hasil_meet(Request $request)
    {
        $obj =  json_decode(json_encode($request->all()), FALSE);
        dd($obj);
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

    public function update_hasil_meet(Request $request, $id)
    {
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

    public function delete_hasil_meet($id)
    {
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

    public function show_hasil_meet($id)
    {
        try {
            //code...
            //code...
            $data = HasilMeeting::where('meeting_id', $id)->get();
            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    $obj[] = (object)[
                        "id" => $d->id,
                        "isi" => $d->isi,
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

    public function edit_hasil_meet($id)
    {
        try {
            $data = HasilMeeting::find($id);
            if (!$data) {
                $data = array();
            }
            return response()->json($data);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 200,
                'message' => 'Gagal' . $th->getMessage(),
            ], 500);
        }
    }

    public function batal_jadwal_meet(Request $request)
    {
        try {
            //code...
            $data = JadwalMeeting::find($request->id);
            $data->status = 0;
            $data->save();
            return response()->json([
                'status' => 200,
                'message' => 'Berhasil Diubah',
            ], 200);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 200,
                'message' => 'Gagal',
            ], 500);
        }
    }

    public function update_jadwal_meet(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $data = JadwalMeeting::find($id);
            if ($data) {

                $obj = new stdClass();
                $obj->urutan =  $data->urutan;
                $obj->judul = $data->judul;
                $obj->tgl_meeting =  $data->tgl_meeting;
                $obj->mulai =  $data->mulai;
                $obj->selesai =  $data->selesai;
                $obj->lokasi =  $data->lokasi;
                $obj->status =  $data->status;
                $obj->notulen =   $data->notulen;
                $obj->moderator =   $data->moderator;
                $obj->deskripsi =  $data->deskripsi;

                if (count($data->PesertaMeeting) > 0) {
                    foreach ($data->PesertaMeeting as $p) {
                        $peserta[] = (object)[
                            'id' => $p->id,
                            'karyawan_id' => $p->id,
                            'nama' => $p->Karyawan->nama,
                            'jabatan' => $p->Karyawan->Divisi->nama,
                            'status' => $p->status,
                            'ket' => $p->ket,
                        ];
                    }
                    $obj->peserta = $peserta;
                    PesertaMeeting::where('meeting_id', $id)->delete();
                } else {
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

                if (count($request->peserta) > 0) {
                    foreach ($request->peserta as $p) {
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
            } else {
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
                'message' => 'Gagal Di Ubah' . $th->getMessage(),
            ], 500);
        }
    }

    public function update_hadir_jadwal_meet(Request $request)
    {
        DB::beginTransaction();
        try {
            //code...
            PesertaMeeting::where(['id' => $request->id])->update(['status' => $request->status]);
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
                'message' => 'Gagal Di Ubah',
            ], 500);
        }

    }

    public function show_jadwal_meet_person_belum($id)
    {

        try {
            //code...
            $data = PesertaMeeting::
            leftJoin('jadwal_meeting','jadwal_meeting.id','=','peserta_meeting.meeting_id')
            ->where('karyawan_id',$id)
            ->whereIN('jadwal_meeting.status',[1])
            ->with('JadwalMeeting')->get();

            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    if($d->status == 1){
                        $status_peserta = 'Hadir';
                    }elseif($d->status == 2){
                        $status_peserta = 'Tidak Hadir';
                    }else{
                        $status_peserta = 'Belum Mengisi';
                    }

                    if($d->JadwalMeeting->status == 1){
                        $status_meet = 'Belum Terlaksana';
                    }elseif($d->JadwalMeeting->status == 2){
                        $status_meet = 'Terlaksana';
                    }else{
                        $status_meet = 'Batal';
                    }

                    $obj[] = (object)[
                        "id" => $d->id,
                        "urutan" => 'Meet-' . $d->JadwalMeeting->urutan,
                        "judul" => $d->JadwalMeeting->judul,
                        "tanggal" => $d->JadwalMeeting->tgl_meeting,
                        "mulai" =>  $d->JadwalMeeting->mulai,
                        "selesai" => $d->JadwalMeeting->selesai,
                        "lokasi" =>  $d->JadwalMeeting->lokasi,
                        "status" =>  $status_meet,
                        "status_peserta" =>  $status_peserta,
                        "moderator" =>  $d->JadwalMeeting->moderator,
                        "deskripsi" => $d->JadwalMeeting->deskripsi,
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

    public function show_jadwal_meet_person_selesai($id)
    {
        try {
            //code...
            $data = PesertaMeeting::
            leftJoin('jadwal_meeting','jadwal_meeting.id','=','peserta_meeting.meeting_id')
            ->where('karyawan_id',$id)
            ->whereIN('jadwal_meeting.status',[0,2])
            ->with('JadwalMeeting')->get();

            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    if($d->status == 1){
                        $status_peserta = 'Hadir';
                    }elseif($d->status == 2){
                        $status_peserta = 'Tidak Hadir';
                    }else{
                        $status_peserta = 'Belum Mengisi';
                    }

                    if($d->JadwalMeeting->status == 1){
                        $status_meet = 'Belum Terlaksana';
                    }elseif($d->JadwalMeeting->status == 2){
                        $status_meet = 'Terlaksana';
                    }else{
                        $status_meet = 'Batal';
                    }

                    $obj[] = (object)[
                        "id" => $d->id,
                        "urutan" => 'Meet-' . $d->JadwalMeeting->urutan,
                        "judul" => $d->JadwalMeeting->judul,
                        "tanggal" => $d->JadwalMeeting->tgl_meeting,
                        "mulai" =>  $d->JadwalMeeting->mulai,
                        "selesai" => $d->JadwalMeeting->selesai,
                        "lokasi" =>  $d->JadwalMeeting->lokasi,
                        "status" =>  $status_meet,
                        "status_peserta" =>  $status_peserta,
                        "moderator" =>  $d->JadwalMeeting->moderator,
                        "deskripsi" => $d->JadwalMeeting->deskripsi,
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
    public function show_jadwal_meet()
    {

        try {
            //code...
            $data = JadwalMeeting::where('status',1)->get();

            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    if($d->status == 1){
                        $status_meet = 'Belum Terlaksana';
                    }elseif($d->status == 2){
                        $status_meet = 'Terlaksana';
                    }else{
                        $status_meet = 'Batal';
                    }

                    $obj[] = (object)[
                        "id" => $d->id,
                        "urutan" => 'Meet-' . $d->urutan,
                        "judul" => $d->judul,
                        "tanggal" => $d->tgl_meeting,
                        "mulai" =>  $d->mulai,
                        "selesai" => $d->selesai,
                        "lokasi" =>  $d->lokasi,
                        "status" =>  $status_meet,
                        "notulen" =>  $d->notulen,
                        "moderator" =>  $d->moderator,
                        "deskripsi" => $d->deskripsi,
                        "peserta" => PesertaMeeting::select('karyawan_id')->where('meeting_id', $d->id)->get()->map(function ($item) {
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

    public function show_notulen_meet($id)
    {
        try {
            //code...
            $data = DB::table('erp_meetings.hasil_notulen')
                ->select('hasil_notulen.id', 'pic.nama as pic', 'verif.nama as verif', 'hasil_notulen.checked_at', 'hasil_notulen.hasil', 'hasil_notulen.uraian', 'hasil_notulen.ket')
                ->leftjoin('erp_kesehatan.karyawans as pic', 'pic.id', '=', 'hasil_notulen.pic_id')
                ->leftjoin('erp_kesehatan.karyawans as verif', 'verif.id', '=', 'hasil_notulen.verif_id')
                ->where('hasil_notulen.meeting_id', $id)
                ->get();
            if ($data->isEmpty()) {
                $obj = array();
            } else {
                foreach ($data as $d) {
                    $obj[] = (object)[
                        "id" => $d->id,
                        "pic" => $d->pic,
                        "uraian" => $d->uraian,
                        "dicek_oleh" => $d->verif,
                        "tgl_cek" => $d->checked_at,
                        "hasil_notulen" => $d->hasil,
                        "ket" => $d->ket,
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

    public function detail_jadwal_meet_person($id)
    {
        try {
            //code...
            $detail = PesertaMeeting::find($id);

            $jadwal = JadwalMeeting::find($detail->meeting_id);

            //Peserta
            $peserta = array();
            $riwayat = array();
            if ($jadwal->PesertaMeeting->count() > 0){
                foreach($jadwal->PesertaMeeting as $j){
                    $peserta[] = array(
                        'id' => $j->id,
                        'nama' => $j->Karyawan->nama,
                        'jabatan' => $j->Karyawan->Divisi->nama
                    );
                }
            }

            if($jadwal->RiwayatJadwalMeeting->count() > 0){
                foreach($jadwal->RiwayatJadwalMeeting as $j){
                    $x = json_decode($j->isi);
                    $riwayat[] =  array(
                        'urutan' =>  'Meet-' . $x->urutan,
                        'judul' => $x->judul,
                        'tgl_meeting' => $x->tgl_meeting,
                        'mulai' =>  $x->mulai,
                        'selesai' =>  $x->selesai,
                        'lokasi' =>  $x->lokasi,
                        'status' =>  $x->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting', //1=belum 2=dilaksanakan
                        'notulen' =>   Karyawan::find($x->notulen)->nama,
                        'moderator' =>   Karyawan::find($x->moderator)->nama,
                        'deskripsi' =>  $x->deskripsi,
                        'peserta' =>  $x->peserta,
                        'alasan_perubahan_meeting' =>  $j->ket,
                    );

                }
            }

            $obj = new stdClass();
            $obj->urutan =  'Meet-' . $jadwal->urutan;
            $obj->judul = $jadwal->judul;
            $obj->tgl_meeting =  $jadwal->tgl_meeting;
            $obj->mulai =  $jadwal->mulai;
            $obj->selesai =  $jadwal->selesai;
            $obj->lokasi =  $jadwal->lokasi;
            $obj->status =  $jadwal->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting'; //1=belum 2=dilaksanakan
            $obj->notulen =   Karyawan::find($jadwal->notulen)->nama;
            $obj->moderator =   Karyawan::find($jadwal->moderator)->nama;
            $obj->deskripsi =  $jadwal->deskripsi;
            $obj->peserta =  $peserta;

            $riwayat[] =  $obj;

            $newobj = new stdClass();
            $newobj->urutan =  'Meet-' . $jadwal->urutan;
            $newobj->judul = $jadwal->judul;
            $newobj->tgl_meeting =  $jadwal->tgl_meeting;
            $newobj->mulai =  $jadwal->mulai;
            $newobj->selesai =  $jadwal->selesai;
            $newobj->lokasi =  $jadwal->lokasi;
            $newobj->status =  $jadwal->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting'; //1=belum 2=dilaksanakan
            $newobj->notulen =   Karyawan::find($jadwal->notulen)->nama;
            $newobj->moderator =   Karyawan::find($jadwal->moderator)->nama;
            $newobj->deskripsi =  $jadwal->deskripsi;
            $newobj->riwayat =  $riwayat;


            $data = array(
                'kehadiran' => $newobj
            );

            return $data;

        } catch (\Throwable $th) {
            //throw $th;
            return response()->json([
                'status' => 200,
                'message' => 'Gagal'.$th->getMessage(),
            ], 500);
        }
    }

    public function detail_jadwal_meet($id)
    {
        try {
            $jadwal = JadwalMeeting::find($id);

            //Peserta
            $peserta = array();
            $riwayat = array();
            if ($jadwal->PesertaMeeting->count() > 0){
                foreach($jadwal->PesertaMeeting as $j){
                    $peserta[] = array(
                        'id' => $j->id,
                        'nama' => $j->Karyawan->nama,
                        'jabatan' => $j->Karyawan->Divisi->nama
                    );
                }
            }


            if($jadwal->RiwayatJadwalMeeting->count() > 0){
                foreach($jadwal->RiwayatJadwalMeeting as $j){
                    $x = json_decode($j->isi);
                    $riwayat[] =  array(
                        'urutan' =>  'Meet-' . $x->urutan,
                        'judul' => $x->judul,
                        'tgl_meeting' => $x->tgl_meeting,
                        'mulai' =>  $x->mulai,
                        'selesai' =>  $x->selesai,
                        'lokasi' =>  $x->lokasi,
                        'status' =>  $x->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting', //1=belum 2=dilaksanakan
                        'notulen' =>   Karyawan::find($x->notulen)->nama,
                        'moderator' =>   Karyawan::find($x->moderator)->nama,
                        'deskripsi' =>  $x->deskripsi,
                        'peserta' =>  $x->peserta,
                        'alasan_perubahan_meeting' =>  $j->ket,
                    );

                }
            }

            $obj = new stdClass();
            $obj->urutan =  'Meet-' . $jadwal->urutan;
            $obj->judul = $jadwal->judul;
            $obj->tgl_meeting =  $jadwal->tgl_meeting;
            $obj->mulai =  $jadwal->mulai;
            $obj->selesai =  $jadwal->selesai;
            $obj->lokasi =  $jadwal->lokasi;
            $obj->status =  $jadwal->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting'; //1=belum 2=dilaksanakan
            $obj->notulen =   Karyawan::find($jadwal->notulen)->nama;
            $obj->moderator =   Karyawan::find($jadwal->moderator)->nama;
            $obj->deskripsi =  $jadwal->deskripsi;
            $obj->peserta =  $peserta;

            $riwayat[] =  $obj;

            $newobj = new stdClass();
            $newobj->urutan =  'Meet-' . $jadwal->urutan;
            $newobj->judul = $jadwal->judul;
            $newobj->tgl_meeting =  $jadwal->tgl_meeting;
            $newobj->mulai =  $jadwal->mulai;
            $newobj->selesai =  $jadwal->selesai;
            $newobj->lokasi =  $jadwal->lokasi;
            $newobj->status =  $jadwal->status == 1 ? 'belum_terlaksana' : 'menyusun_hasil_meeting'; //1=belum 2=dilaksanakan
            $newobj->notulen =   Karyawan::find($jadwal->notulen)->nama;
            $newobj->moderator =   Karyawan::find($jadwal->moderator)->nama;
            $newobj->deskripsi =  $jadwal->deskripsi;
            $newobj->peserta =  $peserta;
            $newobj->riwayat =  $riwayat;

            return $newobj;
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 200,
                'message' => 'Gagal'.$th->getMessage(),
            ], 500);
        }
    }

    public function cetakUndangan($id)
    {
        $data = $this->detail_jadwal_meet($id);
        // return view('page.meeting.undangan', compact('data'));
        return PDF::loadView('page.meeting.undangan', compact('data'))->setPaper('a4', 'potrait')->stream('undangan.pdf');
    }

    public function cetakHasil($id)
    {
        return PDF::loadView('page.meeting.hasil', compact('id'))->setPaper('a4', 'potrait')->stream('undangan.pdf');
    }

    public function upload_dokumen(Request $request)
    {
        foreach($request->file('image') as $image){
            $randomCollectionName = uniqid('collection_', false);
            $randomCollectionName = Str::uuid()->toString();

            $set = app('firebase.firestore')->database()->collection($randomCollectionName);
            $firebase_storage_path = 'Images/';
            $name = $set->id();
            $localfolder = public_path('firebase-temp-uploads') .'/';
            $extension = $image->getClientOriginalExtension();
            $file      = $name. '.' . $extension;

            if ($image->move($localfolder, $file)) {
                     $uploadedfile = fopen($localfolder.$file, 'r');
                     $x =  app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
                     $downloadUrl[] = $x->signedUrl(new \DateTime('+1 hour'));
                     unlink($localfolder . $file);
                    }
        }
        return $downloadUrl;
    }
    // public function upload_dokumen(Request $request)
    // {
    //     dd($request->all());
    //     $image = $request->file('image'); //image file from frontend

    //     $randomCollectionName = uniqid('collection_', false);
    //     $randomCollectionName = Str::uuid()->toString();

    //     $student   = app('firebase.firestore')->database()->collection($randomCollectionName);
    //     $firebase_storage_path = 'Images/';
    //     $name     = $student->id();
    //     $localfolder = public_path('firebase-temp-uploads') .'/';
    //     $extension = $image->getClientOriginalExtension();
    //     $file      = $name. '.' . $extension;
    //     if ($image->move($localfolder, $file)) {
    //       $uploadedfile = fopen($localfolder.$file, 'r');
    //      $x =  app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);
    //      $downloadUrl = $x->signedUrl(new \DateTime('+1 hour'));
    //       unlink($localfolder . $file);

    //     }
    //     return $downloadUrl;
    // }
}
