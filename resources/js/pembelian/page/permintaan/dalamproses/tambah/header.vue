<script>
import moment from 'moment'
export default {
    props: {
        headers: {
            type: Object,
            default: () => {}
        }
    },
    methods: {
        moment(date) {
            return moment(date).lang('id').format('LL')
        },
        formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka)
        }
    }
}
</script>
<template>
    <div>
        <div class="row">
            <div class="col">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">Rencana Pembelian</h4>
                        <p class="card-text text-bold">{{ headers.rencana }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-secondary">
                    <div class="card-body">
                        <h4 class="card-title">Tanggal Diminta</h4>
                        <p class="card-text text-bold">{{ moment(headers.tanggal) }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">Sisa Budget Oktober</h4>
                        <p class="card-text text-bold">{{ formatRupiah(headers.sisa) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
.card-primary{
    background-color: #dbdee6;
    color: #465c81;
}

.card-secondary {
    background-color: #e1e1e1;
    color: #505050;
}
</style>