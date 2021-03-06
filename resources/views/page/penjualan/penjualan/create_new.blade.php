@extends('adminlte.page')

@section('title', 'ERP')

@section('content_header')
<h1 class="m-0 text-dark">Penjualan</h1>
@stop

@section('adminlte_css')
<style>
    .hide {
        display: none !important;
    }

    .align-right {
        text-align: right;
    }

    .select2 {
        width: 100% !important;
    }




    #grad1 {
        background-color: #9C27B0;
        background-image: linear-gradient(120deg, #FF4081, #81D4FA)
    }

    #msform {
        text-align: center;
        position: relative;
        margin-top: 20px
    }

    #msform fieldset .form-card {
        background: white;
        border: 0 none;
        border-radius: 0px;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        padding: 20px 40px 30px 40px;
        box-sizing: border-box;
        width: 94%;
        margin: 0 3% 20px 3%;
        position: relative
    }

    #msform fieldset {
        background: white;
        border: 0 none;
        border-radius: 0.5rem;
        box-sizing: border-box;
        width: 100%;
        margin: 0;
        padding-bottom: 20px;
        position: relative
    }

    #msform fieldset:not(:first-of-type) {
        display: none
    }

    #msform fieldset .form-card {
        text-align: left;
        color: #9E9E9E
    }

    /* #msform input,
    #msform textarea {
        padding: 0px 8px 4px 8px;
        border: none;
        border-bottom: 1px solid #ccc;
        border-radius: 0px;
        margin-bottom: 25px;
        margin-top: 2px;
        width: 100%;
        box-sizing: border-box;
        font-family: montserrat;
        color: #2C3E50;
        font-size: 16px;
        letter-spacing: 1px
    } */

    #msform input:focus,
    #msform textarea:focus {
        -moz-box-shadow: none !important;
        -webkit-box-shadow: none !important;
        box-shadow: none !important;
        border: none;
        font-weight: bold;
        border-bottom: 2px solid skyblue;
        outline-width: 0
    }

    #msform .action-button {
        width: 100px;
        background: skyblue;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px
    }

    #msform .action-button:hover,
    #msform .action-button:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px skyblue
    }

    #msform .action-button-previous {
        width: 100px;
        background: #616161;
        font-weight: bold;
        color: white;
        border: 0 none;
        border-radius: 0px;
        cursor: pointer;
        padding: 10px 5px;
        margin: 10px 5px
    }

    #msform .action-button-previous:hover,
    #msform .action-button-previous:focus {
        box-shadow: 0 0 0 2px white, 0 0 0 3px #616161
    }

    select.list-dt {
        border: none;
        outline: 0;
        border-bottom: 1px solid #ccc;
        padding: 2px 5px 3px 5px;
        margin: 2px
    }

    select.list-dt:focus {
        border-bottom: 2px solid skyblue
    }

    .card {
        z-index: 0;
        border: none;
        border-radius: 0.5rem;
        position: relative
    }

    .fs-title {
        font-size: 25px;
        color: #2C3E50;
        margin-bottom: 10px;
        font-weight: bold;
        text-align: left
    }

    #progressbar {
        margin-bottom: 30px;
        overflow: hidden;
        color: lightgrey
    }

    #progressbar .active {
        color: #000000
    }

    #progressbar li {
        list-style-type: none;
        font-size: 12px;
        width: 25%;
        float: left;
        position: relative
    }

    #progressbar #account:before {
        font-family: FontAwesome;
        content: "\f023"
    }

    #progressbar #personal:before {
        font-family: FontAwesome;
        content: "\f007"
    }

    #progressbar #payment:before {
        font-family: FontAwesome;
        content: "\f09d"
    }

    #progressbar #confirm:before {
        font-family: FontAwesome;
        content: "\f00c"
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: skyblue
    }

    .radio-group {
        position: relative;
        margin-bottom: 25px
    }

    .radio {
        display: inline-block;
        width: 204;
        height: 104;
        border-radius: 0;
        background: lightblue;
        box-shadow: 0 2px 2px 2px rgba(0, 0, 0, 0.2);
        box-sizing: border-box;
        cursor: pointer;
        margin: 8px 2px
    }

    .radio:hover {
        box-shadow: 2px 2px 2px 2px rgba(0, 0, 0, 0.3)
    }

    .radio.selected {
        box-shadow: 1px 1px 2px 2px rgba(0, 0, 0, 0.1)
    }

    .fit-image {
        width: 100%;
        object-fit: cover
    }
</style>
@stop

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center mt-0">
            <div class="col-11 col-sm-9 col-md-7 col-lg-6 text-center p-0 mt-3 mb-2">
                <div class="card px-0 pt-4 pb-0 mt-3 mb-3">
                    <div class="row">
                        <div class="col-md-12 mx-0">
                            <form id="msform">
                                <!-- progressbar -->
                                <ul id="progressbar">
                                    <li class="active" id="account"><strong>Account</strong></li>
                                    <li id="personal"><strong>Personal</strong></li>
                                    <li id="payment"><strong>Payment</strong></li>
                                    <li id="confirm"><strong>Finish</strong></li>
                                </ul> <!-- fieldsets -->
                                <fieldset>
                                    <div class="form-card">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Nama Customer</label>
                                                <div class="col-5">
                                                    <select name="customer_id" id="customer_id" class="form-control custom-select customer_id @error('customer_id') is-invalid @enderror">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Alamat</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label" name="alamat" id="alamat" readonly />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Telepon</label>
                                                <div class="col-5">
                                                    <input type="text" class="form-control col-form-label" name="telepon" id="telepon" readonly />
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Jenis Penjualan</label>
                                                <div class="col-5 col-form-label">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="jenis_penjualan" id="jenis_penjualan1" value="ekatalog" />
                                                        <label class="form-check-label" for="jenis_penjualan1">E-Catalogue</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="jenis_penjualan" id="jenis_penjualan2" value="spa" />
                                                        <label class="form-check-label" for="jenis_penjualan2">SPA</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="jenis_penjualan" id="jenis_penjualan3" value="spb" />
                                                        <label class="form-check-label" for="jenis_penjualan3">SPB</label>
                                                    </div>
                                                    <div class="invalid-feedback" id="msgjenis_penjualan">
                                                        @if($errors->has('jenis_penjualan'))
                                                        {{ $errors->first('jenis_penjualan')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <input type="button" name="next" class="next action-button" value="Next Step" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <div class="form-horizontal">
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Tanggal Pemesanan</label>
                                                <div class="col-4">
                                                    <input type="date" class="form-control col-form-label @error('tanggal_pemesanan') is-invalid @enderror" name="tanggal_pemesanan" id="tanggal_pemesanan" />
                                                    <div class="invalid-feedback" id="msgtanggal_pemesanan">
                                                        @if($errors->has('tanggal_pemesanan'))
                                                        {{ $errors->first('tanggal_pemesanan')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Instansi</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label @error('instansi') is-invalid @enderror" name="instansi" id="instansi" />
                                                    <div class="invalid-feedback" id="msginstansi">
                                                        @if($errors->has('instansi'))
                                                        {{ $errors->first('instansi')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Alamat Instansi</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label @error('alamatinstansi') is-invalid @enderror" name="alamatinstansi" id="alamatinstansi" />
                                                    <div class="invalid-feedback" id="msgalamatinstansi">
                                                        @if($errors->has('alamatinstansi'))
                                                        {{ $errors->first('alamatinstansi')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Provinsi</label>
                                                <div class="col-7">
                                                    <select name="provinsi" id="provinsi" class="form-control custom-select provinsi @error('provinsi') is-invalid @enderror" style="width: 100%;">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Satuan Kerja</label>
                                                <div class="col-7">
                                                    <input type="text" class="form-control col-form-label @error('satuan_kerja') is-invalid @enderror" name="satuan_kerja" id="satuan_kerja" />
                                                    <div class="invalid-feedback" id="msgsatuan_kerja">
                                                        @if($errors->has('satuan_kerja'))
                                                        {{ $errors->first('satuan_kerja')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Status</label>
                                                <div class="col-5 col-form-label">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="satuan4" value="draft" />
                                                        <label class="form-check-label" for="satuan4">Draft</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="satuan1" value="sepakat" />
                                                        <label class="form-check-label" for="satuan1">Sepakat</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="satuan2" value="negosiasi" />
                                                        <label class="form-check-label" for="satuan2">Negosiasi</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status" id="satuan3" value="batal" />
                                                        <label class="form-check-label" for="satuan3">Batal</label>
                                                    </div>

                                                    <div class="invalid-feedback" id="msgstatus">
                                                        @if($errors->has('status'))
                                                        {{ $errors->first('status')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">No Paket</label>
                                                <div class="col-5 input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text" id="ket_no_paket">AK1-</span>
                                                    </div>
                                                    <input type="text" class="form-control col-form-label @error('nomor_paket') is-invalid @enderror" name="no_paket" id="no_paket" aria-label="ket_no_paket" />
                                                    <div class="invalid-feedback" id="msgno_paket">
                                                        @if($errors->has('no_paket'))
                                                        {{ $errors->first('no_paket')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Batas Kontrak</label>
                                                <div class="col-4">
                                                    <input type="date" class="form-control col-form-label @error('batas_kontrak') is-invalid @enderror" name="batas_kontrak" id="batas_kontrak" />
                                                    <div class="invalid-feedback" id="msgbatas_kontrak">
                                                        @if($errors->has('batas_kontrak'))
                                                        {{ $errors->first('batas_kontrak')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="" class="col-form-label col-5" style="text-align: right">Deskripsi</label>
                                                <div class="col-5">
                                                    <textarea class="form-control col-form-label @error('deskripsi') is-invalid @enderror" name="deskripsi" id="deskripsi"></textarea>
                                                    <div class="invalid-feedback" id="msgdeskripsi">
                                                        @if($errors->has('deskripsi'))
                                                        {{ $errors->first('deskripsi')}}
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label for="keterangan" class="col-form-label col-5" style="text-align: right">Keterangan</label>
                                                <div class="col-5">
                                                    <textarea class="form-control col-form-label" name="keterangan"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="next" class="next action-button" value="Next Step" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title">Payment Information</h2>
                                        <div class="form-horizontal">
                                            <div class="float-right">
                                                <button class="btn btn-info btn-sm"><i class="fas fa-plus"></i> Tambah List</button>
                                            </div>
                                            <div class="card">
                                                <div class="card-body">

                                                </div>
                                            </div>
                                        </div>
                                        <div class="radio-group">
                                            <div class='radio' data-value="credit"><img src="https://i.imgur.com/XzOzVHZ.jpg" width="200px" height="100px"></div>
                                            <div class='radio' data-value="paypal"><img src="https://i.imgur.com/jXjwZlj.jpg" width="200px" height="100px"></div> <br>
                                        </div> <label class="pay">Card Holder Name*</label> <input type="text" name="holdername" placeholder="" />
                                        <div class="row">
                                            <div class="col-9"> <label class="pay">Card Number*</label> <input type="text" name="cardno" placeholder="" /> </div>
                                            <div class="col-3"> <label class="pay">CVC*</label> <input type="password" name="cvcpwd" placeholder="***" /> </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-3"> <label class="pay">Expiry Date*</label> </div>
                                            <div class="col-9"> <select class="list-dt" id="month" name="expmonth">
                                                    <option selected>Month</option>
                                                    <option>January</option>
                                                    <option>February</option>
                                                    <option>March</option>
                                                    <option>April</option>
                                                    <option>May</option>
                                                    <option>June</option>
                                                    <option>July</option>
                                                    <option>August</option>
                                                    <option>September</option>
                                                    <option>October</option>
                                                    <option>November</option>
                                                    <option>December</option>
                                                </select> <select class="list-dt" id="year" name="expyear">
                                                    <option selected>Year</option>
                                                </select> </div>
                                        </div>
                                    </div> <input type="button" name="previous" class="previous action-button-previous" value="Previous" /> <input type="button" name="make_payment" class="next action-button" value="Confirm" />
                                </fieldset>
                                <fieldset>
                                    <div class="form-card">
                                        <h2 class="fs-title text-center">Success !</h2> <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-3"> <img src="https://img.icons8.com/color/96/000000/ok--v2.png" class="fit-image"> </div>
                                        </div> <br><br>
                                        <div class="row justify-content-center">
                                            <div class="col-7 text-center">
                                                <h5>You Have Successfully Signed Up</h5>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


@section('adminlte_js')
<script>
    $(document).ready(function() {

        var current_fs, next_fs, previous_fs; //fieldsets
        var opacity;

        $(".next").click(function() {

            current_fs = $(this).parent();
            next_fs = $(this).parent().next();

            //Add Class Active
            $("#progressbar li").eq($("fieldset").index(next_fs)).addClass("active");

            //show the next fieldset
            next_fs.show();
            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    next_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });
        });

        $(".previous").click(function() {

            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();

            //Remove class active
            $("#progressbar li").eq($("fieldset").index(current_fs)).removeClass("active");

            //show the previous fieldset
            previous_fs.show();

            //hide the current fieldset with style
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now) {
                    // for making fielset appear animation
                    opacity = 1 - now;

                    current_fs.css({
                        'display': 'none',
                        'position': 'relative'
                    });
                    previous_fs.css({
                        'opacity': opacity
                    });
                },
                duration: 600
            });
        });

        $('.radio-group .radio').click(function() {
            $(this).parent().find('.radio').removeClass('selected');
            $(this).addClass('selected');
        });

        $(".submit").click(function() {
            return false;
        })

    });
</script>
@stop