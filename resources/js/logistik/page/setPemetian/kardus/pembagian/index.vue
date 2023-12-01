<script>
import pagination from '../../../../components/pagination.vue';
import Header from '../../../../components/header.vue';
import tambah from './tambah.vue';
import axios from 'axios';
export default {
    components: {
        pagination,
        Header,
        tambah,
    },
    data() {
        return {
            title: 'Pembagian Wilayah',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '/logistik/dashboard'
                },
                {
                    name: 'Set Pemetian',
                    link: '/logistik/pengiriman/pemetian'
                },
                {
                    name: 'Set Pembagian Wilayah',
                    link: '#'
                }
            ],
            dataTable: [],
            search: '',
            renderPaginate: [],
            showModal: false,
            maksInput: 0,
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        refresh() {
            this.$emit('refresh');
        },
        modalTambah() {
            this.showModal = true;
            this.$nextTick(() => {
                $('.modalPembagian').modal('show');
            });
        },
        async getData() {
            try {
                this.$store.dispatch('setLoading', true);
                const { data } = await axios.get(`/api/logistik/rw/pack_wilayah/show/${this.$route.params.id}`);
                this.dataTable = data.data
                this.maksInput = data.jumlah;
            } catch (error) {
                console.log(error);
            } finally {
                this.$store.dispatch('setLoading', false);
            }
        }
    },
    computed: {
        filteredDalamProses() {
            return this.dataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    },
    mounted() {
        this.getData();
    }
}
</script>
<template>
    <div>
        <Header :breadcumbs="breadcumbs" :title="title" />
        <tambah v-if="showModal" @closeModal="showModal = false" :jumlahMaksKirim="maksInput" @refresh="getData" />
        <div class="card" v-if="!$store.state.loading">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="modalTambah">
                            Tambah Pembagian Wilayah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight"><input type="text" v-model="search" class="form-control"
                            placeholder="Cari..."></div>
                </div>
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">

                    </div>
                </div>
                <table class="table text-center">
                    <thead>
                        <tr>
                            <th rowspan="2">No</th>
                            <th rowspan="2">Nama Produk</th>
                            <th rowspan="2">Wilayah</th>
                            <th colspan="2">Jumlah</th>
                            <th rowspan="2">Aksi</th>
                        </tr>
                        <tr>
                            <th>Selesai</th>
                            <th>Belum Selesai</th>
                        </tr>
                    </thead>
                    <tbody v-if="renderPaginate.length > 0">
                        <tr v-for="(data, idx) in renderPaginate" :key="idx">
                            <td>{{ idx + 1 }}</td>
                            <td>{{ data.produk }}</td>
                            <td>{{ data.wilayah }}</td>
                            <td>{{ data.selesai }}</td>
                            <td>{{ data.belum }}</td>
                            <td>
                                <router-link
                                    :to="{ name: 'detailPengkardusan', params: { id: data.id, linkNow: $route.fullPath } }"
                                    class="btn btn-outline-primary btn-sm">Set Kardus</router-link>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr>
                            <td colspan="100%" class="text-center">Tidak ada data</td>
                        </tr>
                    </tbody>
                </table>
                <pagination :filteredDalamProses="filteredDalamProses"
                    @updateFilteredDalamProses="updateFilteredDalamProses" />
            </div>
        </div>
        <div v-else>
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
</template>