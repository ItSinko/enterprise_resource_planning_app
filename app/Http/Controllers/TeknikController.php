<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\teknik\BillOfMaterial;
use App\Models\teknik\DetailBillOfMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class TeknikController extends Controller
{
    public function bom_detail($id)
    {
        return view('page.teknik.bom.detail', ['id' => $id]);
    }

    public function bom_data_produk($id)
    {
        return view('page.teknik.bom.data.produk', ['id' => $id]);
    }

    public function update_bom(Request $request, $id)
    {
        dd($request->all());
        $validator = Validator::make($request->all(), [
            'kode' => 'required|unique:bill_of_material,kode,' . $id,
            'nama' => 'required|unique:bill_of_material,nama,' . $id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'gagal'
            ]);
        } else {
            $bom = BillOfMaterial::find($id);
            $bom->kode = $request->kode;
            $bom->nama = $request->nama;
            $bom->produk_id = $request->produk_id;
            $bom->is_aktif = $request->status;
            $bom->save();


            $dbom = DetailBillOfMaterial::whereHas('bom', function ($q) use ($id) {
                $q->where('bill_of_material_id', $id);
            })->get();

            if (count($dbom) > 0) {
                $deldbom = DetailBillOfMaterial::whereHas('bom', function ($q) use ($id) {
                    $q->where('bill_of_material_id', $id);
                })->delete();
                if (!$deldbom) {
                    $bool = false;
                }
            }

            for ($i = 0; $i < count($request->part); $i++) {
                DetailBillOfMaterial::create([
                    'bill_of_material_id' => $bom->id,
                    'part_id' => $request->part[$i]['id'],
                    'jumlah' => $request->part[$i]['jumlah'],
                    'satuan_id' => $request->part[$i]['satuan'],
                ]);
            }


            return response()->json([
                'status' => 'berhasil'
            ]);
        }
    }
    public function store_bom(Request $request)
    {
        dd($request->all());
        $validator = Validator::make($request->all(), [
            'kode' => 'required',
            'nama' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'gagal'
            ]);
        } else {
            $bom = BillOfMaterial::create([
                'produk_id' => $request->produk_id,
                'kode' => $request->kode,
                'nama' => $request->nama,
                'keterangan' => NULL,
                'is_aktif' => $request->status,
            ]);


            for ($i = 0; $i < count($request->part); $i++) {
                DetailBillOfMaterial::create([
                    'bill_of_material_id' => $bom->id,
                    'part_id' => $request->part[$i]['id'],
                    'jumlah' => $request->part[$i]['jumlah'],
                    'satuan_id' => $request->part[$i]['satuan'],
                ]);
            }


            return response()->json([
                'status' => 'berhasil'
            ]);
        }
    }

    public function edit_bom($id)
    {
        $bom = BillOfMaterial::find($id);
        $data = array();


        $data = array(
            'id' => $bom->id,
            'kode' => $bom->kode,
            'nama' => $bom->nama,
            'keterangan' => NULL,
            'status' => $bom->is_aktif,
        );


        foreach ($bom->detail_bom as $key_bom => $b) {
            $data['part'][$key_bom] = array(
                'namaPart' => array(
                    'value' =>  $b->Sparepart->id,
                    'satuan' => $b->Sparepart->satuan->id,
                    'label' => $b->Sparepart->nama,
                ),
                'jumlah' => $b->jumlah
            );
        }

        return response()->json(['data' => $data]);
    }
    public function get_data_bom()
    {
        $produk = Produk::whereHas('bom')->get();
        $data = array();

        foreach ($produk as $key_produk => $p) {
            $data[$key_produk] = array(
                'produk' => $p->nama,
                'detail' => array()
            );

            foreach ($p->bom as $key_bom => $b) {
                $data[$key_produk]['detail'][$key_bom] = array(
                    'id' => $b->id,
                    'kode' => $b->kode,
                    'nama' => $b->nama,
                    'status' => $b->is_aktif == 1 ? 'Aktif' : 'Tidak Aktif',
                    'tahun_pembuatan' => $b->created_at->format("Y")
                );
            }
        }
        return response()->json(['data' => $data]);
    }

    public function get_detail_bom($id)
    {
        $bom = BillOfMaterial::where('id', $id)->get();
        $data = array();
        foreach ($bom as $key_bom => $b) {
            $data[$key_bom] = array(
                'header' => array(
                    'produk' => $b->Produk->nama,
                    'nama_bom' => $b->nama,
                    'kode_bom' => $b->kode,
                    'tahun_pembuatan' => $b->created_at->format("Y") ,
                    'status' => $b->is_aktif == 1 ? 'Aktif' : 'Tidak Aktif',
                ),
                'data_tabel' => array(),
            );

            foreach ($b->detail_bom as $key_detail_bom => $d) {
                $data[$key_bom]['data_tabel'][$key_detail_bom] = array(
                    'id' => $d->id,
                    'kode' => $d->Sparepart->kode,
                    'nama' => $d->Sparepart->nama,
                    'jumlah' => $d->jumlah
                );
            }
        }
        return response()->json(['data' => $data]);
    }
}
