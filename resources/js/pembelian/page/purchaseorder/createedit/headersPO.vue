<script>
    import moment from 'moment'
    export default {
        data() {
            return {
                permintaan: {
                    no_pp: 'PP/PRD',
                    tanggal_diminta: '2020-01-01',
                    tanggal_dibutuhkan: '2020-01-01',
                },
                divisi: {
                    nama_divisi: 'Divisi',
                    jenis_barang: 'Jenis Barang',
                    sisa_budget: 2000000,
                }
            }
        },
        methods: {
            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }).format(angka)
            },
            moment(date) {
                return moment(date).lang('id').format('LL')
            },
        },
    }

</script>
<template>
    <div>
        <div class="card">
            <div class="card-body">
                <div class="row row-whith-wide-cards">
                    <div class="col d-flex align-items-stretch">
                        <div class="card card-primary">
                            <div class="card-body">
                                <h4 class="card-title">Permintaan Pembelian</h4>
                                <div class="card-text mt-5">
                                    <div class="row">
                                        <div class="col-1">
                                            <i class="fa fa-credit-card" aria-hidden="true"></i>
                                        </div>
                                        <div class="col">
                                            <p>No PP</p>
                                        </div>
                                        <div class="col text-dark text-bold">
                                            <p>{{ permintaan.no_pp }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </div>
                                        <div class="col">
                                            <p>Tanggal Diminta</p>
                                        </div>
                                        <div class="col text-dark text-bold">
                                            <p>{{ moment(permintaan.tanggal_diminta) }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                        </div>
                                        <div class="col">
                                            <p>Tanggal Dibutuhkan</p>
                                        </div>
                                        <div class="col text-dark text-bold">
                                            <p>{{ moment(permintaan.tanggal_dibutuhkan) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col d-flex align-items-stretch">
                        <div class="card card-success">
                            <div class="card-body">
                                <h4 class="card-title">Divisi</h4>
                                <div class="card-text mt-5">
                                    <div class="row">
                                        <div class="col-1">
                                            <i class="fa fa-building" aria-hidden="true"></i>
                                        </div>
                                        <div class="col">
                                            <p>Divisi</p>
                                        </div>
                                        <div class="col text-dark text-bold">
                                            <p>{{ divisi.nama_divisi }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <i class="fa fa-database" aria-hidden="true"></i>
                                        </div>
                                        <div class="col">
                                            <p>Jenis Barang</p>
                                        </div>
                                        <div class="col text-dark text-bold">
                                            <p>{{ divisi.jenis_barang }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-1">
                                            <i class="fas fa-money-bill-wave"></i>
                                        </div>
                                        <div class="col">
                                            <p>Sisa Budget</p>
                                        </div>
                                        <div class="col text-dark text-bold">
                                            <p>{{ formatRupiah(divisi.sisa_budget) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-secondary" role="alert">
                    <div class="row">
                        <div class="col-1">
                            <i class="fas mt-4 fa-2xl fa-info-circle"></i>
                        </div>
                        <div class="col">
                            <small>Tujuan</small><br>
                            <b>PP/2020/0001</b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
