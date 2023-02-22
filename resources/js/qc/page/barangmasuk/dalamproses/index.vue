<script>
    import Table from './table'
    import Tambah from './tambah'
    export default {
        data() {
            return {
                dataTable: [{
                        no_po: 'PO-001',
                        divisi: 'Produksi',
                        supplier: 'PT. ABC',
                        tanggal_diminta: '2020-01-01',
                        estimasi_kedatangan: '2020-01-01',
                        status: 'Dalam Pemeriksaan Gudang',
                    },
                    {
                        no_po: 'PO-002',
                        divisi: 'Produksi',
                        supplier: 'PT. ABC',
                        tanggal_diminta: '2020-01-01',
                        estimasi_kedatangan: '2020-01-01',
                        status: 'Menunggu Kedaatangan',
                    }
                ],
                selectTable: [],
                search: '',
                checkFilter: [],

                modal: false,
            }
        },
        components: {
            Table,
            Tambah
        },
        methods: {
            checkStatus(value){
                if(this.checkFilter.includes(value)){
                    this.checkFilter = this.checkFilter.filter(item => item !== value)
                }else{
                    this.checkFilter.push(value)
                }
            },

            tambahPengecekan(){
                if (this.selectTable.length > 0) {
                    this.modal = true
                    this.$nextTick(() => {
                        $('.modalBarangMasuk').modal('show')
                    })
                } else {
                    this.$swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Silahkan pilih data terlebih dahulu!',
                    })
                }
            },

            closeModalTambah() {
                this.modal = false
                $('.modalBarangMasuk').modal('hide')
            },
        },
        computed: {
            uniqueStatus() {
                const status = this.dataTable.map((data) => data.status)
                return [...new Set(status)]
            },
            filteredTable() {

                let filtered = this.checkFilter.length > 0 ? this.dataTable.filter((data) => this.checkFilter.includes(data.status)) : this.dataTable

                return filtered.filter((data) => {
                    return Object.keys(data).some((key) => {
                        return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                    })
                })
            },
        }
    }

</script>
<template>
    <div>
        <Tambah v-if="modal" @close="closeModalTambah" :selectTable="selectTable"/>
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight">
                <button class="btn btn-primary" @click="tambahPengecekan">
                    <i class="fas fa-plus"></i>
                    Tambah Jadwal Pengecekan
                </button>
                <div class="btn-group">
                    <button class="btn btn-outline-info dropdown-toggle" type="button" id="triggerId"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                        Filter
                    </button>
                    <div class="dropdown-menu" aria-labelledby="triggerId">
                        <div class="px-3 py-3">
                                                            <div class="form-group">
                                    <label for="jenis_penjualan">Status</label>
                                </div>
                                <div class="form-group" v-for="status in uniqueStatus" :key="status">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" @click="checkStatus(status)">
                                        <label for="status" class="form-check-label text-uppercase">
                                            {{status}}
                                        </label>
                                    </div>
                                </div>
                            <div class="form-group">
                                <label for="jenis_penjualan">Tanggal PO</label>
                            </div>
                            <div class="form-group"><label for="">Mulai Tanggal</label><input type="date"
                                    class="form-control"></div>
                            <div class="form-group"><label for="">Hingga Tanggal</label><input type="date"
                                    class="form-control"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari">
            </div>
        </div>
        <Table :dataTable="filteredTable" :selectTable="selectTable" />
    </div>
</template>
