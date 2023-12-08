<script>
import axios from 'axios';
import moment from 'moment'
import Header from '../../components/header.vue'
import transfer from './transfer.vue';
import riwayatTransfer from './riwayat.vue';
export default {
    components: {
        Header,
        transfer,
        riwayatTransfer
    },
    data() {
        return {
            title: 'Transfer Gudang',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Transfer Gudang',
                    link: '#'
                }
            ],
            pengiriman: [],
            riwayat: [],
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: pengiriman } = await axios.get('/api/prd/kirim')
                const { data: riwayat } = await axios.get('/api/prd/history/pengiriman')
                this.pengiriman = pengiriman.map(item => {
                    return {
                        ...item,
                        periode: this.monthFormat(item.tanggal_mulai),
                        tgl_mulai: this.dateFormat(item.tanggal_mulai),
                        tgl_selesai: this.dateFormat(item.tanggal_selesai),
                    }
                })
                this.riwayat = riwayat.map(item => {
                    return {
                        ...item,
                        tanggal: this.dateFormat(item.waktu_tf),
                        waktu: this.getTime(item.waktu_tf),
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        },
        monthFormat(date) {
            return moment(date).lang('id').format('MMMM')
        },
        getTime(date) {
            return moment(date).format('HH:mm')
        },
    },
    mounted() {
        this.getData()
    }

}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body" v-if="!$store.state.loading">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Transfer</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <transfer :dataTable="pengiriman" @refresh="getData" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <riwayatTransfer :dataTable="riwayat" />
                    </div>
                </div>
            </div>
            <div class="card-body" v-else>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>