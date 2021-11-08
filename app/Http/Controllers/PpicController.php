<?php

namespace App\Http\Controllers;

// library
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Auth;

// model
use App\Models\Produk;
use App\Models\JadwalPerakitan;
use App\Models\GudangBarangJadi;

class PpicController extends Controller
{
    // API
    public function getEvent($status, Request $request)
    {
        $month = date('m');
        $year = date('Y');
        $event = JadwalPerakitan::with('Produk')->orderBy('tanggal_mulai', 'asc');

        if (isset($request->state)) $event->where('state', $request->state);

        if ($status == "pelaksanaan") {
            $event = $event->whereYear('tanggal_mulai', $year)->whereMonth('tanggal_mulai', $month)->get();
            $this->updateStatus($event, 'pelaksanaan');
        } else if ($status == "penyusunan") {
            $month += 1;
            $event = $event->where('tanggal_mulai', '>=', "$year-$month-01")->get();
            $this->updateStatus($event, 'penyusunan');
        } else {
            $event = $event->where('tanggal_mulai', '<', "$year-$month-01")->get();
            $this->updateStatus($event, 'selesai');
        }

        return $event;
    }

    public function getProduk()
    {
        $model = Produk::all();

        return $model;
    }

    public function addEvent(Request $request)
    {
        $data = [
            'produk_id' => $request->produk_id,
            'jumlah' => $request->jumlah,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'status' => $request->status,
            'state' => "perencanaan",
            'warna' => $request->warna,
            'konfirmasi' => 0
        ];
        JadwalPerakitan::create($data);

        return $this->getEvent($request->status, $request);
    }

    public function deleteEvent(Request $request)
    {
        JadwalPerakitan::destroy($request->id);
        return $this->getEvent($request->status, $request);
    }

    public function updateStatus($event, $status)
    {
        foreach ($event as $data) {
            $data->status = $status;
            $data->save();
        }
    }

    public function updateConfirmation(Request $request)
    {
        $event = JadwalPerakitan::where('status', $request->status)->get();
        foreach ($event as $data) {
            if (isset($request->state)) $data->state = $request->state;
            $data->konfirmasi = 0;
            // if (isset($request->konfirmasi_rencana)) $data->konfirmasi_rencana = $request->konfirmasi_rencana;
            // if (isset($request->konfirmasi_perubahan)) $data->konfirmasi_perubahan = $request->konfirmasi_perubahan;
            $data->save();
        }

        return $this->getEvent($request->status, $request);
    }

    public function resetConfirmation()
    {
        $event = JadwalPerakitan::all();
        foreach ($event as $data) {
            if ($data->status == "penyusunan") {
                $data->konfirmasi = 0;
                $data->state = "perencanaan";
            } else if ($data->status == "pelaksanaan") {
                $data->konfirmasi = 1;
                $data->state = "persetujuan";
            }
            $data->save();
        }

        return "success";
    }

    public function getGbjQuery()
    {
        $query = GudangBarangJadi::with('produk', 'noseri')->get();

        return $query;
    }

    public function getGbjDatatable()
    {
        $query = $this->getGbjQuery();

        return DataTables::of($query)->addIndexColumn()->make(true);
    }
}
