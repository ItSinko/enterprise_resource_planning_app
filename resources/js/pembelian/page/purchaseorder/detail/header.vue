<script>
    export default {
        props: {
            header: {
                type: Object,
                default: () => {
                    return {
                        pp_header: {
                            nopp: '',
                            jenis_barang: '',
                            divisi: '',
                            tgl_dibutuhkan: '',
                            tgl_diminta: '',
                            tujuan: '',
                        },
                        po_header: {
                            nopo: '',
                            tgl_po: '',
                            tgl_datang: '',
                            ekspedisi: '',
                        },
                        supplier_header: {
                            nama: '',
                            alamat: '',
                            email: '',
                            telp: '',
                        },
                    }
                }
            },
        },
    }

</script>
<template>
    <div>
        <div class="row row-whith-wide-cards">
            <div class="col d-flex align-items-stretch">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title text-bold">Permintaan Pembelian</h4>
                        <div class="card-text mt-5">
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                                </div>
                                <div class="col">
                                    <p>No PP</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ header.pp_header?.nopp }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-database" aria-hidden="true"></i>
                                </div>
                                <div class="col">
                                    <p>Jenis Barang</p>
                                </div>
                                <div class="col text-bold text-capitalize">
                                    <p>{{ header.pp_header?.jenis_barang }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-building" aria-hidden="true"></i>
                                </div>
                                <div class="col">
                                    <p>Divisi</p>
                                </div>
                                <div class="col text-bold text-capitalize">
                                    <p>{{ header.pp_header?.divisi }} </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                                <div class="col">
                                    <p>Tanggal Diminta</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ formatTanggal(header.pp_header?.tgl_diminta) }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                                <div class="col">
                                    <p>Tanggal Dibutuhkan</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ formatTanggal(header.pp_header?.tgl_dibutuhkan) }}</p>
                                </div>
                            </div>
                            <div class="alert alert-warning" role="alert">
                                <div class="row">
                                    <div class="col-2">
                                        <i class="fas mt-4 fa-2xl fa-info-circle"></i>
                                    </div>
                                    <div class="col text-capitalize">
                                        <small>Tujuan</small><br>
                                        <b>{{ header.pp_header?.tujuan }}</b>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card card-danger">
                    <div class="card-body">
                        <h4 class="card-title text-bold">Purchase Order</h4>
                        <div class="card-text mt-5">
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                                </div>
                                <div class="col">
                                    <p>No PO</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ header.po_header?.nopo }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                                <div class="col">
                                    <p>Tanggal PO</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ formatTanggal(header.po_header?.tgl_po) }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-calendar" aria-hidden="true"></i>
                                </div>
                                <div class="col">
                                    <p>Estimasi Kedatangan</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ formatTanggal(header.po_header?.tgl_datang) }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="col">
                                    <p>Tanggal Kedatangan</p>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ formatTanggal(header.po_header?.tgl_datang) }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-truck-moving"></i>
                                </div>
                                <div class="col">
                                    <p>Ekspedisi</p>
                                </div>
                                <div class="col text-bold text-capitalize">
                                    <p>{{ header.po_header?.ekspedisi }}</p>
                                </div>
                            </div>
                            <!-- <div class="alert alert-danger" role="alert">
                                <div class="row">
                                    <div class="col-2">
                                        <i class="fas mt-4 fa-2xl fa-exclamation-triangle"></i>
                                    </div>
                                    <div class="col">
                                        <small>Status</small><br>
                                        <div class="progress mt-1">
                                            <div class="progress-bar bg-success" role="progressbar" style="width: 25%;"
                                                aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="col d-flex align-items-stretch">
                <div class="card card-success">
                    <div class="card-body">
                        <h4 class="card-title text-bold">Supplier</h4>
                        <div class="card-text mt-5">
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-user-alt"></i>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ header.supplier_header?.nama }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fa fa-map-pin" aria-hidden="true"></i>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ header.supplier_header?.alamat }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-mail-bulk"></i>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ header.supplier_header?.email }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div class="col text-bold">
                                    <p>{{ header.supplier_header?.telp }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-1">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="col text-bold">
                                    <!-- <p>{{ header.po_header.top }}</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<style>
    .card-primary {
        background-color: #eff2f4;
        color: #5b7a8f;
    }

    .card-danger {
        background-color: #f5efef;
        color: #985d61;
    }

    .card-success {
        background-color: #e7e9e7;
        color: #616e61;
    }

    .row-whith-wide-cards .card {
        width: 100%;
    }

</style>
