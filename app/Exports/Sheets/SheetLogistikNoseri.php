<?php

namespace App\Exports\Sheets;

use App\Models\DetailLogistik;
use App\Models\DetailLogistikPart;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat\DateFormatter;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;



class SheetLogistikNoseri implements FromView, ShouldAutoSize, WithStyles, WithEvents, WithColumnFormatting, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function jenis_laporan()
    {
        return $this->jenis_laporan;
    }
    public function ekspedisi()
    {
        return $this->ekspedisi;
    }
    public function tgl_awal()
    {
        return $this->tgl_awal;
    }
    public function tgl_akhir()
    {
        return $this->tgl_akhir;
    }

    public function __construct(string $jenis_laporan, string  $ekspedisi, string $tgl_awal, string $tgl_akhir)
    {
        $this->jenis_laporan = $jenis_laporan;
        $this->ekspedisi = $ekspedisi;
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }


    public function columnFormats(): array
    {
        return [
            'M' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'I' => NumberFormat::FORMAT_TEXT
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:O2')->getFont()->setBold(true);
        $sheet->getStyle('A:O')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('b2:c2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('d2:f2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('51adb9');
        $sheet->getStyle('g2:j2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('89d0b4');
        $sheet->getStyle('k2:n2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00ff7f');
        $sheet->getStyle('o2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('89d0b4');

        $sheet->getStyle('A:C')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('G:I')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('O')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('E')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('E')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(38);
        $sheet->getStyle('K')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('L')->setAutoSize(false)->setWidth(30);
        $sheet->getStyle('L')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(45);
        $sheet->getStyle('N')->getAlignment()->setWrapText(true);
    }

    public function registerEvents(): array
    {
    // $alphabetRange = range('A', 'Z');
    // // $alphabet = $alphabetRange[$this->totalValue+6]; // returns Alphabet

        return [
            AfterSheet::class => function(AfterSheet $event)  {
                $cellRange = 'A2:O'.$event->sheet->getHighestRow();
                $event->sheet->getStyle($cellRange)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ])->getAlignment()->setWrapText(true);
            },
        ];
    }


    public function view(): View
    {
        $header = $this->jenis_laporan;
        $eks = $this->ekspedisi;
        $awal = $this->tgl_awal;
        $akhir = $this->tgl_akhir;
        $prd = "";
        $prt = "";

        if ($header == "ekspedisi") {
            if ($eks != '0') {
                $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($eks, $awal, $akhir) {
                    $q->where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->orderBy('logistik_id', 'ASC')->get();
                $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($eks, $awal, $akhir) {
                    $q->where('ekspedisi_id', $eks)->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->orderBy('logistik_id', 'ASC')->get();
            } else {
                $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                    $q->whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->orderBy('logistik_id', 'ASC')->get();
                $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                    $q->whereNotNull('ekspedisi_id')->whereBetween('tgl_kirim', [$awal, $akhir]);
                })->orderBy('logistik_id', 'ASC')->get();
            }
        } else if ($header == "nonekspedisi") {
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->orderBy('logistik_id', 'ASC')->get();
            $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereNotNull('nama_pengirim')->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->orderBy('logistik_id', 'ASC')->get();
        } else {
            $prd = DetailLogistik::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->orderBy('logistik_id', 'ASC')->get();
            $prt = DetailLogistikPart::whereHas('Logistik', function ($q) use ($awal, $akhir) {
                $q->whereBetween('tgl_kirim', [$awal, $akhir]);
            })->orderBy('logistik_id', 'ASC')->get();
        }

        $data = $prd->merge($prt);

        return view('page.logistik.laporan.LaporanLogistikDetail', ['header' => $header, 'data' => $data, 'eks' => $eks, 'awal' => $awal, 'akhir' => $akhir]);
    }

    public function title(): string
    {
        return 'Per Detail Surat Jalan';
    }
}
