<script>
import DataTable from '../../../../components/DataTable.vue';
export default {
    components: {
        DataTable,
    },
    props: ['dataTable'],
    data() {
        return {
            headers: [
                { text: 'No', value: 'no' },
                { text: 'Nama', value: 'nama' },
                { text: 'Bagian', value: 'bagian', sortable: false },
                { text: 'Tanggal Pengajuan', value: 'tanggal', sortable: false },
                { text: 'Jenis Pelaporan', value: 'jenis' },
                { text: 'Aksi', value: 'aksi' },
            ],
            search: '',
            tanggalAwal: '',
            tanggalAkhir: '',
            bagianFilter: [],
        }
    },
    methods: {
        detail(item) {
            this.$router.push({
                name: 'detail-pengajuan',
                params: {
                    id: item.id,
                    // bawa link url saat ini
                    link: this.$route.fullPath,
                }
            })
        },
        renderNo(data) {
            return data.map((item, index) => {
                return {
                    ...item,
                    no: index + 1,
                }
            })
        }
    },
    computed: {
        filterData() {
            let filtered = this.renderNo(this.dataTable);

            if (this.tanggalAwal && this.tanggalAkhir) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_pengajuan)
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate && date <= endDate
                }))
            } else if (this.tanggalAwal) {
                const startDate = new Date(this.tanggalAwal);
                startDate.setHours(0, 0, 0, 0);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_pengajuan)
                    date.setHours(0, 0, 0, 0);
                    return date >= startDate
                }))
            } else if (this.tanggalAkhir) {
                const endDate = new Date(this.tanggalAkhir);
                endDate.setHours(23, 59, 59, 999);

                filtered = this.renderNo(filtered.filter(data => {
                    const date = new Date(data.tgl_pengajuan)
                    date.setHours(23, 59, 59, 999);
                    return date <= endDate
                }))
            }

            if (this.bagianFilter.length > 0) {
                filtered = this.renderNo(filtered.filter(data => {
                    return this.bagianFilter.includes(data.bagian)
                }))
            }

            return filtered;
        },
        getAllBagianUnique() {
            const bagian = this.dataTable.map(item => item.bagian);
            return [...new Set(bagian)];
        }
    }
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari..." />
            </div>
        </div>
        <DataTable :headers="headers" :items="filterData" :search="search">
            <template #header.tanggal>
                <span class="text-bold pr-2">Tanggal Pengajuan</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Awal</label>
                                            <input type="date" class="form-control" v-model="tanggalAwal"
                                                :max="tanggalAkhir">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="jenis_penjualan">Tanggal Akhir</label>
                                            <input type="date" class="form-control" v-model="tanggalAkhir"
                                                :min="tanggalAwal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>
            <template #header.bagian>
                <span class="text-bold pr-2">Bagian</span>
                <span class="filter">
                    <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-filter"></i>
                    </a>
                    <form id="filter_ekat">
                        <div class="dropdown-menu">
                            <div class="px-3 py-3">
                                <div class="form-group font-weight-normal">
                                    <label for="">Bagian</label>
                                    <v-select :options="getAllBagianUnique" v-model="bagianFilter" multiple></v-select>
                                </div>
                            </div>
                        </div>
                    </form>
                </span>
            </template>

            <template #item.no="{ item, index }">
                <div>
                    {{ index + 1 }}
                </div>
            </template>

            <template #item.aksi="{ item }">
                <div>
                    <button class="btn btn-outline-info btn-sm">
                        <i class="fas fa-detail-info"></i>
                        Detail
                    </button>
                </div>
            </template>


        </DataTable>
    </div>
</template>