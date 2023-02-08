<script>
import moment from 'moment';
import status from '../../../../../components/status.vue';

export default {
    components: {
        status
    },
    data() {
        return {
            pengembalian: [
                {
                    no_pengembalian: 'P-001',
                    tgl_pengembalian: '2020-01-01',
                    status: 'terima pengembalian',
                },
                {
                    no_pengembalian: 'P-002',
                    tgl_pengembalian: '2020-01-01',
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
        filteredPengembalian() {
            return this.pengembalian.filter(pengembalian => {
                return Object.keys(pengembalian).some(key => {
                    return String(pengembalian[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="form-group"><input type="search" class="form-control" placeholder="cari" v-model="search"></div>
        </div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Pengembalian</th>
                    <th>Tgl Pengembalian</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(pengembalian, index) in filteredPengembalian" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ pengembalian.no_pengembalian }}</td>
                    <td>{{ formatTanggal(pengembalian.tgl_pengembalian) }}</td>
                    <td><status :status="pengembalian.status"></status></td>
                    <td>
                        <i class="fas fa-eye text-info"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>