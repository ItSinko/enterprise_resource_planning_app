<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        @page {
            margin: 0px 20px 0px 13px;
            /* page-break-inside: avoid !important; */
        }
        .text-left{
            text-align: left;
        }
        .text-right{
            text-align: right;
        }
        .text-center{
            text-align: center;
        }
        header>table{
            width: 100%;
        }
        .td-width-header {
            width: 50%;
        }
        .vera {
            vertical-align: top;
        }
        .table{
            margin-top: 10px;
            width: 100%;
            border-collapse: collapse;
            border-top: 1px solid black;
            border-bottom: 1px solid black;
        }
        .table>thead>tr>th{
            border-bottom: 1px solid black;
        }
        .all-text{
            /* bold text */
            font-weight: bold;
            /* italic text */
            font-style: italic;
            /* underline text */
            text-decoration: underline;
        }
        footer>table{
            width: 100%;
        }
        /* margin top table ttd pengemudi*/
        .mt-td{
            padding-top: 60px;
        }
        /* Define styles for the footer */
        footer {
            position: fixed;
            bottom: 0;
            width: 98%;
        }
        .table-footer{
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
        }
        .table-footer>thead>tr>th{
            border: 1px solid black;
        }
        .table-footer>tbody>tr>td{
            border: 1px solid black;
        }

        main {
            margin-top: -10px;
        }

        hr {
            border: 0.5px solid black;
        }
    </style>
</head>
<body>
    <header>
        <table style="font-size: 18px;">
            <tr>
              <td>
                <b>SURAT JALAN</b>
              </td>
              <th style="text-align: right;">
                PT. Sinko Prima Alloy
              </td>
            </tr>
        </table>
        <hr>
        <table>
            <tr>
              <td class="vera" >
                  <table>
                      <tr>
                          <td class="vera" width="25%">Pengiriman :</td>
                          <td class="vera"><b><u>{{$data->tujuan_kirim}}</u></b></td>
                      </tr>
                      <tr>
                         <td></td>
                          <td>{{$data->alamat_kirim}}</td>
                      </tr>
                      <tr>
                        <td></td>
                        <td><b>UP : </b>{{$data->nama_up}}  {{$data->telp_up}}</td>
                      </tr>
                  </table>
              </td>
              <td class="vera" width="40%">
                <table style="width: 100%">
                  <tr>
                    <td class="td-width-header">Nomor SJ</td>
                    <td>:{{$data->nosurat}}</td>
                  </tr>
                  <tr>
                    <td class="td-width-header">Tanggal SJ</td>
                    <td>:  {{ \Carbon\Carbon::parse($data->tgl_kirim)->isoFormat('DD MMMM YYYY') }}</td>
                  </tr>
                  <tr>
                    <td class="td-width-header">Nomor PO</td>
                    <td>:     @if (isset($data->DetailLogistik[0]))
                            {{$data->DetailLogistik[0]->DetailPesananProduk->DetailPesanan->Pesanan->no_po}}
                          @else
                            {{ $data->DetailLogistikPart->first()->DetailPesananPart->Pesanan->no_po}}
                          @endif</td>
                  </tr>
                  <tr>
                    <td class="td-width-header">Ket. Pengiriman</td>
                    <td>:
                    @switch($data->ket)
                              @case('bayar_tujuan')
                                  <span>BAYAR TUJUAN <span>
                                  @break
                                @case('bayar_sinko')
                                    <span>BAYAR SINKO </span>
                                    @break
                              @default
                              <span>NON BAYAR<span>
                              @break
                          @endswitch
                    </td>
                  </tr>
                  <tr>
                    <td class="td-width-header">Ekspedisi</td>
                    <td>: @if ($data->nama_pengirim == '')
                            {{$data->Ekspedisi->nama}}
                            @else
                            {{$data->nama_pengirim}}
                            @endif</td>
                  </tr>
                </table>
                </tr>
              </table>
    </header>
    <main>
        <table class="table">
            <thead>
                <tr>
                    <th  class="vera align-center">No</th>
                    <th  class="vera align-center">Nama Barang</th>
                    <th  class="vera align-center">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data_produk as $key => $item)
                <tr
                @if(!isset($item['detail']))
                    style="border-bottom: 1px solid black"
                @endif
                >
                <td class="vera align-center">
                    {{ $key+1 }}
                </td>
                <td class="vera">
                    {{ $item['nama'] }}
                </td>
                <td class="vera">
                    {{  $item['jumlah'] }}.00
                </td>
                </tr>
                @if(isset($item['detail']))
                <tr style="border-bottom: 1px solid black">
                    <td></td>
                    <td class="vera" colspan="2">
                        <b>No Seri</b> : <br>
                        @foreach ($item['detail'] as $key => $detail)
                            {{ $detail['nama'] }} :

                            @foreach ($detail['seri'] as $seriDetail)
                                {{ $seriDetail['seri'] }}@if (!$loop->last),@endif
                            @endforeach
                            <br />
                        @endforeach
                    </td>
                </tr>
            @endif
                @endforeach
            </tbody>

            <tfoot class="text-center">
                @php
                $totalproduk = 0;
                $totalpart = 0;

                foreach ($data_produk as $item) {
                    if ($item['jenis'] === 'produk') {
                        foreach ($item['detail'] as $detail) {
                            $totalproduk += $detail['jumlah_noseri'];
                        }
                    } elseif ($item['jenis'] === 'sparepart') {
                        $totalpart += $item['jumlah'];
                    }
                }

                $total = $totalproduk + $totalpart;
            @endphp
            <tr>
                <td colspan="3">Total : {{ $total }}.00 Unit</td>
            </tr>
            </tfoot>
        </table>

    </main>
    <footer>
        <table>
        </tr>
          <tr>
            <td class="align-left vera" width="12%">
              <b>Keterangan : </b><br>
              {{-- {{$data->paket}}
              @if ($data->ket != null)
               - {{$data->ket}}
              @else
              <br>
              @endif --}}
            </td>
          </tr>
      </table>
      <hr>
        <table>
            <tr>
              <td class="text-center">
                Diterima Oleh,
              </td>
              <td class="text-center">
                Dibawa Oleh,
              </td>
              <td class="text-center">
              Dibuat Oleh,
              </td>
            </tr>
            <td class="text-right" colspan="2" >
               <br>
               <br>
               <br>

            <tr>
              <td class="text-center">
                <hr style="width:40%">
              </td>
              <td class="text-center">
                <hr style="width:40%">
                {{-- KURIR --}}
              </td>
              <td class="text-center">
                <hr style="width:30%">
                {{-- LOGISTIK --}}
              </td>
            </tr>
            <td class="text-right" colspan="3" >
                <br>
             <tr>
            <tr>
              <td class="text-right" colspan="3" style="font-size: 12px">
                <i>SPA-FR/GUD-04, Tanggal Terbit : 20 Maret 2020, Revisi : 02</i>
              </td>

            </tr>
        </table>
    </footer>
</body>
</html>
<script>
    $(document).ready(function() {
      window.print();
    });
    // click cancel close window
    window.onafterprint = function(){
      window.close();
    }
  </script>
