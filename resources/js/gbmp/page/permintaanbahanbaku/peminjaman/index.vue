<script>
    import status from '../../../components/status.vue';
    import Pagination from '../../../components/pagination.vue';

    export default {
        components: {
            status,
            Pagination,
        },
        data() {
            return {
                search: '',
                dataTable: [{
                        no_permintaan: 'P-001',
                        divisi: 'Gudang',
                        tgl_permintaan: '2020-01-01',
                        tgl_dibutuhkan: '2020-01-01',
                        batas_pengembalian: '2023-02-14',
                        status: 'selesai',
                    },
                    {
                        no_permintaan: 'P-002',
                        divisi: 'Gudang',
                        tgl_permintaan: '2020-01-01',
                        tgl_dibutuhkan: '2020-01-01',
                        batas_pengembalian: '2023-03-01',
                        status: 'selesai',
                        persentase: 50,
                    }
                ],
                renderPaginate: [],
            }
        },
        methods: {
            updateFilteredDalamProses(data) {
                this.renderPaginate = data
            },
            cekPengembalian(data) {
                const tglPengembalian = new Date(data);
                const tglSekarang = new Date();
                const selisihHari = Math.ceil((tglPengembalian - tglSekarang) / (1000 * 3600 * 24)) * -1;

                if (tglPengembalian > tglSekarang) {
                    return this.formatTanggal(data);
                } else {
                    return `
                        <p class="text-danger text-bold">${this.formatTanggal(data)}</p>
                        <span class="text-danger">Batas Pengembalian Lebih ${selisihHari} Hari</span>
                    `;
                }
            },
        },
        computed: {
            filteredPeminjaman(){
                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
                return this.dataTable.filter((peminjaman) => {
                    return Object.keys(peminjaman).some((key) => {
                        return String(dataIsNotNull(peminjaman[key])).toLowerCase().includes(this.search
                            .toLowerCase())
                    })
                })
            }
        }
    }

</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input v-model="search" type="text" class="form-control" placeholder="Cari" />
            </div>
        </div>

        <table class="table text-center">
            <thead class="thead-light ">
                <tr>
                    <th>No</th>
                    <th>No Permintaan</th>
                    <th>Divisi</th>
                    <th>Tanggal Permintaan</th>
                    <th>Tanggal Dibutuhkan</th>
                    <th>Batas Pengembalian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in renderPaginate" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_permintaan }}</td>
                    <td>{{ data.divisi }}</td>
                    <td>{{ formatTanggal(data.tgl_permintaan) }}</td>
                    <td>{{ formatTanggal(data.tgl_dibutuhkan) }}</td>
                    <td v-html="cekPengembalian(data.batas_pengembalian)">

                    </td>
                    <td>
                        <status :status="data.status" :persentase="data.persentase" />
                    </td>
                    <td>
                        <i class="fa fa-eye text-info" aria-hidden="true"></i>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination :filteredDalamProses="filteredPeminjaman" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>
