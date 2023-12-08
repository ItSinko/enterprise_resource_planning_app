<script>
import DataTable from '../../../../components/DataTable.vue';
import Tambah from '../detail/tambah.vue';
export default {
    components: {
        DataTable,
        Tambah
    },
    data() {
        return {
            headers: [
                { text: 'No', value: 'no' },
                { text: 'No Seri', value: 'noseri' },
                { text: 'Tanggal Dibuat', value: 'tgl_buat', sortable: false },
                { text: 'Packer', value: 'packer', sortable: false },
                { text: 'Aksi', value: 'aksi', align: 'text-left' }
            ],
            search: '',
            dataTable: [{
                no: 1,
                noseri: '123456789',
                tgl_buat: '23 September 2020',
                packer: 'Packer 1',
            }],
            tanggalAwal: '',
            tanggalAkhir: '',
            filterPacker: [],
            showModal: false,
        }
    },
    methods: {
        clickFilterPacker(packer) {
            if (this.filterPacker.includes(packer)) {
                this.filterPacker = this.filterPacker.filter(item => item !== packer)
            } else {
                this.filterPacker.push(packer)
            }
        },
                openModalCreate() {
            this.showModal = true
            this.$nextTick(() => {
                $('.modalGenerate').modal('show')
            })
        },
    },
    computed: {
        getAllPackerUnique() {
            return [...new Set(this.dataTable.map(item => item.packer))];
        }
    }
}
</script>
<template>
    <div>
        <Tambah v-if="showModal" @closeModal="showModal = false" />
        <div class="d-flex bd-highlight">
            <div class="p-2 flex-grow-1 bd-highlight"><button class="btn btn-outline-primary" @click="openModalCreate">
                    <i class="fas fa-plus"></i>
                    Tambah
                </button></div>
            <div class="p-2 bd-highlight"> <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <DataTable :headers="headers" :items="dataTable" :search="search">
            <template #header.tgl_buat>
                <span class="text-bold pr-2">Tanggal Dibuat</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group"><label for="">Tanggal Awal</label><input type="date"
                                                v-model="tanggalAwal" :max="tanggalAkhir" class="form-control"></div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group"><label for="">Tanggal Akhir</label><input type="date"
                                                v-model="tanggalAkhir" :min="tanggalAwal" class="form-control"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>
            <template #header.packer>
                <span class="text-bold pr-2">Packer</span>
                <span class="filter"><a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div :class="getAllPackerUnique.length > 5 ? 'scrollable' : ''">
                                    <div class="form-group" v-for="packer in getAllPackerUnique" :key="packer">
                                        <div class="form-check"><input type="checkbox" :ref="packer" :value="packer"
                                                class="form-check-input" @click="clickFilterPacker(packer)" />
                                            <label for="" class="form-check-label text-uppercase font-weight-normal">{{
                                                packer }}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>
            <template #item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-info">
                    <i class="fas fa-info-circle"></i>
                    Detail No. Seri Produk
                </button>
                <br>
                <button class="btn btn-sm btn-outline-info my-1">
                    <i class="fas fa-print"></i>
                    Lihat No. Seri
                </button>
                <button class="btn btn-sm btn-outline-primary my-1">
                    <i class="fas fa-print"></i>
                    Cetak No. Seri
                </button>
                <br>
                <button class="btn btn-sm btn-outline-info my-1">
                    <i class="fas fa-eye"></i>
                    Lihat Packing List
                </button>
                <button class="btn btn-sm btn-outline-primary my-1">
                    <i class="fas fa-print"></i>
                    Cetak Packing List
                </button>
            </template>
        </DataTable>
    </div>
</template>