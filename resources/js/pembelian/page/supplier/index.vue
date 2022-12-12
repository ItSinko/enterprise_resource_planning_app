<script>
import Table from './table.vue'
import Header from '../../components/header.vue'
import mix from './mix'
export default {
    components: {
        Table,
        Header
    },
    mixins: [mix],
    data() {
        return {
            title: 'Supplier',
            breadcumbs: [
                {
                    name: 'Beranda`',
                    link: '#'
                },
                {
                    name: 'Supplier',
                    link: '/pembelian/supplier'
                }
            ],

            // Search
            search: '',

            suppliers: [
                {
                    id: 1,
                    kode: 'SUP-001',
                    nama: 'PT. Sinar Jaya',
                    jenis: 'local',
                    kurs: 'IDR',
                    email: 'sinarjaya@mail.com',
                    telepon: '08123456789',
                },
                {
                    id: 2,
                    kode: 'SUP-002',
                    nama: 'Jia Wang',
                    jenis: 'local',
                    kurs: 'IDR',
                    email: 'Jiawang@wang.com',
                    telepon: '08123456789',
                },
            ]
        }
    },
    methods: {
        addSupplier() {
            this.$router.push('/pembelian/supplier/create')
        },
        editSupplier(id) {
            this.$router.push(`/pembelian/supplier/${id}/edit`)
        },
        detailSupplier(id) {
            this.$router.push(`/pembelian/supplier/${id}`)
        },
    },
    computed: {
        filteredSuppliers() {
            const dataIsNotNull = (data) =>
            data !== null && data !== undefined && data !== "" ? data : "-";
            return this.suppliers.filter((supplier) => {
                return (
                    dataIsNotNull(supplier.kode)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    dataIsNotNull(supplier.nama)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    dataIsNotNull(supplier.jenis)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    dataIsNotNull(supplier.kurs)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    dataIsNotNull(supplier.email)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase()) ||
                    dataIsNotNull(supplier.telepon)
                        .toString()
                        .toLowerCase()
                        .includes(this.search.toLowerCase())
                );
            });
        }
    }
}
</script>
<template>
    <div>
        <Header :breadcumbs="breadcumbs" :title="title" />
        <div class="card">
            <div class="card-body">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-primary" @click="addSupplier">
                            <i class="fas fa-plus"></i>
                            Tambah Supplier
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="Cari" v-model="search">
                        </div>
                    </div>
                </div>
                <Table :dataTable="renderPaginate" @edit="editSupplier" @detail="detailSupplier"/>
            </div>
                        <div class="card-footer">
                <div class="d-flex flex-row-reverse bd-highlight">
                    <nav aria-label="...">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                            </li>
                            <li class="page-item" :class="paginate == currentPage ? 'active' : ''"
                                v-for="paginate in pages" :key="paginate">
                                <a class="page-link" @click="nowPage(paginate)">{{paginate}}</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" :disabled="currentPage == pages[pages.length - 1]"
                                    @click="nextPage">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>