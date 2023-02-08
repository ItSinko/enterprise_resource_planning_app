<script>
import status from '../../../../../components/status.vue';
import moment from 'moment';
export default {
    components: {
        status
    },
    data() {
        return {
            penyerahan: [
                {
                    no_penyerahan: 'P-001',
                    tgl_penyerahan: '2020-01-01',
                    status: 'kirim barang',
                },
                {
                    no_penyerahan: 'P-002',
                    tgl_penyerahan: '2020-01-01',
                    status: 'selesai',
                }
            ],
            search: '',
        }
    },
    methods: {
        formatTanggal(tanggal) {
            return moment(tanggal).lang('id').format('LL');
        }
    },
    computed: {
        filteredPenyerahan() {
            return this.penyerahan.filter(penyerahan => {
                return Object.keys(penyerahan).some(key => {
                    return String(penyerahan[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    }
}
</script>
<template>
    <div>
        <div class="d-flex bd-highlight">
        <div class="p-2 flex-grow-1 bd-highlight">
            <button class="btn btn-info">
                <i class="fa fa-plus"></i>
                Buat Penyerahan
            </button>
        </div>
        <div class="p-2 bd-highlight">
            <div class="form-group"><input type="search" class="form-control" placeholder="cari" v-model="search"></div>
        </div>
        </div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Penyerahan</th>
                    <th>Tgl Penyerahan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(penyerahan, index) in filteredPenyerahan" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ penyerahan.no_penyerahan }}</td>
                    <td>{{ formatTanggal(penyerahan.tgl_penyerahan) }}</td>
                    <td>
                        <status :status="penyerahan.status"/>
                    </td>
                    <td>
                        <i class="fas fa-eye text-info"></i>
                        <i class="fas fa-edit text-warning"></i>
                        <i class="fas fa-trash text-danger"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>