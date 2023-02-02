<?php

namespace App\Exports\Sheets;

use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\DetailPesananPart;
use App\Models\DetailRencanaPenjualan;
use App\Models\RencanaPenjualan;
use App\Models\TblSiswa;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetBerdasarkanPaket implements WithTitle, FromView, ShouldAutoSize, WithStyles, WithColumnFormatting
{
    /**
     * @return \Illuminate\Support\Collection
     */

    public function jenis_penjualan()
    {
        return $this->jenis_penjualan;
    }
    public function distributor()
    {
        return $this->distributor;
    }
    public function tgl_awal()
    {
        return $this->tgl_awal;
    }
    public function tgl_akhir()
    {
        return $this->tgl_akhir;
    }
    public function seri()
    {
        return $this->seri;
    }
    public function jenis_laporan()
    {
        return $this->jenis_laporan;
    }

    public function __construct(string $jenis_penjualan, string $distributor, string $tgl_awal,  string $tgl_akhir, string $seri, string $jenis_laporan)
    {
        $this->jenis_penjualan = $jenis_penjualan;
        $this->distributor = $distributor;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
        $this->seri = $seri;
        $this->jenis_laporan = $jenis_laporan;
    }

    public function columnFormats(): array
    {
        return [
            'Q' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'R' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,

        ];
    }

    public function styles(Worksheet $sheet)
    {


        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:w2')->getFont()->setBold(true);

        $sheet->getStyle('b2:f2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('f2:g2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');

        $sheet->getStyle('g2:k2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('l2:m2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00b359');

        $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('K')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('N')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('O')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('O')->getAlignment()->setWrapText(true);

        if ($this->seri != "kosong") {
            $sheet->getColumnDimension('P')->setAutoSize(false)->setWidth(70);
            $sheet->getStyle('P')->getAlignment()->setWrapText(true);
            $sheet->getStyle('n2:t2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('89d0b4');
            $sheet->getStyle('u2:w2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('f99c83');
            $sheet->getStyle('U:V')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        } else {
            $sheet->getStyle('n2:s2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('89d0b4');
            $sheet->getStyle('t2:v2')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('f99c83');
            $sheet->getStyle('T:U')->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        }

        $sheet->getStyle('A:W')->getAlignment()
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('A:H')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('L:M')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }

    public function view(): View
    {
        $distributor = $this->distributor;
        $jenis_laporan = $this->jenis_laporan;
        $seri = $this->seri;
        $x = explode(',', $this->jenis_penjualan);
        $tanggal_awal = $this->tgl_awal;
        $tanggal_akhir = $this->tgl_akhir;

        if ($distributor == 'semua') {
            $ekat  = Pesanan::wherenotnull('no_po')
                ->orderby('so', 'ASC')
                ->where('so', 'LIKE', '%EKAT%')
                ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                ->get();

            $spa  = Pesanan::wherenotnull('no_po')
                ->orderby('so', 'ASC')
                ->where('so', 'LIKE', '%SPA%')
                ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                ->get();

            $spb  = Pesanan::wherenotnull('no_po')
                ->orderby('so', 'ASC')
                ->where('so', 'LIKE', '%SPB%')
                ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                ->get();
        } else {
            $ekat  = Pesanan::wherenotnull('no_po')
                ->whereHas('Ekatalog', function ($q) use ($distributor) {
                    $q->where('customer_id', $distributor);
                })
                ->orderby('so', 'ASC')
                ->where('so', 'LIKE', '%EKAT%')
                ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                ->get();

            $spa  = Pesanan::wherenotnull('no_po')
                ->whereHas('Spa', function ($q) use ($distributor) {
                    $q->where('customer_id', $distributor);
                })
                ->orderby('so', 'ASC')
                ->where('so', 'LIKE', '%SPA%')
                ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                ->get();

            $spb  = Pesanan::wherenotnull('no_po')
                ->whereHas('Spb', function ($q) use ($distributor) {
                    $q->where('customer_id', $distributor);
                })
                ->orderby('so', 'ASC')
                ->where('so', 'LIKE', '%SPB%')
                ->whereBetween('tgl_po', [$tanggal_awal, $tanggal_akhir])
                ->get();
        }




        if ($x == ['ekatalog', 'spa', 'spb']) {
            $data = $ekat->merge($spa)->merge($spb);
        } else if ($x == ['ekatalog', 'spa']) {
            $data = $ekat->merge($spa);
        } else if ($x == ['ekatalog', 'spb']) {
            $data = $ekat->merge($spb);
        } else if ($x == ['spa', 'spb']) {
            $data = $spa->merge($spb);
        } else if ($this->jenis_penjualan == 'ekatalog') {
            $data = $ekat;
        } else if ($this->jenis_penjualan == 'spa') {
            $data = $spa;
        } else if ($this->jenis_penjualan == 'spb') {
            $data = $spb;
        }
        return view('page.penjualan.penjualan.LaporanPenjualanPaketEx', ['data' => $data, 'jenis_laporan' => $jenis_laporan, 'seri' => $seri]);
    }
    public function title(): string
    {
        return 'Sudah PO';
    }
}
