<html>
    <head>
        <style>

            /** Define the margins of your page **/
            @page {
                margin: 100px 25px;
                /* page-break-inside: avoid !important; */
            }


            main {
                position: relative;
                top: 140px;
                width: 100%;
                padding-bottom: 300px;
                font-size: 14px;
            }

            header {
                position: fixed;
                top: -90px;
                left: 0px;
                right: 0px;
                height: 250px;
                margin-bottom: 100px;
                /** Extra personal styles **/
                background-color: #ffffff;
                color: rgb(0, 0, 0);
                line-height: 20px;
                font-size: 14px;
            }

            footer {

                position: fixed;
                bottom: -10px;
                left: 0px;
                right: 0px;
                height: 50px;
                top: 690px;
                /** Extra personal styles **/
                background-color: #ffffff;
                color: rgb(0, 0, 0);
                line-height: 20px;
            }
    .vera {
        vertical-align: top;
    }

    .align-left {
        text-align: left;
    }

    .align-right {
        text-align: right;
    }
    .align-center {
        text-align: center;
    }

    .page-break {
            page-break-after: always;
        }

    .td-width-header {
      width: 35%;
    }

    main>table{
      page-break-inside: avoid !important;
      border-top: 1px solid #000000;
      border-bottom: 1px solid #000000
    }
    table{
      table-layout: fixed; width: 100%; border-collapse: collapse; 
    }
        </style>
    </head>
    <body>
        <!-- Define header and footer blocks before your content -->
        <header>
            <table>
                <tr>
                  <td>
                    <b>SURAT JALAN</b>
                  </td>
                  <th style="text-align: right;">
                    PT. Sinko Prima Alloy
                  </td>
                </tr>
            </table>
            <table>
                <tr>
                  <td class="vera" >
                      <table>
                          <tr>
                              <td class="vera"  width="20%">Pelanggan :</td>
                              <td><b>{{$data->customer}}</b></td>
                          </tr>
                          <tr>
                              <td></td>
                              <td>{{$data->alamat_customer}}</td>
                          </tr>
                      </table>
                      <br>
                      <table>
                          <tr>
                              <td class="vera" width="20%">Pengiriman :</td>
                              <td class="vera"><b><u>{{$data->tujuan_kirim}}</u></b></td>
                          </tr>
                          <tr>
                             <td></td>
                              <td>{{$data->alamat_kirim}}</td>
                          </tr>
                          <tr>
                            <td></td>
                            <td><b>UP : </b>{{$data->up}}</td>
                          </tr>
                      </table>
                  </td>
                  <td width="1%"></td>
                  <td class="vera" width="35%">
                    <table style="width: 100%">
                      <tr>
                        <td class="td-width-header">Nomor SJ</td>
                        <td>: {{$data->nosj}}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Tanggal SJ</td>
                        {{-- {{ \Carbon\Carbon::now()->isoFormat('DD MMMM YYYY') }} --}}
                        <td>: {{ \Carbon\Carbon::parse($data->tgl_sj)->isoFormat('DD MMMM YYYY') }}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Nomor PO</td>
                        <td>: {{$data->no_po}}</td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Keterangan Pengiriman</td>
                        <td>:
                          @switch($data->ket)
                              @case('bayar_tujuan')
                                  <span>BAYAR TUJUAN <span>
                                  @break
                                @case('bayar_sinko')
                                    <span>BAYAR SINKO </span>
                              @default
                              <span>NON BAYAR<span>
                          @endswitch
                        </td>
                      </tr>
                      <tr>
                        <td class="td-width-header">Ekspedisi</td>
                        <td>: {{$data->ekspedisi}}</td>
                      </tr>
                    </table>
                    </tr>
                  </table>
        </header>

        <footer>
            <table>
              </tr>
                <tr>
                  <td class="align-left vera" width="12%">
                    <b>Keterangan : </b><br>
                    {{$data->paket}}
                    @if ($data->ket != null)
                     - {{$data->ket}}
                    @else
                    <br>
                    @endif
                  </td>
                </tr>
            </table>
            <hr>
            <table>
                <tr>
                  <td class="align-center">
                    Diterima Oleh,
                  </td>
                  <td class="align-center">
                    Dibawa Oleh,
                  </td>
                  <td class="align-center">
                  Dibuat Oleh,
                  </td>
                </tr>
                <td class="align-right" colspan="2" >
                   <br>
                   <br>
                   <br>

                <tr>
                  <td class="align-center">
                    <hr style="width:30%">

                  </td>
                  <td class="align-center">
                    <hr style="width:40%">
                    {{-- KURIR --}}
                  </td>
                  <td class="align-center">
                    <hr style="width:30%">
                    {{-- LOGISTIK --}}
                  </td>
                </tr>
                <td class="align-right" colspan="3" >
                    <br>
                 <tr>
                <tr>
                  <td class="align-right" colspan="3" style="font-size: 12px">
                    <i>SPA-FR/GUD-04, Tanggal Terbit : 20 Maret 2020, Revisi : 02</i>
                  </td>

                </tr>
            </table>
        </footer>

        <!-- Wrap the content of your PDF inside a main tag -->
        <main>
            {{-- Hal -1 --}}
                <table>
                    <thead>
                       <tr>
                         <td class="vera align-center" width="8%" style="border-bottom: 1px solid black">
                           <b>No</b>
                         </td>
                         <td class="vera align-center" width="20%" style="border-bottom: 1px solid black">
                           <b>Kode Barang</b>
                         </td>
                         <td class="vera align-center" style="border-bottom: 1px solid black">
                           <b>Nama Barang</b>
                         </td>
                         <td class="vera"  width="8%" style="border-bottom: 1px solid black">
                           <b>Jumlah</b>
                         </td>
                         <td class="vera"  width="8%" style="border-bottom: 1px solid black">
                           <b>Satuan</b>
                         </td>
                       </tr>
                    </thead>
                    <tbody style="page-break-after: avoid !important;">
                        @foreach ( $data->item as $key => $item)
                        <tr>
                            <td class="vera align-center">
                                {{ $key+1 }}
                            </td>
                            <td class="vera align-center">
                              @if($item->kode == null)
                                -
                              @else
                                {{$item->kode}}
                              @endif
                            </td>
                            <td class="vera">
                                {{$item->nama}}
                            </td>
                            <td class="vera">
                                @if(isset($item->noseri))
                                {{$item->jumlah_noseri}}.00
                                @else
                                {{$item->jumlah}}.00
                                @endif
                            </td>
                              <td class="vera">
                                {{ $item->satuan}}
                              </td>
                          </tr>
                          @if(isset($item->noseri)){
                          <tr>
                            <td class="vera" colspan="5">
                                <b>No Seri</b> : <br>
                              @php echo implode(', ',$item->noseri) @endphp
                             </td>
                          </tr>
                          }
                          @endif


                          @endforeach

                        {{-- <tr>
                            <td class="align-center" colspan="3">

                            </td>
                            <td class="align-center" colspan="3">
                              <b>Total 1 Coly</b>
                            </td>


                          </tr> --}}
                    </tbody>
                </table>
                @if($data->dimensi != "")
                  <div style="margin: 10px 0px;">
                    <b>Dimensi</b>
                  <br>
                  {{ $data->dimensi}}
                @endif
                @if($data->ekspedisi_terusan != "")
                  </div
                  @if($data->dimensi == "")
                    style="margin: 10px 0px;"
                  @endif
                  >
                  <b>Ekspedisi Terusan : </b><br>
                  {{ $data->ekspedisi_terusan}}
                  <br>
                  </div>
                @endif


                {{-- <div style="page-break-after: always;"></div> --}}
                {{-- @if($hal == $i)
                <div style="page-break-after: never;"> </div>
                @else
                <div style="page-break-after: always;"></div>
                @endif
                @endfor --}}

                {{-- <div style="page-break-after: always;"></div> --}}
            {{-- Hal -2 --}}

                {{-- <div style="page-break-after: never;"> </div> --}}
        </main>
    </body>
</html>
