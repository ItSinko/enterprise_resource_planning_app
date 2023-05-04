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
                dataTable: [{
                    no_permintaan: 'P-001',
                    tujuan: 'Gudang',
                    tgl_permintaan: '2020-01-01',
                    tgl_dibutuhkan: '2020-01-01',
                }],
                search: '',
                renderPaginate: [],
            }
        },
        methods: {
            updateFilteredDalamProses(data) {
                this.renderPaginate = data
            },
            detail(id) {
                this.$router.push({
                    name: 'permintaanbahanbaku.detail',
                    params: {
                        id: id
                    }
                })
            },
        },
        computed: {
            filteredRiwayat() {
                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "-";
                return this.dataTable.filter((riwayat) => {
                    return Object.keys(riwayat).some((key) => {
                        return String(dataIsNotNull(riwayat[key])).toLowerCase().includes(this.search
                            .toLowerCase())
                    })
                })
            },
        },
    }

</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input v-model="search" type="text" class="form-control" placeholder="Cari" />
            </div>
        </div>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Permintaan</th>
                    <th>Tujuan</th>
                    <th>Tgl Permintaan</th>
                    <th>Tgl Dibutuhkan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in renderPaginate" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_permintaan }}</td>
                    <td>
                        <status :status="data.tujuan" />
                    </td>
                    <td>{{ formatTanggal(data.tgl_permintaan) }}</td>
                    <td>{{ formatTanggal(data.tgl_dibutuhkan) }}</td>
                    <td>
                        <i class="fas fa-eye text-info" @click="detail(data.id)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
        <Pagination :filteredDalamProses="filteredRiwayat" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>
