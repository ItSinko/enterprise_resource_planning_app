<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\PackRw;
use App\Models\PackRwHead;
use App\Models\SeriDetailRw;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class ExportPackWilayah implements   WithTitle,FromView,ShouldAutoSize,WithColumnFormatting, WithStyles
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
    public function view(): View
    {
        $id = $this->urutan;
        $data = PackRw::select('pack_rw.noseri','seri_detail_rw.packer','seri_detail_rw.created_at','seri_detail_rw.isi')
        ->leftjoin('seri_detail_rw', 'seri_detail_rw.noseri_id', '=', 'pack_rw.noseri_id')
        ->where('pack_rw_head_id',$id)->get();

        $wilayah = PackRwHead::find($id);

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

        return view('page.logistik.pack.export_wilayah',['data'=> $data_urut_produk, 'wilayah' => $wilayah]);
    }
    public function title(): string
    {
        return 'Data Export Reworks';
    }

    public function styles(Worksheet $sheet)
    {   
        // create center alignment A1:I1
        $sheet->getStyle('a1:i1')->getAlignment()->setHorizontal('center');
        // create bold
        $sheet->getStyle('a1:i1')->getFont()->setBold(true);
    }
}
