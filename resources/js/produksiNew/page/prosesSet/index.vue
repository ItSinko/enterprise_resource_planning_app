<script>
import Header from '../../components/header.vue'
import proses from './proses'
import riwayat from './riwayat'
import axios from 'axios'
export default {
    components: {
        Header,
        proses,
        riwayat,
    },
    data() {
        return {
            title: 'Set Produk Reworks',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/produksi/dashboard'
                },
                {
                    name: 'Set Produk Reworks',
                    link: '#'
                },
            ],
            proses: [],
            riwayat: [],
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true)
                const { data: proses } = await axios.get('/api/prd/rw/proses')
                const { data: riwayat } = await axios.get('/api/prd/rw/tf/riwayat')
                this.proses = proses.map(item => {
                    return {
                        no_urut: `PRD-${item.urutan}`,
                        ...item,
                        tgl_mulai: this.dateFormat(item.tgl_mulai),
                        tgl_selesai: this.dateFormat(item.tgl_selesai),
                    }
                })
                this.riwayat = riwayat.map(item => {
                    return {
                        no_urut: `PRD-${item.urutan}`,
                        ...item,
                        tgl_tf: this.dateFormat(item.tgl_tf),
                    }
                })
            } catch (error) {
                console.log(error)
            } finally {
                this.$store.dispatch('setLoading', false)
            }
        }
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
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" data-target="#pills-home"
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Produk Reworks</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent" v-if="!$store.state.loading">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <proses :dataTable="proses" @refresh="getData" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <riwayat :dataTable="riwayat" />
                    </div>
                </div>
                <div class="spinner-border" role="status" v-else>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</template>