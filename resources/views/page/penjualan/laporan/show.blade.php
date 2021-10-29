@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Laporan</h1>
@stop

@section('adminlte_css')
<style>
    .filter {
        margin: 5px;
    }

    .hide {
        display: none !important;
    }
</style>
@stop

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-secondary">
                    <div class="card-title">Pencarian</div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-horizontal">
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Distributor</label>
                                    <div class="col-4">
                                        <select class="select2 select-info form-control customer_id" name="customer_id" id="customer_id">
                                            <option value=""></option>
                                        </select>
                                        <div class="invalid-feedback" id="msgcustomer_id">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Penjualan</label>
                                    <div class="col-5 col-form-label">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="penjualan" id="penjualan1" value="ekatalog" disabled />
                                            <label class="form-check-label" for="penjualan1">E-Catalogue</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="penjualan" id="penjualan2" value="spa" disabled />
                                            <label class="form-check-label" for="penjualan2">SPA</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="penjualan" id="penjualan3" value="spb" disabled />
                                            <label class="form-check-label" for="penjualan3">SPB</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Awal</label>
                                    <div class="col-2">
                                        <input type="date" class="form-control col-form-label @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" disabled />
                                        <div class="invalid-feedback" id="msgtanggal_mulai">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Akhir</label>
                                    <div class="col-2">
                                        <input type="date" class="form-control col-form-label @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" disabled />
                                        <div class="invalid-feedback" id="msgtanggal_akhir">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-5"></div>
                                    <div class="col-4">
                                        <span class="float-right filter"><button type="button" class="btn btn-success" id="btncetak" disabled>Cetak</button></span>
                                        <span class="float-right filter"><button type="button" class="btn btn-outline-danger" id="btnbatal">Batal</button></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('adminlte_js')
<script>
    $(function() {
        $('.customer_id').on('keyup change', function() {
            if ($(this).val() != "") {
                $('input[type="radio"][name="penjualan"]').removeAttr('disabled');
                if ($('input[type="radio"][name="penjualan"]').val() != undefined && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('input[type="radio"][name="penjualan"]').on('change', function() {
            if ($(this).val() != "") {
                $('#tanggal_mulai').removeAttr('disabled');
                if ($('.customer_id').val() != "" && $('#tanggal_mulai').val() != "" && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('#tanggal_mulai').on('keyup change', function() {
            $("#tanggal_akhir").val("");
            $("#btncetak").removeAttr('disabled');
            if ($(this).val() != "") {
                $('#tanggal_akhir').removeAttr('disabled');
                $("#tanggal_akhir").attr("min", $(this).val())
                if ($('.customer_id').val() != "" && $('input[type="radio"][name="penjualan"]').val() != undefined && $('#tanggal_akhir').val() != "") {
                    $("#btncetak").removeAttr('disabled');
                } else {

                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#tanggal_akhir").val("");
                $("#btncetak").removeAttr('disabled');
            }
        });

        $('#tanggal_akhir').on('change', function() {
            if ($(this).val() != "") {
                if ($('.customer_id').val() != "" && $('#tanggal_mulai').val() != "" && $('input[type="radio"][name="penjualan"]').val() != undefined) {
                    $("#btncetak").removeAttr('disabled');
                } else {
                    $("#btncetak").attr('disabled', true);
                }
            } else {
                $("#btncetak").attr('disabled', true);
            }
        });

        $('.customer_id').select2({
            ajax: {
                tags: [],
                dataType: 'json',
                delay: 250,
                type: 'GET',
                url: '/api/customer/select/',
                processResults: function(data) {
                    console.log(data);
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
        }).change(function() {
            var id = $(this).val();
            $.ajax({
                url: '/api/customer/select/' + id,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log(data);
                    $('#alamat').val(data[0].alamat);
                    $('#telepon').val(data[0].telp);
                }
            });
        });

        $("#btnbatal").on('click', function() {
            $("#btncetak").attr('disabled', true);
            $('.customer_id').prop('selectedIndex', -1)
            $('input[type="radio"][name="penjualan"]').prop('checked', false);
            $('input[type="radio"][name="penjualan"]').attr('disabled', true);
            $('#tanggal_mulai').val('');
            $('#tanggal_mulai').attr('disabled', true);
            $('#tanggal_akhir').val('');
            $('#tanggal_akhir').attr('disabled', true);
        });
    });
</script>
@endsection