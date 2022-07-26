<?php

namespace App\Exports;

use App\Models\JadwalPerakitan;
use App\Models\JadwalRakitNoseri;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class NoseriRakitExport implements FromView, WithColumnWidths, WithEvents
{
   public function view(): View
   {
        // $data = JadwalRakitNoseri::with(['header', 'header.produk.produk'])
        //         ->orderBy('jadwal_id')
        //         ->orderBy('noseri')
        //         ->get();
        //         return $data;
        $data = JadwalPerakitan::with(['produk.produk', 'noseri'])
                ->whereNotIn('status', [6])
                ->get();
        return view('page.produksi.noseri_rakit', [
            'data' => $data
        ]);
   }

   public function columnWidths(): array
   {
        return [
            // 'A' => 10,
            // 'B' => 20,
            // 'C' => 55,
            // 'D' => 20,
            // 'E' => 20,
            // 'G' => 20
        ];
   }

   public function registerEvents(): array
   {
        return [
            AfterSheet::class => function(AfterSheet $sheet) {
                // $sheet->sheet->getDelegate()->setAutoFilter('A2:G3');
            }
        ];
   }
}
