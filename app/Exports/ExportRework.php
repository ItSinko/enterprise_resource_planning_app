<?php

namespace App\Exports;

use App\Models\SeriDetailRw;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExportRework implements  WithTitle, FromView, ShouldAutoSize, WithColumnFormatting
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function urutan()
    {
        return $this->urutan;
    }

    public function __construct(string $urutan)
    {
        $this->urutan = $urutan;
    }


    public function columnFormats(): array
    {
        return [
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
        ];
    }

    // public function styles(Worksheet $sheet)
    // {
    //     $sheet->getStyle('B1')->getFont()->setBold(true)->setSize(16);
    //     $sheet->getStyle('A3:m3')->getFont()->setBold(true);
    //     $sheet->getStyle('j2')->getFont()->setBold(true);
    // }

    public function view(): View
    {
        $urutan = $this->urutan;
        $data = SeriDetailRw::where('urutan',$urutan)->get();
        foreach($data as $d)
        {
            $o = json_decode($d->isi);
            $seri[] = array(
                'seri' => $d->noseri,
                'packer' => $d->packer,
                'tgl' => Carbon::createFromFormat('Y-m-d H:i:s', $d->created_at)->format('d M Y'),
                'item' => $o
            );
        }


        $collection = collect($seri);

        $collection = $collection->map(function ($item) {
            $item['item'] = collect($item['item'])->sortBy('produk')->values()->all();
            return $item;
        });


        $data_urut_produk = $collection->toArray();
        // return $data_urut_produk;

        return view('page.produksi.excel_reworks',['data'=> $data_urut_produk]);
    }
    public function title(): string
    {
        return 'Data Customer';
    }
}
