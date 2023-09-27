<script>
import Header from '../../components/header.vue';
import Table from './table.vue';
import axios from 'axios';
import pagination from '../../components/pagination.vue';
export default {
    components: {
        Header,
        Table,
        pagination,
    },
    data() {
        return {
            title: 'Karyawan',
            breadcumbs: [
                {
                    name: 'Beranda',
                    link: '#'
                },
                {
                    name: 'Karyawan',
                    link: '/hr/karyawan'
                },
            ],
            karyawan: [],
            search: '',
            renderPaginate: [],
        }
    },
    methods: {
        async getKaryawan() {
            try {
                const { data } = await axios.get('/api/karyawan_all');
                this.karyawan = data;
            } catch (error) {
                console.log(error);
            }
        },
        updatePage(page) {
            this.renderPaginate = page
        },
        tambah() {
            this.$router.push({ name: 'tambah-karyawan' })
        }
    },
    computed: {
        paginateData() {
            return this.karyawan.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        },
    },
    mounted() {
        this.getKaryawan();
    }
}
</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight mb-3">
                    <div class="mr-auto p-2 bd-highlight">
                        <button class="btn btn-primary" @click="tambah">
                            <i class="fa fa-plus"></i>
                            Tambah
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari..." v-model="search">
                        </div>
                    </div>
                </div>
                <Table :dataTable="renderPaginate" />
                <pagination :DataTable="paginateData" @updatePage="updatePage" />
            </div>
        </div>
    </div>
</template>
