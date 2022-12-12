<script>
export default {
    props: {
        headers: {
            type: Object,
            default: null
        }
    },
}
</script>
<template>
    <div>
        <div class="card card-primary">
            <div class="card-body text-center">
                <p class="text-bold">{{ headers.nama }}</p>
                <p>Alamat Perusahaan</p>
                <p>{{ headers.kode }}</p>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-body">
                <p class="text-bold">Data Supplier</p>
                <div class="row">
                    <div class="col-2">
                        <i class="fa fa-barcode fa-xl" aria-hidden="true"></i>
                    </div>
                    <div class="col">
                        <p>Kode Supplier</p>
                        <p class="text-bold">{{ headers.kode }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <i class="fa fa-map-pin fa-xl" aria-hidden="true"></i>
                    </div>
                    <div class="col">
                        <p>Jenis Supplier</p>
                        <p class="text-bold">{{ headers.jenis }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-2">
                        <i class="fas fa-money-bill-wave fa-xl"></i>
                    </div>
                    <div class="col">
                        <p>Kurs</p>
                        <p class="text-bold">{{ headers.kurs }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-secondary">
            <div class="card-body">
                <p class="text-bold">Kontak</p>
                <p>
                    <i class="fas fa-mail-bulk"></i>
                    <span class="ml-2">{{ headers.email }}</span>
                </p>
                <p>
                    <i class="fas fa-phone-alt"></i>
                    <span class="ml-2">{{ headers.telepon }}</span>
                </p>
                <p>
                    <i class="fa fa-fax" aria-hidden="true"></i>
                    <span class="ml-2">Fax</span>
                </p>
            </div>
        </div>
    </div>
</template>
<style>
    .card-primary{
        background-color: #edeff3;
        color: #465c81;
    }
    .card-secondary{
        background-color: #eeeeee;
        color: #908c8d;
    }
</style>