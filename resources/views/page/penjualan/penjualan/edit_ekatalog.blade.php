@extends('adminlte.page')

@section('title', 'ERP')

@section('content')
    <div id="app">
        <index></index>
    </div>

@section('adminlte_js')
<script>
    $(function() {
            $('#jenis_paket').select2();
            var nopaketdb = "{{ str_replace( array('AK1-', 'FKS-'), '', $e->no_paket) }}";
            var nopaketubah = false;
            var status_akn = '{{$e->status}}';
            var jum_produk = '{{count($e->Pesanan->DetailPesanan)}}';
            $(".os-content-arrange").remove();
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
            var yyyy = today.getFullYear();

            today = yyyy + '-' + mm + '-' + dd;
            // var instansi_array = [];


            $("#tgl_edit").attr("max", today);
            $("#batas_kontrak").attr("min", today);

            loop();
            load_variasi();

            var penjualan_produk_id = false;
            var variasi = false;
            var produk_jumlah = false;
            var produk_harga = false;

            function checkvalidasi(){

                $('#produktable').find('.penjualan_produk_id').each(function() {
                    if ($(this).val() != "") {
                        penjualan_produk_id = true;
                    }
                    else {
                        penjualan_produk_id = false;
                        return false;
                    }
                });

                $('#produktable').find('.variasi').each(function() {
                    if ($(this).val() != "") {
                        variasi = true;
                    }
                    else {
                        variasi = false;
                        return false;
                    }
                });

                $('#produktable').find('.produk_jumlah').each(function() {
                    if ($(this).val() != "") {
                        produk_jumlah = true;
                    }
                    else {
                        produk_jumlah = false;
                        return false;
                    }
                });

                $('#produktable').find('.produk_harga').each(function() {
                    if ($(this).val() != "") {
                        produk_harga = true;
                    }
                    else {
                        produk_harga = false;
                        return false;
                    }
                });

                if($('input[type="radio"][name="status_akn"]:checked').val() == "sepakat" || $('input[type="radio"][name="status_akn"]:checked').val() == "negosiasi"){
                    if(penjualan_produk_id == true && variasi == true && produk_jumlah == true && produk_harga == true){
                        if($('#no_paket').val() != "" && $('#provinsi').val() != null && $('#tgl_buat').val() != "" && $('#tgl_edit').val() != "" && $('#no_urut').val() != "" && $('#instansi').val() != "" && $('#satuan_kerja').val() != "" && $('#alamatinstansi').val() != "" && $('#deskripsi').val() != ""){
                            $("#btnsimpan").attr('disabled', false);
                        } else {
                            $("#btnsimpan").attr('disabled', true);
                        }
                    }
                    else{
                        $("#btnsimpan").attr('disabled', true);
                    }
                }
                else if($('input[type="radio"][name="status_akn"]:checked').val() == "batal" || $('input[type="radio"][name="status_akn"]:checked').val() == "draft"){
                    if ($('input[type="checkbox"][name="isi_produk"]:checked').length > 0) {
                        if(penjualan_produk_id == true && variasi == true && produk_jumlah == true && produk_harga == true){
                            if($('#tgl_buat').val() != ""  && $('#no_urut').val() != "" && $('#instansi').val() != "" && $('#satuan_kerja').val() != "" && $('#alamatinstansi').val() != "" && $('#deskripsi').val() != ""){
                                $("#btnsimpan").attr('disabled', false);
                            } else {
                                $("#btnsimpan").attr('disabled', true);
                            }
                        }
                        else{
                            $("#btnsimpan").attr('disabled', true);
                        }
                    } else {
                        if($('#tgl_buat').val() != ""  && $('#no_urut').val() != "" && $('#instansi').val() != "" && $('#satuan_kerja').val() != "" && $('#alamatinstansi').val() != "" && $('#deskripsi').val() != ""){
                            $("#btnsimpan").attr('disabled', false);
                        } else {
                            $("#btnsimpan").attr('disabled', true);
                        }
                    }
                }

            }

            // function getinstansi(id){
            //     instansi_array = [];
            //     $.ajax({
            //         url: '/api/customer/get_instansi/' + id+'/'+yyyy,
            //         type: 'POST',
            //         dataType: 'json',
            //         async: false,
            //         success: function(data) {
            //             $.each(data, function( i, val ) {
            //                 instansi_array.push(val);
            //             });
            //         }
            //     });
            // }

            function perencanaan(customer_id, instansi){
                $('#perencanaantable').DataTable({
                    searching: false,
                    destroy: true,
                    processing: true,
                    serverSide: true,
                    ajax: {
                        'url': '/api/penjualan/rencana/produk/'+customer_id+'/'+instansi+'/'+yyyy,
                        'dataType': 'json',
                        'type': 'POST',
                        'headers': {
                            'X-CSRF-TOKEN': '{{csrf_token()}}'
                        }
                    },
                    language: {
                        processing: '<i class="fa fa-spinner fa-spin"></i> Tunggu Sebentar'
                    },
                    columns: [{
                            data: 'DT_RowIndex',
                            className: 'align-center nowrap-text',
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'nama_produk',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'qty',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'realisasi',
                            className: 'align-center nowrap-text'
                        },
                        {
                            data: 'harga',
                            render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp. ' ),
                            className: 'align-right nowrap-text'
                        },
                        {
                            data: 'aksi',
                            className: 'align-center nowrap-text'
                        },
                    ]
                });
            }

            // getinstansi("{{$e->customer_id}}");
            perencanaan("{{$e->customer_id}}", "{{$e->instansi}}");

            function loop() {
                for (i = 0; i < 20; i++) {
                    select_data(i);
                }
            }

            function cek_stok(id) {
                var jumlah = 0;
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    async: false,
                    url: '/api/produk/variasi_stok/' + id,
                    success: function(data) {
                        jumlah = data;
                    },
                    error: function(data) {
                        jumlah = data;
                    }
                });
                return jumlah;
            }

            $('#customer_id').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Customer",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/customer/select',
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
            }).change(function() {
                var id = $(this).val();
                $.ajax({
                    url: '/api/customer/select/' + id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#alamat_customer').val(data[0].alamat);
                        $('#telepon_customer').val(data[0].telp);
                    }
                });

                // getinstansi(id);
                perencanaan(id, document.getElementById("instansi").value);
            });

            $('.provinsi').select2({
                ajax: {
                    minimumResultsForSearch: 20,
                    placeholder: "Pilih Produk",
                    dataType: 'json',
                    theme: "bootstrap",
                    delay: 250,
                    type: 'GET',
                    url: '/api/provinsi/select',
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
            })

            if ('{{$e->customer_id}}' == 484) {
                var cust_id = 'belum';
                $("#customer_id").attr('disabled', true);
                $("#customer_id").empty().trigger('change');
                $("#provinsi").attr('disabled', true);
                $("#provinsi").empty().trigger('change');
                $("#alamat").val("");
                $("#telepon").val("");
            } else {
                var cust_id = 'sudah';
            }

            if (status_akn != 'sepakat') {
                if (status_akn == 'draft') {

                    if(jum_produk <= 0){
                        $("#dataproduk").addClass("hide");
                    }
                    $("#provinsi").attr('disabled', true);
                    $("#provinsi").empty().trigger('change')
                    $("#batas_kontrak").attr('disabled', true);
                }
            }

            $('input[type="radio"][name="namadistributor"]').on('change', function() {
                if ($(this).val() != "") {
                    if ($(this).val() == "sudah") {
                        $("#customer_id").attr('disabled', false);
                    } else {
                        $("#customer_id").attr('disabled', true);
                        $("#customer_id").empty().trigger('change')
                        $("#alamat").val("");
                        $("#telepon").val("");
                    }
                } else {
                    $("#msgstatus").text("Status Harus dipilih");
                    $("#status").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
                checkvalidasi();
            });

            $('input[name="status_akn"][value={{$e->status}}]').attr('checked', 'checked');
            $('input[name="namadistributor"][value=' + cust_id + ']').attr('checked', 'checked');
            $('#customer_id').on('keyup change', function() {
                if ($(this).val() != "") {
                    $('#msgcustomer_id').text("");
                    $('#customer_id').removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $('#msgcustomer_id').text("Silahkan Pilih Customer");
                    $('#customer_id').addClass('is-invalid');
                }
            });

            $('input[type="radio"][name="status_akn"]').on('change', function() {
                 $('#isi_produk_input').addClass('hide');

                if ($(this).val() != "") {
                    if ($(this).val() == "sepakat") {
                        $('#checkbox_nopaket').addClass('hide');
                        $('#isi_nopaket').prop("checked", false);
                        $('#no_paket').attr('readonly', false);
                        $("#dataproduk").removeClass("hide");
                        $("#batas_kontrak").attr('disabled', false);
                        $("#provinsi").attr('disabled', false);
                        var $newOption = $("<option selected='selected'></option>").val("11").text("Jawa Timur")
                    $(".provinsi").append($newOption).trigger('change');
                        if(nopaketubah == false){
                            $('#no_paket').val(nopaketdb);
                        }
                        if(jum_produk <= 0){
                        $("#produktable tbody").empty();
                         $('#produktable tbody').append(trproduktable());
                        }
                        numberRowsProduk($("#produktable"));
                    } else if ($(this).val() == "draft") {
                        $('#isi_produk_input').removeClass('hide');
                        $('#checkbox_nopaket').removeClass('hide');
                        $('#no_paket').val("");
                        $('#no_paket').attr('readonly', true);
                         $("#batas_kontrak").attr('disabled', true);
                        $("#provinsi").attr('disabled', true);
                        $("#provinsi").empty().trigger('change')
                        if(jum_produk <= 0){
                            $('input[type="checkbox"][name="isi_produk"]').attr('checked', false);
                            $("#produktable tbody").empty();
                            $('#produktable tbody').append(trproduktable());
                            $("#totalhargaprd").text("Rp. 0");
                            $("#dataproduk").addClass("hide");
                            $('#isi_nopaket').prop("checked", false);
                        }
                        if($('input[type="checkbox"][name="isi_produk"]:checked').length <= 0){
                            $("#dataproduk").addClass("hide");
                        }
                    } else if($(this).val() == "batal"){
                        if(status_akn != "draft"){
                            $('#checkbox_nopaket').addClass('hide');
                            $('#isi_nopaket').prop("checked", false);
                            $('#no_paket').attr('readonly', false);
                            numberRowsProduk($("#produktable"));
                            $("#batas_kontrak").val("");
                            $("#batas_kontrak").attr('disabled', true);
                            $("#dataproduk").removeClass("hide");
                            $("#provinsi").attr('disabled', true);
                            $("#provinsi").empty().trigger('change')
                            if(nopaketubah == false){
                                $('#no_paket').val(nopaketdb);
                            }

                        }
                        else{
                            $('#checkbox_nopaket').removeClass('hide');
                            $('#isi_nopaket').prop("checked", false);
                            $('#no_paket').val("");
                            $('#no_paket').attr('readonly', true);
                            $("#batas_kontrak").attr('disabled', true);
                            $("#provinsi").attr('disabled', true);
                            $("#provinsi").empty().trigger('change');
                            if(jum_produk <= 0){
                                $('#dataproduk').addClass('hide');
                                $("#produktable tbody").empty();
                                $('#produktable tbody').append(trproduktable());
                                $("#totalhargaprd").text("Rp. 0");
                                numberRowsProduk($("#produktable"));
                            }
                        }
                    } else {
                        $('#checkbox_nopaket').addClass('hide');
                        $('#isi_nopaket').prop("checked", false);
                        $('#no_paket').attr('readonly', false);
                        $("#batas_kontrak").val("");
                        $("#batas_kontrak").attr('disabled', true);
                        $("#dataproduk").removeClass("hide");
                        $("#provinsi").attr('disabled', true);
                        $("#provinsi").empty().trigger('change')
                        if(nopaketubah == false){
                            $('#no_paket').val(nopaketdb);
                        }
                        if(jum_produk <= 0){
                            $("#produktable tbody").empty();
                            $('#produktable tbody').append(trproduktable());
                        }
                        numberRowsProduk($("#produktable"));
                    }
                } else {
                    $('#checkbox_nopaket').addClass('hide');
                        $('#isi_nopaket').prop("checked", false);
                        $('#no_paket').attr('readonly', false);
                    $("#msgstatus").text("Status Harus dipilih");
                    $("#status").addClass('is-invalid');
                    if(nopaketubah == false){
                        $('#no_paket').val(nopaketdb);
                    }
                }
                checkvalidasi();
            });


            $('input[type="checkbox"][name="isi_nopaket"]').change(function() {
                if ($('input[type="checkbox"][name="isi_nopaket"]:checked').length > 0) {
                    $('#no_paket').attr('readonly', false);
                    if(nopaketubah == false){
                            $('#no_paket').val(nopaketdb);
                        }
                }else{
                    $('#no_paket').attr('readonly', true);
                    $('#no_paket').val("");
                }
            })
            $('input[type="checkbox"][name="isi_produk"]').change(function() {
                $("#produktable tbody").empty();
                $('#produktable tbody').append(trproduktable());
                numberRowsProduk($("#produktable"));
                $("#totalhargaprd").text("Rp. 0");

                if ($('input[type="checkbox"][name="isi_produk"]:checked').length > 0) {
                    $("#dataproduk").removeClass("hide");
                } else {
                    $("#dataproduk").addClass("hide");
                }
                checkvalidasi();
            });
            $(document).on('keyup', '#no_paket', function(){
                nopaketubah = true;
            })


            $('#tanggal_pemesanan').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgtanggal_pemesanan").text("");
                    $("#tanggal_pemesanan").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgtanggal_pemesanan").text("Isi Tanggal Pemesanan");
                    $("#tanggal_pemesanan").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('input[type="radio"][name="do_akn"]').on('change', function() {
                if ($(this).val() == "yes") {
                    $("#do_detail_no_akn").removeClass("hide");
                    $("#do_detail_tgl_akn").removeClass("hide");
                } else if ($(this).val() == "no") {
                    $("#do_detail_no_akn").addClass("hide");
                    $("#do_detail_tgl_akn").addClass("hide");
                }
                checkvalidasi();
            });

            $('#batas_kontrak').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgbatas_kontrak").text("");
                    $("#batas_kontrak").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgbatas_kontrak").text("Batas Kontrak Harus diisi");
                    $("#batas_kontrak").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#tgl_edit').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgtgl_edit").text("");
                    $("#tgl_edit").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgtgl_edit").text("Tanggal Edit Harus diisi");
                    $("#tgl_edit").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#pills-produk-tab').on('click', function(){
                var cust = $('#customer_id').val();
                var instansi = $('#instansi').val();
                perencanaan(cust, instansi);
                checkvalidasi();
            });

            $('input[name="instansi"]').on('keyup change', function() {
                if ($(this).val() != "") {
                    var cust = $('.customer_id').val();
                    $("#msginstansi").text("");
                    $("#instansi").removeClass('is-invalid');
                    perencanaan(cust, $(this).val());
                } else if ($(this).val() == "") {
                    $("#msginstansi").text("Instansi Harus diisi");
                    $("#instansi").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#deskripsi').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgdeskripsi").text("");
                    $("#deskripsi").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgdeskripsi").text("Deskripsi harus diisi");
                    $("#deskripsi").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#no_urut').on('keyup change', function() {
                if ($(this).val() != "") {
                    var values = $(this).val();
                    $.ajax({
                        type: 'POST',
                        dataType: 'JSON',
                        url: '/api/penjualan/check_no_urut/' + '{{$e->id}}'+'/' + values,
                        success: function(data) {
                            if (data > 0) {
                                $("#msgno_urut").text("No Urut tidak boleh sama");
                                $("#no_urut").addClass('is-invalid');
                                $('#btntambah').attr("disabled", true);
                            } else {
                                $("#msgno_urut").text("");
                                $("#no_urut").removeClass('is-invalid');
                            }
                        },
                        error: function(data) {
                            $("#msgno_urut").text("No Urut tidak boleh sama");
                            $("#no_urut").addClass('is-invalid');
                            $('#btntambah').attr("disabled", true);
                        }
                    });
                } else if ($(this).val() == "") {
                    $("#msgno_urut").text("No Urut Harus diisi");
                    $("#no_urut").addClass('is-invalid');
                    $('#btntambah').attr("disabled", true);
                }
                checkvalidasi();
            });

            $('#no_po_akn').on('keyup change', function() {
                if ($(this).val() != "") {
                    $("#msgno_po_akn").text("");
                    $("#no_po_akn").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgno_po_akn").text("Nomor PO Harus diisi");
                    $("#no_po_akn").addClass('is-invalid');
                }
                checkvalidasi();
            });

            $('#tanggal_po_akn').on('keyup', function() {
                if ($(this).val() != "") {
                    $("#msgtanggal_po_akn").text("");
                    $("#tanggal_po_akn").removeClass('is-invalid');
                } else if ($(this).val() == "") {
                    $("#msgtanggal_po_akn").text("Tanggal PO Harus diisi");
                    $("#tanggal_po_akn").addClass('is-invalid');
                }
                checkvalidasi();
            });

            function formatmoney(bilangan) {
                var number_string = bilangan.toString(),
                    sisa = number_string.length % 3,
                    rupiah = number_string.substr(0, sisa),
                    ribuan = number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                    separator = sisa ? '.' : '';
                    rupiah += separator + ribuan.join('.');
                }
                return rupiah;
            }

            function replaceAll(string, search, replace) {
                return string.split(search).join(replace);
            }

            function totalhargaprd() {
                var totalharga = 0;
                $('#produktable').find('tr .produk_subtotal').each(function() {
                    var subtotal = replaceAll($(this).val(), '.', '');
                    totalharga = parseInt(totalharga) + parseInt(subtotal);
                    $("#totalhargaprd").text("Rp. " + totalharga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                })
            }

            function numberRowsProduk($t) {
                var c = 0 - 2;
                $t.find("tr").each(function(ind, el) {
                    $(el).find("td:eq(0)").html(++c);
                    var j = c - 1;
                    $(el).find('.penjualan_produk_id').attr('name', 'penjualan_produk_id[' + j + ']');
                    $(el).find('.penjualan_produk_id').attr('id', j);
                    var variasi = $(el).find('.variasi');
                    for (var k = 0; k < variasi.length; k++) {
                        $(el).find('select[name="variasi[' + j + '][' + k + ']"').select2();
                        $(el).find('select[data-attr="variasi' + k + '"]').attr('name', 'variasi[' + j + '][' + k + ']');
                        $(el).find('select[data-attr="variasi' + k + '"]').attr('id', 'variasi' + j + '' + k);
                        $(el).find('span[data-attr="ketstok' + k + '"]').attr('name', 'ketstok[' + j + '][' + k + ']');
                        $(el).find('span[data-attr="ketstok' + k + '"]').attr('id', 'ketstok' + j + '' + k);
                    }
                    $(el).find('.detail_produk').attr('id', 'detail_produk' + j);
                    $(el).find('.produk_harga').attr('id', 'produk_harga' + j);
                    $(el).find('.produk_harga').attr('name', 'produk_harga[' + j + ']');
                    $(el).find('.produk_ongkir').attr('id', 'produk_ongkir' + j);
                    $(el).find('.produk_ongkir').attr('name', 'produk_ongkir[' + j + ']');
                    $(el).find('.produk_jumlah').attr('id', 'produk_jumlah' + j);
                    $(el).find('.produk_jumlah').attr('name', 'produk_jumlah[' + j + ']');
                    $(el).find('.produk_subtotal').attr('id', 'produk_subtotal' + j);
                    $(el).find('.produk_subtotal').attr('name', 'produk_subtotal[' + j + ']');
                    $(el).find('.rencana_id').attr('id', 'rencana_id' + j);
                    $(el).find('.rencana_id').attr('name', 'rencana_id[' + j + ']');
                    $(el).find('.detail_jual').attr('id', 'detail_jual' + j);
                    select_data($(el).find('.penjualan_produk_id').attr('id'));
                });
            }

            $("#produktable").on('keyup change', '.penjualan_produk_id', function() {
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                $(this).closest('tr').find('.rencana_id').val("");
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                }
                checkvalidasi();
            });

            $("#produktable").on('keyup change', '.produk_jumlah', function() {
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                var ketstok = $(this).closest('tr').find('.ketstok');
                var variasi = $(this).closest('tr').find('.variasi');
                var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    if (ongkircvrt == "") {
                        ongkircvrt = "0";
                        $(this).closest('tr').find('.produk_ongkir').val("0");
                    }
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                    for (var i = 0; i < variasi.length; i++) {
                        var variasires = $('select[name="variasi[' + ppid + '][' + i + ']"]').select2('data')[0];
                        var kebutuhan = jumlah * variasires.jumlah;
                        if (cek_stok(variasires.id) < kebutuhan) {
                            var jumlah_kekurangan = 0;
                            if (cek_stok(variasires.id) < 0) {
                                jumlah_kekurangan = kebutuhan;
                            } else {
                                jumlah_kekurangan = Math.abs(cek_stok(variasires.id) - kebutuhan);
                            }
                            $('select[name="variasi[' + ppid + '][' + i + ']"]').addClass('is-invalid');
                            $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('Jumlah Kurang ' + jumlah_kekurangan + ' dari Permintaan');
                        } else if (cek_stok(variasires.id) >= kebutuhan) {
                            $('select[name="variasi[' + ppid + '][' + i + ']"]').removeClass('is-invalid');
                            $('span[name="ketstok[' + ppid + '][' + i + ']"]').text('');
                        }
                    }
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                    variasi.removeClass('is-invalid');
                    ketstok.text('');
                }

                checkvalidasi();
            });

            $('#produktable').on('keyup change', '.variasi', function() {
                $(this).val();
                var name = $(this).attr('name');
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var ppid = $(this).closest('tr').find('.penjualan_produk_id').attr('id');
                val = $('select[name="' + name + '"]').val();
                id = $('select[name="' + name + '"]').attr('data-id');
                vals = $('select[name="' + name + '"]').select2('data')[0];
                var kebutuhan = jumlah * vals.jumlah;
                if (cek_stok(vals.id) < kebutuhan) {
                    var jumlah_kekurangan = 0;
                    if (cek_stok(vals.id) < 0) {
                        jumlah_kekurangan = kebutuhan;
                    } else {
                        jumlah_kekurangan = Math.abs(cek_stok(vals.id) - kebutuhan);
                    }
                    $('select[name="variasi[' + ppid + '][' + id + ']"]').addClass('is-invalid');
                    $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('Jumlah Kurang ' + jumlah_kekurangan + ' dari Permintaan');
                } else if (cek_stok(vals.id) >= kebutuhan) {
                    $('select[name="variasi[' + ppid + '][' + id + ']"]').removeClass('is-invalid');
                    $('span[name="ketstok[' + ppid + '][' + id + ']"]').text('');
                }
                checkvalidasi();
            });

            $("#produktable").on('keyup change', '.produk_harga', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    if (ongkircvrt == "") {
                        ongkircvrt = "0";
                        $(this).closest('tr').find('.produk_ongkir').val("0");
                    }
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                }

                checkvalidasi();
            });

            $("#produktable").on('keyup change', '.produk_ongkir', function() {
                var result = $(this).val().replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                $(this).val(result);
                var jumlah = $(this).closest('tr').find('.produk_jumlah').val();
                var harga = $(this).closest('tr').find('.produk_harga').val();
                var ongkir = $(this).closest('tr').find('.produk_ongkir').val();
                var subtotal = $(this).closest('tr').find('.produk_subtotal');
                if (jumlah != "" && harga != "") {
                    var hargacvrt = replaceAll(harga, '.', '');
                    var ongkircvrt = replaceAll(ongkir, '.', '');
                    subtotal.val(formatmoney((jumlah * parseInt(hargacvrt)) + parseInt(ongkircvrt)));
                    totalhargaprd();
                } else {
                    subtotal.val(formatmoney("0"));
                    totalhargaprd();
                }
                checkvalidasi();
            });

            function trproduktable() {
                var data = `<tr>
                    <td></td>
                    <td>
                        <div class="form-group select_item">
                            <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                <option value=""></option>
                            </select>
                            <div class="detailjual" id="tes0">
                            </div>
                        </div>
                        <div id="detail_produk" class="detail_produk"></div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <div class="input-group">
                                <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah" style="width:100%;">
                            </div>
                            <small id="produk_ketersediaan"></small>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" placeholder="Masukkan Harga" style="width:100%;"/>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Harga" style="width:100%;"/>
                        </div>
                    </td>
                    <td>
                        <div class="form-group d-flex justify-content-center">
                            <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" style="width:100%;" readonly/>
                        </div>
                    </td>
                    <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly></td>
                    <td>
                        <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                    </td>
                </tr>`;
                return data;
            }

            $('#addrowproduk').on('click', function() {
                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                } else {
                    $('#produktable tbody tr:last').after(trproduktable());
                    numberRowsProduk($("#produktable"));
                }
                checkvalidasi();
            });

            $('#produktable').on('click', '#removerowproduk', function(e) {
                $(this).closest('tr').remove();
                numberRowsProduk($("#produktable"));
                totalhargaprd();
                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(trproduktable());
                    numberRowsProduk($("#produktable"));
                    $("#totalhargaprd").text("Rp. 0");
                }

                checkvalidasi();
            });

            function select_data(i) {
                $('#' + i).select2({
                    placeholder: "Pilih Produk",
                    ajax: {
                        minimumResultsForSearch: 20,
                        dataType: 'json',

                        delay: 250,
                        type: 'GET',
                        url: '/api/penjualan_produk/select_param/ekatalog',
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
                }).change(function(i) {
                    var index = $(this).attr('id');
                    var id = $(this).val();
                    $.ajax({
                        url: '/api/penjualan_produk/select/' + id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(res) {
                            $('#produk_harga' + index).val(formatmoney(res[0].harga));
                            $('#produk_subtotal' + index).val(formatmoney(res[0].harga * $('#produk_jumlah' + index).val()));
                            $('#rencana_id' + index).val("");
                            totalhargaprd();
                            var tes = $('#detail_produk' + index);
                            tes.empty();
                            var datas = "";
                            tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                            for (var x = 0; x < res[0].produk.length; x++) {
                                var data = [];
                                tes.append(`<div>`);
                                tes.append(`<div class="card-body blue-bg">
                                            <h6>` + res[0].produk[x].nama + `</h6>
                                            <select class="form-control variasi" name="variasi[` + index + `][` + x + `]" id="variasi` + index + `` + x + `" style="width:100%;" data-attr="variasi` + x + `" data-id="` + x + `"></select>
                                            <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` + x + `]" id="ketstok` + index + `` + x + `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
                                        </div>`);
                                if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                                    data.push({
                                        id: res[0].produk[x].gudang_barang_jadi[0].id,
                                        text: res[0].produk[x].nama,
                                        jumlah: res[0].produk[x].pivot.jumlah,
                                        qt: cek_stok(res[0].produk[x].gudang_barang_jadi[0].id)
                                    });
                                } else {
                                    for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                        data.push({
                                            id: res[0].produk[x].gudang_barang_jadi[y].id,
                                            text: res[0].produk[x].gudang_barang_jadi[y].nama,
                                            jumlah: res[0].produk[x].pivot.jumlah,
                                            qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
                                        });
                                    }
                                }
                                $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
                                    placeholder: 'Pilih Variasi',
                                    data: data,
                                    templateResult: function(data) {
                                        var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                        return $span;
                                    },
                                    templateSelection: function(data) {
                                        var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                        return $span;
                                    }
                                });

                                $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger("change");
                                tes.append(`</div>`)
                            }
                            tes.append(`</fieldset>`);
                        }
                    });
                    checkvalidasi();
                });
            }

            function load_variasi() {
                produk = [];
                produk = <?php
                            $prd = array();
                            if (isset($e->Pesanan)) {
                                $p = array();
                                if (isset($e->Pesanan->DetailPesanan)) {
                                    echo json_encode($e->Pesanan->DetailPesanan);
                                } else {
                                    echo json_encode($prd);
                                }
                            } else {
                                echo json_encode($prd);
                            } ?>;
                if (produk.length > 0) {
                    for (var w = 0; w < produk.length; w++) {
                        $.ajax({
                            url: '/api/penjualan_produk/select/' + produk[w]['penjualan_produk_id'],
                            type: 'GET',
                            dataType: 'json',
                            async: false,
                            success: function(res) {
                                for (var x = 0; x < res[0].produk.length; x++) {
                                    var data = [];
                                    if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                                        data.push({
                                            id: res[0].produk[x].gudang_barang_jadi[0].id,
                                            text: res[0].produk[x].nama,
                                            jumlah: res[0].produk[x].pivot.jumlah,
                                            qt: cek_stok(res[0].produk[x].gudang_barang_jadi[0].id)
                                        });
                                    } else {
                                        for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                            data.push({
                                                id: res[0].produk[x].gudang_barang_jadi[y].id,
                                                text: res[0].produk[x].gudang_barang_jadi[y].nama,
                                                jumlah: res[0].produk[x].pivot.jumlah,
                                                qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
                                            });
                                        }
                                    }
                                    $('select[name="variasi[' + w + '][' + x + ']"]').select2({
                                        placeholder: 'Pilih Variasi',
                                        data: data,
                                        templateResult: function(data) {
                                            var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                            return $span;
                                        },
                                        templateSelection: function(data) {
                                            var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                            return $span;
                                        }
                                    });

                                    $('select[name="variasi[' + w + '][' + x + ']"]').trigger("change");
                                }
                            }
                        });
                    }
                }

            }

            $("#alamatinstansi").autocomplete({
                source: function(request, response) {

                    $.ajax({
                        dataType: 'json',
                        url: "/api/penjualan/check_alamat",
                        data: {
                            term: request.term
                        },

                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.alamat,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#instansi").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: '/api/customer/get_instansi/' + $('#customer_id').val() + '/' + yyyy,
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el,
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#satuan_kerja").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        dataType: 'json',
                        url: "/api/ekatalog/all_satuan",
                        data: {
                            term: request.term
                        },
                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.satuan,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $("#deskripsi").autocomplete({
                source: function(request, response) {

                    $.ajax({
                        dataType: 'json',
                        url: "/api/ekatalog/all_deskripsi",
                        data: {
                            term: request.term
                        },

                        success: function(data) {

                            var transformed = $.map(data, function(el) {
                                return {
                                    label: el.deskripsi,
                                    id: el.id
                                };
                            });
                            response(transformed);
                        },
                        error: function() {
                            response([]);
                        }
                    });
                }
            });

            $('#perencanaantable').on('click', '#btntransfer', function() {
                var id = $(this).closest('tr').find('#btntransfer').attr('data-id');
                var nama_produk = $(this).closest('tr').find('#btntransfer').attr('data-nama_produk');
                var produk_id = $(this).closest('tr').find('#btntransfer').attr('data-produk');
                var jumlah = $(this).closest('tr').find('#btntransfer').attr('data-jumlah');
                var harga = $(this).closest('tr').find('#btntransfer').attr('data-harga');
                transferproduk(id, nama_produk, produk_id, jumlah, harga);
            });

            function transferproduk(id, nama_produk, produk_id, jumlah, harga){
                var data = `<tr>
                        <td></td>
                        <td>
                            <div class="form-group select_item">
                                <select name="penjualan_produk_id[]" id="0" class="select2 form-control custom-select penjualan_produk_id @error('penjualan_produk_id') is-invalid @enderror" style="width:100%;">
                                    <option value="`+produk_id+`">`+nama_produk+`</option>
                                </select>
                                <div class="detailjual" id="tes0">
                                </div>
                            </div>
                            <div id="detail_produk" class="detail_produk"></div>
                        </td>
                        <td>
                            <div class="form-group d-flex justify-content-center">
                                <div class="input-group">
                                    <input type="number" class="form-control produk_jumlah" aria-label="produk_satuan" name="produk_jumlah[]" id="produk_jumlah0" value="`+jumlah+`" style="width:100%;">

                                </div>
                                <small id="produk_ketersediaan"></small>
                            </div>
                        </td>
                        <td>
                            <div class="form-group d-flex justify-content-center">

                                <input type="text" class="form-control produk_harga" name="produk_harga[]" id="produk_harga0" value="`+formatmoney(harga)+`" placeholder="Masukkan Harga" style="width:100%;"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group d-flex justify-content-center">

                                <input type="text" class="form-control produk_ongkir" name="produk_ongkir[]" id="produk_ongkir0" placeholder="Masukkan Ongkir" style="width:100%;"/>
                            </div>
                        </td>
                        <td>
                            <div class="form-group d-flex justify-content-center">
                                <input type="text" class="form-control produk_subtotal" name="produk_subtotal[]" id="produk_subtotal0" placeholder="Masukkan Subtotal" value="`+ formatmoney(jumlah * parseInt(harga)) + `" style="width:100%;" readonly/>
                            </div>
                        </td>
                        <td hidden><input type="hidden" class="rencana_id" name="rencana_id[]" id="rencana_id0" readonly value="`+id+`"></td>
                        <td>
                            <a id="removerowproduk"><i class="fas fa-minus" style="color: red;"></i></a>
                        </td>
                    </tr>`;

                if ($('#produktable > tbody > tr').length <= 0) {
                    $('#produktable tbody').append(data);
                    select_data();
                    numberRowsProduk($("#produktable"));
                    totalhargaprd();
                } else {
                    $('#produktable tbody tr:last').after(data);
                    select_data();
                    numberRowsProduk($("#produktable"));
                    totalhargaprd();
                }
                var index = $('#produktable > tbody > tr').length - 1;
                $.ajax({
                    url: '/api/penjualan_produk/select/' + produk_id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        var tes = $('#detail_produk' + index);
                        tes.empty();
                        var datas = "";
                        tes.append(`<fieldset><legend><b>Detail Produk</b></legend>`);
                        for (var x = 0; x < res[0].produk.length; x++) {
                            var data = [];
                            tes.append(`<div>`);
                            tes.append(`<div class="card-body blue-bg">
                                            <h6>` + res[0].produk[x].nama + `</h6>
                                            <select class="form-control variasi" name="variasi[` + index + `][` + x + `]" style="width:100%;" id="variasi` + index + `` + x + `" data-attr="variasi` + x + `" data-id="` + x + `"></select>
                                            <span class="invalid-feedback d-block ketstok" name="ketstok[` + index + `][` + x + `]" id="ketstok` + index + `` + x + `" data-attr="ketstok` + x + `" data-id="` + x + `"></span>
                                        </div>`);
                            if (res[0].produk[x].gudang_barang_jadi.length <= 1) {
                                data.push({
                                    id: res[0].produk[x].gudang_barang_jadi[0].id,
                                    text: res[0].produk[x].nama,
                                    jumlah: res[0].produk[x].pivot.jumlah,
                                    qt: cek_stok(res[0].produk[x].gudang_barang_jadi[0].id)
                                });
                            } else {
                                for (var y = 0; y < res[0].produk[x].gudang_barang_jadi.length; y++) {
                                    data.push({
                                        id: res[0].produk[x].gudang_barang_jadi[y].id,
                                        text: res[0].produk[x].gudang_barang_jadi[y].nama,
                                        jumlah: res[0].produk[x].pivot.jumlah,
                                        qt: cek_stok(res[0].produk[x].gudang_barang_jadi[y].id)
                                    });
                                }
                            }
                            $(`select[name="variasi[` + index + `][` + x + `]"]`).select2({
                                placeholder: 'Pilih Variasi',
                                data: data,
                                templateResult: function(data) {
                                    var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                    return $span;
                                },
                                templateSelection: function(data) {
                                    var $span = $(`<div><span class="col-form-label">` + data.text + `</span><span class="badge blue-text float-right col-form-label stok" data-id="` + data.qt + `">` + data.qt + `</span></div>`);
                                    return $span;
                                }
                            });

                            $(`select[name="variasi[` + index + `][` + x + `]"]`).trigger("change");
                            tes.append(`</div>`)
                        }
                        tes.append(`</fieldset>`);
                        // tes.html(datas);
                    }
                });
            }
    });
</script>
@stop
