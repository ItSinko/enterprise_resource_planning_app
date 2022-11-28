<script>
export default {
    props: {
        header: {
            type: Object,
            default: () => {}
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
                                        <p>PP/2020/0001</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fa fa-building" aria-hidden="true"></i>
                                    </div>
                                    <div class="col">
                                        <p>Divisi</p>
                                    </div>
                                    <div class="col text-bold">
                                        <p>PP/2020/0001</p>
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
                                        <p>PP/2020/0001</p>
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
                                        <p>PP/2020/0001</p>
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
                                        <p>PP/2020/0001</p>
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
                                        <p>PP/2020/0001</p>
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
                                        <p>PP/2020/0001</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fas fa-truck-moving"></i>
                                    </div>
                                    <div class="col">
                                        <p>Ekspedisi</p>
                                    </div>
                                    <div class="col text-bold">
                                        <p>PP/2020/0001</p>
                                    </div>
                                </div>
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
                                        <p>PP/2020/0001</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fa fa-map-pin" aria-hidden="true"></i>
                                    </div>
                                    <div class="col text-bold">
                                        <p>PP/2020/0001</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fas fa-mail-bulk"></i>
                                    </div>
                                    <div class="col text-bold">
                                        <p>PP/2020/0001</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-1">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="col text-bold">
                                        <p>PP/2020/0001</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card card-secondary">
                <div class="card-body">
                    <div class="row">
                    <div class="col-1">
                        <i class="fas fa-info-circle fa-2xl"></i>
                    </div>
                    <div class="col">
                        <p>Jadwal Pengecekan</p>
                        <p class="text-bold">
                            01 Januari 2020 - 31 Desember 2020
                        </p>
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
    .card-secondary {
        background-color: #d8d8d8;
        color: #616161;
    }
        .row-whith-wide-cards .card {
  width: 100%;
}
</style>