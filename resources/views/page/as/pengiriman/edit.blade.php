@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0  text-dark">Edit Pengiriman</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    @if (Auth::user()->Karyawan->divisi_id == '8')
                        <li class="breadcrumb-item"><a href="{{ route('penjualan.dashboard') }}">Beranda</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('as.pengiriman.show') }}">Pengiriman</a></li>
                        <li class="breadcrumb-item active">Edit Pengiriman</li>
                    @endif
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@stop

@section('adminlte_css')
    <style>
        #profileImage {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: #ffbb00;
            font-size: 12px;
            color: #000000;
            text-align: center;
            line-height: 60px;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .center {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
        }
        .select2>.selection>.select2-selection--single {
            height: 100% !important;
        }

        .select2>.selection>.select2-selection>.select2-selection__rendered {
            word-wrap: break-word !important;
            text-overflow: inherit !important;
            white-space: normal !important;
        }

        .hide {
            display: none !important;
        }

        .margin {
            margin: 5px;
        }

        .align-center {
            text-align: center;
        }

        .removeshadow {
            box-shadow: none;
        }

        @media screen and (min-width: 1220px) {

            body {
                font-size: 14px;
            }

            .btn {
                font-size: 14px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }

        }

        @media screen and (max-width: 1219px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: right;
            }

            .overflowy {
                max-height: 330px;
                overflow-y: scroll;
                box-shadow: none;
            }

            .cust {
                max-width: 40%;
            }
        }

        @media screen and (max-width: 991px) {
            body {
                font-size: 12px;
            }

            .btn {
                font-size: 12px;
            }

            .labelket {
                text-align: left;
            }

            .margin-md {
                margin-top: 10px;
            }

            .align-md {
                text-align: center;
            }

            .overflowy {
                max-height: 455px;
                overflow-y: scroll;
                box-shadow: none;
            }
        }
    </style>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <form method="POST" action="{{ route('as.pengiriman.update', ['id' => $id]) }}" id="formtambahretur">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-lg-4 col-md-5 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="col-12">
                                <div class="card card-outline card-warning">
                                    <div class="card-header">
                                        <h3 class="card-title">Referensi Retur</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <strong>No Retur</strong>
                                            <p class="text-muted mt-1" id="tgl_retur">{{$i->ReturPenjualan->no_retur}}</p>
                                        </div>
                                        <div class="form-group">
                                            <strong>Tanggal Retur</strong>
                                            <p class="text-muted mt-1" id="tgl_retur">{{$i->ReturPenjualan->tgl_retur}}</p>
                                        </div>
                                        <div class="form-group">
                                            <strong>Jenis Retur</strong>
                                            <p class="text-muted mt-1" id="jenis_retur">{{$i->ReturPenjualan->jenis}}</p>
                                        </div>
                                        <div class="form-group">
                                            <strong>No Referensi Penjualan</strong>
                                            <p class="text-muted mt-1" id="no_pesanan">@if($i->ReturPenjualan->pesanan_id != null) {{$i->ReturPenjualan->Pesanan->no_po}} @elseif($i->ReturPenjualan->retur_penjualan_id != null) {{$i->ReturPenjualan->ReturPenjualanChild->no_retur}} @else {{$i->ReturPenjualan->no_pesanan}} @endif</p>
                                        </div>
                                        <div class="form-group">
                                            <strong>Alasan Retur</strong>
                                            <p class="text-muted mt-1" id="alasan_retur">{{$i->ReturPenjualan->keterangan}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title" id="title_info_cust">Informasi Customer</h3>
                                        <div class="card-tools">
                                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body align-center">
                                        <div id="profileImage" class="center margin-all"></div>
                                        <div class="margin-all mt-2">
                                            <h5><b id="customer">{{$i->ReturPenjualan->Customer->nama}}</b></h5>
                                        </div>
                                        <div class="margin-all text-muted"><i class="fas fa-phone"></i> <span id="telp">{{$i->ReturPenjualan->Customer->telp}}</span></div>
                                        <div class="margin-all mt-2 text-muted">
                                            <span id="alamat">{{$i->ReturPenjualan->Customer->alamat}}</span>
                                        </div>
                                        <span id="pelanggan_id" class="hide">{{$i->ReturPenjualan->Customer->id}}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-lg-8 col-md-6 col-sm-12">
                    <div class="card" style="box-shadow:none;">


                            <div class="card-body">
                                <div class="form-horizontal">
                                    {{-- <div id="informasi_pelanggan" class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Identitas Surat Jalan</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="no_surat_jalan" class="col-lg-5 col-md-12 col-form-label labelket">No Surat Jalan</label>
                                                <div class="col-lg-3 col-md-8">
                                                    <input name="no_surat_jalan" id="no_surat_jalan" class="form-control col-form-label no_surat_jalan text-sm @error('no_surat_jalan') is-invalid @enderror" value="{{$i->no_pengiriman}}">
                                                    <div class="invalid-feedback" id="msgno_surat_jalan"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="tgl_pengiriman" class="col-lg-5 col-md-12 col-form-label labelket">Tanggal Pengiriman</label>
                                                <div class="col-lg-3 col-md-6">
                                                    <input type="date" name="tgl_pengiriman" id="tgl_pengiriman" class="form-control col-form-label tgl_pengiriman text-sm @error('tgl_pengiriman') is-invalid @enderror" value="{{$i->tanggal}}">
                                                    <div class="invalid-feedback" id="msgtgl_pengiriman"></div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="ekspedisi_id" class="col-lg-5 col-md-12 col-form-label labelket">Ekspedisi / Nama Pengirim</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <div id="input-ekspedisi_id" class="@if($i->ekspedisi == NULL) hide @endif">
                                                        <select name="ekspedisi_id" id="ekspedisi_id" class="ekspedisi_id form-control custom-select text-sm">
                                                            @if($i->ekspedisi_id != NULL)
                                                            <option value="{{$i->ekspedisi->id}}">{{$i->ekspedisi->nama}}</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <input type="text" name="non_ekspedisi" id="non_ekspedisi" class="non_ekspedisi form-control text-sm @if($i->ekspedisi != NULL) hide @endif" value="@if($i->ekspedisi == NULL) {{$i->pengirim}} @endif"/>
                                                    <div class="form-check form-check-inline mt-1">
                                                        <input class="form-check-input" type="checkbox" id="pilih_pengiriman" value="non-ekspedisi" @if($i->ekspedisi == NULL) checked @endif>
                                                        <label class="form-check-label" for="ekspedisi_id">Pengiriman Non Ekspedisi</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="biaya_kirim" class="col-lg-5 col-md-12 col-form-label labelket">Biaya Kirim</label>
                                                <div class="col-lg-3 col-md-8">
                                                    <input type="text" name="biaya_kirim" id="biaya_kirim" class="biaya_kirim form-control" value="{{$i->biaya_kirim}}"/>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div id="informasi_pelanggan" class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title">Identitas Penerima</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-group row">
                                                <label for="nama_penerima" class="col-lg-5 col-md-12 col-form-label labelket">Nama Penerima</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <input type="text" name="nama_penerima" id="nama_penerima" class="nama_penerima form-control text-sm" value="{{$i->customer_id != NULL ? $i->Customer->nama : $i->nama_penerima}}" @if($i->customer_id != NULL) readonly="true" @endif/>
                                                    <input type="text" name="customer_id" id="customer_id" class="customer_id form-control hide" value="@if($i->customer_id != NULL) {{$i->customer_id}} @endif"/>
                                                    <div class="form-check form-check-inline mt-1">
                                                        <input class="form-check-input" type="checkbox" id="pilih_penerima" value="customer" @if($i->customer_id != NULL) checked @endif>
                                                        <label class="form-check-label" for="pilih_penerima">Atur Customer sebagai Penerima</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="alamat_penerima" class="col-lg-5 col-md-12 col-form-label labelket">Alamat Penerima</label>
                                                <div class="col-lg-5 col-md-8">
                                                <textarea class="form-control text-sm" name="alamat_penerima" id="alamat_penerima" @if($i->customer_id != NULL) readonly="true" @endif>{{$i->customer_id != NULL ? $i->Customer->alamat : $i->alamat_penerima}}</textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="telp_penerima" class="col-lg-5 col-md-12 col-form-label labelket">Telepon Penerima</label>
                                                <div class="col-lg-4 col-md-8">
                                                    <input type="text" name="telp_penerima" id="telp_penerima" class="telp_penerima text-sm form-control" value="{{$i->customer_id != NULL ? $i->Customer->telp : $i->telepon_penerima}}" @if($i->customer_id != NULL) readonly="true" @endif/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="informasi_pelanggan" class="card card-outline card-warning">
                                        <div class="card-header">
                                            <h3 class="card-title" id="title_info_cust">Informasi Produk</h3>
                                            <div class="card-tools">
                                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                    <i class="fas fa-minus"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-12">
                                                    <h5>Produk</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-hover align-center" id="noseritable">
                                                            <thead class="bg-secondary">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Produk</th>
                                                                    <th>No Seri</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(isset($i->PengirimanNoseri))
                                                                @foreach ($i->PengirimanNoseri as $key => $i)
                                                                <tr>
                                                                    <td>{{$key + 1}}</td>
                                                                    <td>{{$i->NoseriBarangJadi->gudang->produk->nama}} {{$i->NoseriBarangJadi->gudang->nama}}</td>
                                                                    <td><input name="no_seri_id[{{$key}}]" id="no_seri_id{{$key}}" class="form-control no_seri_id d-none" value="{{$i->NoseriBarangJadi->id}}"/>{{$i->NoseriBarangJadi->noseri}}</td>
                                                                </tr>
                                                                @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-md-12">
                                                    <h5>Part</h5>
                                                    <div class="table-responsive">
                                                        <table class="table table-hover align-center" id="noseritable">
                                                            <thead class="bg-secondary">
                                                                <tr>
                                                                    <th>No</th>
                                                                    <th>Nama Part</th>
                                                                    <th>Jumlah</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if(isset($i->PengirimanPart))
                                                                @foreach ($i->PengirimanPart as $key => $i)
                                                                <tr>
                                                                    <td>{{$key + 1}}</td>
                                                                    <td><input name="part_id[{{$key}}]" id="part_id{{$key}}" class="form-control part_id d-none" value="{{$i->Sparepart->id}}"/>{{$i->Sparepart->nama}}</td>
                                                                    <td><input name="part_jumlah[{{$key}}]" id="part_jumlah{{$key}}" class="form-control part_jumlah d-none" value="{{$i->jumlah}}"/>{{$i->jumlah}}</td>
                                                                </tr>
                                                                @endforeach
                                                                @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <a href="{{ route('as.perbaikan.show') }}" type="button" class="btn btn-danger">Batal</a>
                                <button type="submit" class="btn btn-warning float-right" id="btnsubmit">Simpan</button>
                            </div>

                    </div>
                </div>
            </div>
        </form>

        </div>
    </section>
@endsection

@section('adminlte_js')
<script>
    $(function(){

        function identitas_pengirim(){
            if($("#pilih_pengiriman:checked").val() != undefined){
                $('#ekspedisi_id').val(null).trigger('change');
                $("#input-ekspedisi_id").addClass('hide');
                $('#non_ekspedisi').removeClass('hide');
            }else{
                $('#non_ekspedisi').val('');
                $("#input-ekspedisi_id").removeClass('hide');
                $('#non_ekspedisi').addClass('hide');
            }
        }

        function identitas_penerima(){
            $('#nama_penerima').val('');
            $('#customer_id').val('');
            $('#alamat_penerima').val('');
            $('#telp_penerima').val('');
            if($("#pilih_penerima:checked").val() != undefined){
                $('#nama_penerima').val($('#customer').text());
                $('#customer_id').val($('#pelanggan_id').text());
                $('#alamat_penerima').val($('#alamat').text());
                $('#telp_penerima').val($('#telp').text());
            }
        }

        $(document).on('change keyup', '#biaya_kirim', function(){
            var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            $(this).val(result);
        });

        $(document).on('change', '#pilih_pengiriman', function(){
            identitas_pengirim();
        });

        $(document).on('change', '#pilih_penerima', function(){
            identitas_penerima();
        });

        $('#no_retur').select2({
            placeholder: 'Pilih No Retur',
            minimumResultsForSearch: 2,
            ajax: {
                dataType: 'json',
                theme: "bootstrap",
                delay: 250,
                type: 'POST',
                url: '/api/as/retur_siap_kirim',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function(data) {

                    console.log(data);
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.no_retur,
                                jenis: obj.jenis,
                                tgl_retur: obj.tgl_retur,
                                no_pesanan: obj.no_pesanan,
                                keterangan: obj.keterangan,
                                customer: obj.customer,
                                customer_id: obj.customer_id,
                                alamat: obj.alamat,
                                telp: obj.telp,
                            };
                        })
                    };
                },
            }
        }).change(function(){
            $('#customer').html($(this).select2('data')[0]['customer'])
            $('#alamat').html($(this).select2('data')[0]['alamat'])
            $('#telp').html($(this).select2('data')[0]['telp'])

            $('#tgl_retur').html($(this).select2('data')[0]['tgl_retur'])
            $('#jenis_retur').html($(this).select2('data')[0]['jenis'])
            $('#alasan_retur').html($(this).select2('data')[0]['keterangan'])
            $('#no_pesanan').html($(this).select2('data')[0]['no_pesanan'])

            cust_image($(this).select2('data')[0]['customer']);
            identitas_penerima();

            $.ajax({
                type: "POST",
                url: '/api/as/barang_siap_kirim_retur',
                data: { 'id' : $(this).val() },
                dataType: 'json',
                success: function(data) {
                    return_noseri(data.produk);
                    return_part(data.part);
                },
                error: function() {
                    alert('Error occured');
                }
            });
        });

        $('#ekspedisi_id').select2({
            placeholder: "Pilih Ekspedisi",
            minimumResultsForSearch: 20,
            ajax: {
                dataType: 'json',
                delay: 250,
                type: 'GET',
                url: '/api/logistik/ekspedisi/select/0',
                data: function(params) {
                    return {
                        term: params.term
                    }
                },
                processResults: function(data) {
                    return {
                        results: $.map(data, function(obj) {
                            return {
                                id: obj.id,
                                text: obj.nama
                            };
                        })
                    };
                },
            }
        });

        function numberRowsNoseri($t){
            var c = 0 - 1;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.no_seri_id').attr('name', 'no_seri_id[' + j + ']');
                    $(el).find('.no_seri_id').attr('id', 'no_seri_id' + j);
                });
        }

        function numberRowsPart($t){
            var c = 0 - 1;
            $t.find("tr").each(function(ind, el) {
                $(el).find("td:eq(0)").html(++c);
                var j = c - 1;
                $(el).find('.part_id').attr('name', 'part_id[' + j + ']');
                $(el).find('.part_id').attr('id', 'part_id' + j);

                $(el).find('.part_jumlah').attr('name', 'part_jumlah[' + j + ']');
                $(el).find('.part_jumlah').attr('id', 'part_jumlah' + j);
            });
        }

        function reset_table(table){
            $('#'+table+' > tbody').empty();
            $('#'+table+' > tbody').append(`<tr>
                <td colspan="4">Data Belum Tersedia</td>
            </tr>`);
        }

        function return_noseri(noseri){
            $('#noseritable > tbody').empty();
            var data = "";
            if(noseri.length > 0){
                for(var i = 0; i < noseri.length; i++){
                    console.log(noseri[i].noseri);
                    data += `<tr>
                        <td>`+(i + 1)+`</td>
                        <td>`+noseri[i].nama_produk+`</td>
                        <td><input name="no_seri_id[`+i+`]" id="no_seri_id`+i+`" class="form-control no_seri_id d-none" value="`+noseri[i].id+`"/>`+noseri[i].noseri+`</td>
                        <td><i class="fas fa-trash text-danger" id="removenoseri"></i></td>
                    </tr>`
                }
                $('#noseritable > tbody').append(data);
            }
            else{
                reset_table('noseritable');
            }
        }

        function return_part(part){
            $('#parttable > tbody').empty();
            var data = "";
            if(part.length > 0){
                for(var i = 0; i < part.length; i++){
                    data += `<tr>
                        <td>`+(i + 1)+`</td>
                        <td><input name="part_id[`+i+`]" id="part_id`+i+`" class="form-control part_id d-none" value="`+part[i].id+`"/>`+part[i].nama_part+`</td>
                        <td><input name="part_jumlah[`+i+`]" id="part_jumlah`+i+`" class="form-control part_jumlah d-none" value="`+part[i].jumlah+`"/>`+part[i].jumlah+`</td>
                        <td><i class="fas fa-trash text-danger" id="remove_part"></i></td>
                    </tr>`
                }
                $('#parttable > tbody').append(data);
            }
            else{
                reset_table('parttable');
            }
        }

        cust_image($('#customer').text());

        function cust_image(cust_name){
            var cust = cust_name;
            var cust = cust.replace('.', '').replace('PT ', '').replace('CV ', '').replace('& ', '').replace('(',
                '').replace(')', '');
            var init = cust.split(" ");
            var initial = "";
            for (var i = 0; i < init.length; i++) {
                initial = initial + init[i].charAt(0);
            }
            var profileImage = $('#profileImage').text(initial);
        }

        $('#noseritable').on('click', '#removenoseri', function(e) {
            e.preventDefault();
            if($('#noseritable > tbody > tr').length > 1 ){
                $(this).closest('tr').remove();
                numberRowsNoseri($("#noseritable"));
            }
        });

        $('#parttable').on('click', '#remove_part', function(e) {
            e.preventDefault();
            if($('#parttable > tbody > tr').length > 1 ){
                $(this).closest('tr').remove();
                numberRowsPart($("#parttable"));
            }
        });
    })
</script>
@stop

