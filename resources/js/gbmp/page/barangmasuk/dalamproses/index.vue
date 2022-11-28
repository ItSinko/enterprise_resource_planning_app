<script>
    import Table from './table.vue'
    import Tambah from './tambah.vue'
    import Loading from '../../../components/loading.vue'
    export default {
        components: {
            Table,
            Tambah,
            Loading
        },
        data() {
            return {
                dalamProses: [
                    {
                    no_po: 'PO-001',
                    divisi: 'Divisi 1',
                    supplier: 'Supplier 1',
                    tanggal_diminta: '2020-01-01',
                    estimasi_datang: '2020-01-01',
                    status: 'Barang Datang',
                    }, 
                    {
                    no_po: 'PO-002',
                    divisi: 'Divisi 2',
                    supplier: 'Supplier 2',
                    tanggal_diminta: '2020-01-01',
                    estimasi_datang: '2020-01-01',
                    status: 'Menunggu Kedatangan',
                    }],
                proses_selected: [],
                search: '',
                loading: false,
            }
        },
        methods: {
            checked(idx) {
                this.dalamProses.find((proses, index) => {
                    if (index === idx) {
                        if (this.proses_selected.includes(proses)) {
                            this.proses_selected.splice(this.proses_selected.indexOf(proses), 1)
                        } else {
                            this.proses_selected.push(proses)
                        }
                    }
                })
            },
            tambah(){
                $('.modalAddProses').modal('show')
            },
            deleteProses(idx, po) {
                this.loading = true
                this.proses_selected.splice(idx, 1)
                this.$refs.table.$refs.proses.find((proses, index) => {
                    if (proses._value == po) {
                        proses.checked = false
                    }
                })
                this.loading = false
            },
        },
    }
</script>
<template>
    <div>
        <Loading v-if="loading" />
        <div class="card" v-else>
            <div class="card-body">
                <div class="card-text">
                    <div class="d-flex bd-highlight mb-3">
                        <div class="p-2 bd-highlight">
                            <button class="btn btn-primary" @click="tambah">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                                Tambah Jadwal Penerimaan
                            </button>
                        </div>
                        <div class="p-2 bd-highlight">
                            <button type="button" class="btn btn-outline-info" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                        <div class="ml-auto p-2 bd-highlight">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Cari Barang Masuk"
                                    v-model="search">
                            </div>
                        </div>
                    </div>
                    <Table :dalamProses="dalamProses" @checked="checked" ref="table"></Table>
                </div>
            </div>
        </div>
        <Tambah :addProses="proses_selected" @deleteProses="deleteProses"/>
    </div>
</template>
