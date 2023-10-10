<?php

namespace App\Http\Controllers\administrator;

use App\Http\Controllers\Controller;
use App\Models\Divisi;
use App\Models\kesehatan\Karyawan;
use App\Models\SystemLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdministratorController extends Controller
{

    public function  get_change_status_user(Request $request)
    {
        $user = User::find($request->id);
        if ($user->is_aktif == 0) {
            $status = 1;
        } else {
            $status = 0;
        }
        $user->is_aktif = $status;
        $user->save();


        $save =  SystemLog::create([
            'tipe' => 'IT - Admin',
            'subjek' => 'Ubah Status User',
            'user_id' => Auth::user()->id

        ]);

        $field = array(
            'id' => $request->id,
            'username' => $user->username,
            'nama' => $user->Karyawan->nama,
            'divisi' => $user->Karyawan->Divisi->nama,
            'status_lama' => $user->is_aktif == 1 ? 'tidak aktif' : 'aktif',
            'status_baru' =>  $user->is_aktif == 1 ? 'aktif' : 'tidak aktif'
        );

        $data = json_encode($field);
        $get_response = SystemLog::find($save->id);
        $get_response->response = $data;
        $get_response->save();

        return response()->json(['info' => 'success', 'msg' => 'Data berhasil di hapus']);
    }
    public function  get_data_user()
    {
        $data = User::with('Karyawan.Divisi')->get();
        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('akses', function ($data) {
                return $data->Divisi->nama;
            })
            ->addColumn('divisi', function ($data) {
                return $data->Karyawan->Divisi->nama ?? '-';
            })
            ->addColumn('username', function ($data) {
                return $data->username;
            })
            ->addColumn('nama', function ($data) {
                return $data->Karyawan->nama ?? '-';
            })
            ->addColumn('email', function ($data) {
                return $data->email;
            })
            ->addColumn('button', function ($data) {
                $datas = "";

                if ($data->is_aktif == 1) {
                    $datas .= '<label class="switch">
                    <input type="checkbox" checked id="status_user" data-id="' . $data->id . '">
                    <span class="slider round"></span>
                  </label>';
                } else {
                    $datas .= '<label class="switch">
                    <input type="checkbox" id="status_user"  data-id="' . $data->id . '">
                    <span class="slider round"></span>
                  </label>';
                }
                return $datas;
            })
            ->addColumn('edit', function ($data) {
                $datas = "";
                $datas .= '<button class="btn-info btn" data-id="' . $data->id . '" id="edit_user"><i class="fa fa-refresh" aria-hidden="true"></i> Edit</button>';
                return $datas;
            })
            ->rawColumns(['button', 'edit'])
            ->make(true);
    }

    public function get_data_user_modal($id)
    {
        $user_id = User::select('karyawan_id')->pluck('karyawan_id');
        $karyawan = Karyawan::whereNotIN('id', $user_id)->get();
        $user = User::find($id);
        $divisi = Divisi::all();

        return view("page.administrator.user.edit", ['karyawan' => $karyawan, 'user' => $user, 'divisi' => $divisi]);
    }
    public function get_create_user_modal()
    {
        $user_id = User::select('karyawan_id')->pluck('karyawan_id');
        $karyawan = Karyawan::whereNotIN('id', $user_id)->get();
        $divisi = Divisi::all();
        return view("page.administrator.user.create", ['karyawan' => $karyawan, 'divisi' => $divisi]);
    }
    public function get_store_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'email' => 'required',
            'karyawan' => 'required',
            'akses_divisi' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['data' => 'error', 'msg' => 'Data berhasil di hapus']);
        } else {
            $user = User::create([
                'nama' => '-',
                'username' => $request->username,
                'email' => $request->email,
                'karyawan_id' => $request->karyawan,
                'status' => 'online',
                'password' =>   Hash::make('sinkoprima'),
                'divisi_id' => $request->akses_divisi,

            ]);

            $save =  SystemLog::create([
                'tipe' => 'IT - Admin',
                'subjek' => ' User',
                'user_id' => Auth::user()->id

            ]);

            $field = array(
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'status' => 'aktif',
                'karyawan' => array(
                    'id' => $user->karyawan_id,
                    'nama' => $user->Karyawan->nama
                ),
            );



            $data = json_encode($field);
            $get_response = SystemLog::find($save->id);
            $get_response->response = $data;
            $get_response->save();

            return response()->json(['data' => 'success']);
        }
    }


    public function get_update_user(Request $request, $jenis, $id)
    {
        if ($jenis == 'data') {
            $validator = Validator::make($request->all(), [
                'username' => 'required',
                'email' => 'required',
                'karyawan' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['data' => 'error', 'msg' => 'Data berhasil di update']);
            } else {
                $user = User::find($id);
                $id_lama = $user->karyawan_id;
                $username_lama = $user->username;
                $email_lama = $user->email;
                $nama_lama = Karyawan::find($user->karyawan_id)->nama;

                $user->username = $request->username;
                $user->divisi_id = $request->akses_divisi;
                $user->email = $request->email;
                $user->karyawan_id = $request->karyawan;
                $user->save();

                $save =  SystemLog::create([
                    'tipe' => 'IT - Admin',
                    'subjek' => 'Ubah Data User',
                    'user_id' => Auth::user()->id

                ]);

                $field = array(
                    'id' => $id,
                    'data_baru' => array(
                        'username' => $user->username,
                        'email' => $user->email,
                        'karyawan' => array(
                            'id' => $user->karyawan_id,
                            'nama' => $user->Karyawan->nama
                        ),
                    ),
                    'data_lama' => array(
                        'username' => $username_lama,
                        'email' => $email_lama,
                        'karyawan' => array(
                            'id' => $id_lama,
                            'nama' => $nama_lama
                        ),
                    ),
                );

                $data = json_encode($field);
                $get_response = SystemLog::find($save->id);
                $get_response->response = $data;
                $get_response->save();

                return response()->json(['data' => 'success']);
            }
        }
    }

    public function reset_pwd_user(Request $request, $id)
    {
        $user =  User::find($id);
        $user->password =  Hash::make('sinkoprima');
        $user->save();




        if ($user) {

            $save =  SystemLog::create([
                'tipe' => 'IT - Admin',
                'subjek' => 'Reset Password',
                'user_id' => Auth::user()->id

            ]);

            $field = array(
                'id' => $id,
                'username' => $user->username,
                'nama' => $user->Karyawan->nama,
                'divisi' => $user->Karyawan->Divisi->nama
            );

            $data = json_encode($field);
            $get_response = SystemLog::find($save->id);
            $get_response->response = $data;
            $get_response->save();

            return response()->json(['info' => 'success', 'msg' => 'Password berhasil di ubah']);
        } else {
            return response()->json(['info' => 'error', 'msg' => 'Password berhasil di ubah']);
        }
    }
}
