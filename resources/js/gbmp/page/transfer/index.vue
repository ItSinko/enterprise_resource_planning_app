<script>
    import Header from '../../components/header.vue'
    import Table from './table.vue'
    import Loading from '../../components/loading.vue'
    import axios from 'axios'
    import addTransfer from './tambah.vue'
    import detailTransfer from './detail.vue'
    import mix from './mix'
    export default {
        mixins: [mix],
        components: {
            Header,
            Table,
            Loading,
            addTransfer,
            detailTransfer
        },
        data() {
            return {
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Transfer Antar Gudang',
                        link: '/gbmp/transfer'
                    },
                ],
                title: 'Transfer Antar Gudang',
                gudangtransfers: [],
                search: '',
                gudangtransfer: [],
                details: null,
                divisi: null,
            }
        },
        created() {
            this.getGudangTransfer()
        },
        methods: {
            async getGudangTransfer() {
                this.loading = true
                const getDivisi = localStorage.getItem('divisi')
                this.divisi = getDivisi
                const { data } = await axios.get(`/api/gbmp/data/${getDivisi}`)
                this.gudangtransfers = data.data
                this.loading = false
            },
            clickFilterdalamProses(filter) {
                if (this.gudangtransfer.includes(filter)) {
                    this.gudangtransfer = this.gudangtransfer.filter(item => item !== filter)
                } else {
                    this.gudangtransfer.push(filter)
                }
            },
            createTransfer() {
                $('.modalAddTransfer').modal('show')
            },
            async detailTransfer(id) {
                const { data } = await axios.get(`/api/gbmp/data/${this.divisi}/${id}`)
                this.details = data.data
                this.$nextTick(() => {
                    $('.modalDetailTransfer').modal('show');
                })
            },

            barang(brg, id) {
                console.log(brg)
                switch (brg) {
                    case 'Transfer Barang':
                        return this.$swal({
                            'title': 'Transfer Barang',
                            'text': 'Anda yakin ingin mentransfer barang?',
                            'icon': 'warning',
                            'showCancelButton': true,
                            'confirmButtonColor': '#3085d6',
                            'cancelButtonColor': '#d33',
                            'confirmButtonText': 'Ya',
                            'cancelButtonText': 'Batal',
                        }).then((result) => {
                            if (result.value) {
                                this.$swal({
                                    'title': 'Berhasil',
                                    'text': 'Barang berhasil ditransfer',
                                    'icon': 'success',
                                    'showConfirmButton': false,
                                    'timer': 1500,
                                })
                            }
                        })
                    case 'Terima Barang':
                        return this.$swal({
                            'title': 'Terima Barang',
                            'text': 'Anda yakin ingin menerima barang dengan benar?',
                            'icon': 'warning',
                            'showCancelButton': true,
                            'confirmButtonColor': '#3085d6',
                            'cancelButtonColor': '#d33',
                            'confirmButtonText': 'Ya',
                            'cancelButtonText': 'Batal',
                        }).then((result) => {
                            if (result.value) {
                                this.$swal({
                                    'title': 'Berhasil',
                                    'text': 'Barang berhasil diterima',
                                    'icon': 'success',
                                    'showConfirmButton': false,
                                    'timer': 1500,
                                })
                            }
                        })
                }
            }
        },
        computed: {
            filteredGudangTransfers() {
                let filtered = []
                if (this.gudangtransfer.length > 0) {
                    this.gudangtransfer.forEach(filter => {
                        filtered = filtered.concat(this.gudangtransfers.filter(item => item.status == filter))
                    })
                } else {
                    filtered = this.gudangtransfers
                }

                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
                return filtered.filter((gudangtransfer) => {
                    const search = this.search.toLowerCase();
                    const no_transfer = dataIsNotNull(gudangtransfer.no_transfer).toString().toLowerCase();
                    const divisi = dataIsNotNull(gudangtransfer.divisi).toString().toLowerCase();
                    const tanggal = dataIsNotNull(gudangtransfer.tanggal).toString().toLowerCase();
                    const keterangan = dataIsNotNull(gudangtransfer.keterangan).toString().toLowerCase();
                    const status = dataIsNotNull(gudangtransfer.status).toString().toLowerCase();
                    const ket = dataIsNotNull(gudangtransfer.ket).toString().toLowerCase();
                    return no_transfer.includes(search) || divisi.includes(search) || tanggal.includes(
                        search) || keterangan.includes(search) || status.includes(search) || ket.includes(
                        search);
                })
            },

            getUniqueStatus() {
                const unique = [...new Set(this.gudangtransfers.map(item => item.status))];
                return unique;
            },
        },
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <add-transfer @refresh="getGudangTransfer"/>
        <detail-transfer :detail="details" v-if="details"/>
        <Loading v-if="loading" />
        <div class="card" v-else>
            <div class="card-body">
                <div class="d-flex justify-content-end">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex">
                                    <p class="bd-highlight pr-2">Keterangan</p>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div class="d-flex">
                                        <div class="box-ket bg-danger"></div>
                                        <p class="pr-2">Divisi Tujuan Barang Keluar</p>
                                    </div>
                                    <div class="d-flex">
                                        <div class="box-ket bg-success"></div>
                                        <p class="pr-2">Divisi Tujuan Barang Keluar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex bd-highlight">
                    <div class="p-2 bd-highlight">
                        <button type="button" @click="createTransfer" class="btn btn-primary" data-toggle="modal"
                            data-target="#modalTransfer">
                            <i class="fas fa-plus"></i> Transfer Antar Gudang
                        </button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <span class="float-left filter">
                            <button type="button" class="btn btn-outline-info" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <div class="dropdown-menu">
                                <div class="px-3 py-3">
                                    <div class="form-group"><label for="">Status</label></div>
                                    <div class="form-group" v-for="status in getUniqueStatus" :key="status">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"
                                                @click="clickFilterdalamProses(status)" :ref="status" :value="status">
                                            <label for="" class="form-check-label">{{ status }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="ml-auto p-2 bd-highlight">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Cari Transfer Antar Gudang"
                                v-model="search">
                        </div>
                    </div>
                </div>
                <Table :transfers="renderPaginate" @detail="detailTransfer" @barang="barang" @refresh="getGudangTransfer"/>
                <div class="card-footer">
                    <div class="d-flex flex-row-reverse bd-highlight">
                        <nav aria-label="...">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                                </li>
                                <li class="page-item" :class="paginate == currentPage ? 'active' : ''"
                                    v-for="paginate in pages" :key="paginate">
                                    <a class="page-link" @click="nowPage(paginate)">{{ paginate }}</a>
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
    </div>
</template>
<style>
    .box-ket {
        width: 20px;
        height: 20px;
        border-radius: 5px;
        margin-right: 5px;
    }

</style>
