<?php

namespace App\Exports;

use App\Exports\Sheets\SheetAfterSalesRetur;
use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\DetailRencanaPenjualan;
use App\Models\Ekatalog;
use App\Models\RencanaPenjualan;
use App\Models\Spa;
use App\Models\Spb;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\DateFormatter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class LaporanAfterSalesRetur implements WithMultipleSheets
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function __construct(string $tgl_awal, string $tgl_akhir)
    {
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }
    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new SheetAfterSalesRetur($this->tgl_awal, $this->tgl_akhir);
        return $sheets;
    }
}
