<?php

namespace App\Exports\Sheets;

use App\Models\ReturPenjualan;
use App\Models\Perbaikan;
use App\Models\NoseriPerbaikan;
use App\Models\PengirimanNoseri;
use App\Models\PengirimanPart;
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



class SheetAfterSalesRetur implements FromView, ShouldAutoSize, WithStyles, WithEvents, WithTitle
{

    public function tgl_awal()
    {
        return $this->tgl_awal;
    }
    public function tgl_akhir()
    {
        return $this->tgl_akhir;
    }

    public function __construct(string $tgl_awal, string $tgl_akhir)
    {
        $this->tgl_awal = $tgl_awal;
        $this->tgl_akhir = $tgl_akhir;
    }


    // public function columnFormats(): array
    // {
    //     return [
    //         'M' => NumberFormat::FORMAT_NUMBER,
    //         'M' => NumberFormat::FORMAT_NUMBER,
    //         'I' => NumberFormat::FORMAT_TEXT
    //     ];
    // }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
        $sheet->getStyle('A2:Q2')->getFont()->setBold(true);
        $sheet->getStyle('A:Q')->getAlignment()
        ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        $sheet->getStyle('a2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FFBA5A');
        $sheet->getStyle('b2:g2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('2EC1AC');
        $sheet->getStyle('h2:i2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('B7B78A');
        $sheet->getStyle('l2:o2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('FB7813');
        $sheet->getStyle('j2:k2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('B6EB7A');
        $sheet->getStyle('p2:q2')->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('4BC2C5');

        $sheet->getStyle('A:Q')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $sheet->getColumnDimension('C')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('C')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('D')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('D')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('G')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('G')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(30);
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('N')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('N')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('J')->setAutoSize(false)->setWidth(35);
        $sheet->getStyle('J')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('K')->setAutoSize(false)->setWidth(20);
        $sheet->getStyle('K')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('O')->setAutoSize(false)->setWidth(50);
        $sheet->getStyle('O')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('H')->setAutoSize(false)->setWidth(15);
        $sheet->getStyle('H')->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('I')->setAutoSize(false)->setWidth(15);
        $sheet->getStyle('I')->getAlignment()->setWrapText(true);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event)  {
                $cellRange = 'A2:Q'.$event->sheet->getHighestRow();
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
        $from = date($this->tgl_awal);
        $to = date($this->tgl_akhir);
        $array = array();
        $data = ReturPenjualan::whereBetween('tgl_retur', [$from, $to])->whereHas('TFProduksi', function($q){
            $q->where('jenis', 'masuk');
            })->addSelect(['count_noseri' => function($q){
                $q->selectRaw('coalesce(count(t_gbj_noseri.id), 0)')
                ->from('t_gbj_noseri')
                ->join('t_gbj_detail', 't_gbj_detail.id', '=', 't_gbj_noseri.t_gbj_detail_id')
                ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->where('t_gbj.jenis', '=', 'masuk')
                ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
            },'count_perbaikan_karantina' => function($q){
                $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
                ->from('noseri_perbaikan')
                ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
                ->where('noseri_perbaikan.m_status_id', '=', '2')
                ->where('noseri_perbaikan.noseri_pengganti_id', '=', NULL)
                ->where('noseri_perbaikan.tindak_lanjut', '=', 'karantina')
                ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
            },'count_perbaikan' => function($q){
                $q->selectRaw('coalesce(count(noseri_perbaikan.id),0)')
                ->from('noseri_perbaikan')
                ->join('perbaikan', 'perbaikan.id', '=', 'noseri_perbaikan.perbaikan_id')
                ->where('noseri_perbaikan.m_status_id', '=', '2')
                ->where('noseri_perbaikan.tindak_lanjut', '!=', 'karantina')
                ->whereColumn('perbaikan.retur_id', 'retur_penjualan.id');
            }, 'count_kirim_noseri' => function($q){
                $q->selectRaw('coalesce(COUNT(pengiriman_noseri.id), 0)')
                ->from('pengiriman_noseri')
                ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_noseri.pengiriman_id')
                ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
            }, 'count_part' => function($q){
                $q->selectRaw('coalesce(SUM(t_gbj_detail.qty), 0)')
                ->from('t_gbj_detail')
                ->join('t_gbj', 't_gbj.id', '=', 't_gbj_detail.t_gbj_id')
                ->where('t_gbj.jenis', 'masuk')
                ->whereNotNull('t_gbj_detail.m_sparepart_id')
                ->whereColumn('t_gbj.retur_penjualan_id', 'retur_penjualan.id');
            }, 'count_kirim_part' => function($q){
                $q->selectRaw('coalesce(SUM(pengiriman_part.jumlah), 0)')
                ->from('pengiriman_part')
                ->join('pengiriman', 'pengiriman.id', '=', 'pengiriman_part.pengiriman_id')
                ->whereColumn('pengiriman.retur_penjualan_id', 'retur_penjualan.id');
        }])->get();

        foreach($data as $key => $i){
            $perbaikan = 100;
            if($i->count_noseri > 0){
                $perbaikan = floor((($i->count_perbaikan + $i->count_perbaikan_karantina) / $i->count_noseri) * 100);
            }

            $pengiriman = floor ((($i->count_perbaikan_karantina + $i->count_kirim_noseri + $i->count_kirim_part) / ($i->count_part + $i->count_noseri)) * 100);

            $array[$key] = array('id' => $i->id,
                'produk' => array(),
                'no_retur' => $i->no_retur,
                'tgl_retur' => \Carbon\Carbon::createFromFormat('Y-m-d', $i->tgl_retur)->format('d-m-Y'),
                'jumlah' => count($i->TFProduksi->detail),
                'ket' => $i->keterangan,
                'pic' => $i->karyawan_id != NULL ? $i->karyawan->nama : $i->pic,
                'telp_pic' => $i->telp_pic != NULL ? "(".$i->telp_pic.")" : '',
                'customer' => $i->Customer->nama,
                'jenis' => $i->jenis != "none" ? $i->jenis : 'tanpa status',
                'prog_pengiriman' => $i->state_id != "10" ? $pengiriman : 100,
                'prog_perbaikan' => $perbaikan,
                'row' => 0
            );
            $row = 0;
            foreach($i->TFProduksi->detail as $keys => $j){
                if($j->gdg_brg_jadi_id != NULL){
                $row = $row + (count($j->seri) > 0 ? count($j->seri) : 1);
                $array[$key]['produk'][$keys] = array(
                    'id' => $j->gdg_brg_jadi_id,
                    'jumlah_unit' => count($j->seri) > 0 ? count($j->seri) : 1,
                    'noseri' => array(),
                    'produk' => $j->produk->produk->nama.' '.$j->produk->nama
                );

                foreach($j->seri as $keyz => $k){
                    $gbj = $j->gdg_brg_jadi_id;
                    $ret = $i->id;
                    $p = NoseriPerbaikan::whereHas('Perbaikan', function($q) use($gbj, $ret){
                        $q->where([['gdg_barang_jadi_id', '=', $gbj], ['retur_id', '=', $ret]]);
                    })->where('noseri_barang_jadi_id', $k->seri->id)->first();
                    $array[$key]['produk'][$keys]['noseri'][$keyz] = array(
                        'noseri' => $k->seri->noseri,
                        'noseri_pengganti' => $p != NULL ? ($p->noseri_pengganti_id != NULL ? $p->NoseriPengganti->noseri : '') : '',
                        'tindak_lanjut' => $p != NULL ? $p->tindak_lanjut : '',
                        'no_perbaikan' => $p != NULL ? $p->Perbaikan->no_perbaikan : '',
                        'tgl_perbaikan' => $p != NULL ? \Carbon\Carbon::createFromFormat('Y-m-d', $p->Perbaikan->tanggal)->format('d-m-Y') : '',
                        'keterangan' => $p != NULL ? $p->Perbaikan->keterangan : '',
                        'no_sj' => NULL,
                        'tgl_kirim' => NULL
                    );
                    $currseri = $p != NULL ? ($p->noseri_pengganti_id != NULL ? $p->noseri_pengganti_id : $k->seri->id) : $k->seri->id;
                    $l = PengirimanNoseri::whereHas('Pengiriman', function($q) use($ret){
                        $q->where('retur_penjualan_id', '=', $ret);
                    })->where('noseri_barang_jadi_id', $currseri)->first();
                    $array[$key]['produk'][$keys]['noseri'][$keyz]['no_sj'] = $l != NULL ? $l->Pengiriman->no_pengiriman : '';
                    $array[$key]['produk'][$keys]['noseri'][$keyz]['tgl_kirim'] = $l != NULL ? \Carbon\Carbon::createFromFormat('Y-m-d', $l->Pengiriman->tanggal)->format('d-m-Y') : '';
                }
            }else{
                $row = $row + 1;
                    $array[$key]['produk'][$keys] = array(
                        'id' => $j->m_sparepart_id,
                        'jumlah_unit' => 1,
                        'noseri' => array(),
                        'produk' => $j->Sparepart->nama." ".$j->qty
                    );

                    $part = $j->m_sparepart_id;
                    $ret = $i->id;
                    $array[$key]['produk'][$keys]['noseri'][0] = array(
                        'noseri' => '',
                        'noseri_pengganti' => '',
                        'tindak_lanjut' => '',
                        'no_perbaikan' => '',
                        'tgl_perbaikan' => '',
                        'keterangan' => '',
                        'no_sj' => NULL,
                        'tgl_kirim' => NULL
                    );
                    $l = PengirimanPart::whereHas('Pengiriman', function($q) use($ret){
                        $q->where('retur_penjualan_id', '=', $ret);
                    })->where('m_sparepart_id', $part)->first();
                    $array[$key]['produk'][$keys]['noseri'][0]['no_sj'] = $l != NULL ? $l->Pengiriman->no_pengiriman : '';
                    $array[$key]['produk'][$keys]['noseri'][0]['tgl_kirim'] = $l != NULL ? $l->Pengiriman->tanggal : '';
            }
            }
            $array[$key]['row'] = $row;
        }
        $header = 'Laporan After Sales Keluhan Pelanggan '.\Carbon\Carbon::createFromFormat('Y-m-d', $from)->format('d/m/Y')." s/d ".\Carbon\Carbon::createFromFormat('Y-m-d', $to)->format('d/m/Y');;
        return view('page.as.laporan.LaporanAfterSalesRetur', ['data' => $array, 'header' => $header]);
    }

    public function title(): string
    {
        return 'Laporan After Sales';
    }
}
