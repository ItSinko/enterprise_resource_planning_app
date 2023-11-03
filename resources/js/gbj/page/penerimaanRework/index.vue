<script>
import Header from '../../components/header.vue'
import Transfer from './transfer'
import Riwayat from './riwayat'
import axios from 'axios';
export default {
    components: {
        Header,
        Transfer,
        Riwayat,
    },
    data() {
        return {
            title: 'Penerimaan Rework',
            breadcumbs: [
                {
                    name: 'Home',
                    link: '/gbj/dashboard'
                },
                {
                    name: 'Permintaan Rework',
                    link: '/penerimaan-rework'
                }
            ],
            penerimaan: [],
            riwayat: [],
        }
    },
    methods: {
        async getData() {
            try {
                this.$store.dispatch('setLoading', true);
                const { data: penerimaan } = await axios.get(`/api/gbj/rw/dp/seri`);
                const { data:riwayat } = await axios.get('/api/gbj/rw/riwayat_penerimaan')
                this.penerimaan = penerimaan.map(terima => {
                    return {
                        nama: terima.item[0].nama_produk,
                        ...terima,
                    }
                });
                this.riwayat = riwayat.map(item => {
                    return {
                        ...item,
                        nama: item.item[0].nama_produk,
                        tgl_tf: this.dateTimeFormat(item.tgl_tf),
                    }
                });
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false);
            }
        }
    },
    mounted() {
        this.getData();
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
                            type="button" role="tab" aria-controls="pills-home" aria-selected="true">Penerimaan Rework</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-profile-tab" data-toggle="pill" data-target="#pills-profile"
                            type="button" role="tab" aria-controls="pills-profile" aria-selected="false">Riwayat</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                        <Transfer :dataTable="penerimaan" @refresh="getData" />
                    </div>
                    <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                        <Riwayat :dataTable="riwayat" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>