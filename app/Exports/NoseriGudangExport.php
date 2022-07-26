<?php

namespace App\Exports;

use App\Models\GudangBarangJadi;
use App\Models\NoseriBarangJadi;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class NoseriGudangExport implements FromView
{
   public function view(): View
   {
        // $data = NoseriBarangJadi::with(['gudang.produk'])
        //         ->whereNotIn('is_aktif', [0])
        //         ->get();
        $data = GudangBarangJadi::with(['produk', 'noseri'])
                ->whereHas('noseri', function($q){
                    $q->whereNotIn('is_aktif', [0]);
                })
                ->get();
        return view('page.gbj.reports.noseri', [
            'data' => $data
        ]);
   }
}
