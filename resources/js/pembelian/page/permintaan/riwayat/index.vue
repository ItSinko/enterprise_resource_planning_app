<script>
import moment from 'moment';
export default {
    data() {
        return {
            dataTable: [
                {
                    no_pp : 'PP-0001',
                    jenis_barang: 'Umum',
                    tanggal_diminta: '2020-01-01',
                    tanggal_dibutuhkan: '2020-01-01',
                    tanggal_selesai: '2020-01-01',
                    dokumen: 'link_to_file',
                },
                {
                    no_pp : 'PP-0002',
                    jenis_barang: 'Umum',
                    tanggal_diminta: '2020-01-01',
                    tanggal_dibutuhkan: '2020-01-01',
                    tanggal_selesai: '2020-01-01',
                    dokumen: 'link_to_file',
                },
            ]
        }
    },
    methods: {
        moment(date) {
            return moment(date).lang('id').format('LL')
        },
    }
}
</script>
<template>
    <div>
        <table class="table tablePermintaan">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No PP</th>
                    <th>Jenis Barang</th>
                    <th>Tanggal Diminta</th>
                    <th>Tanggal Dibutuhkan</th>
                    <th>Tanggal Selesai</th>
                    <th>Dokumen</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_pp }}</td>
                    <td>{{ data.jenis_barang }}</td>
                    <td>{{ moment(data.tanggal_diminta) }}</td>
                    <td>{{ moment(data.tanggal_dibutuhkan) }}</td>
                    <td>{{ moment(data.tanggal_selesai) }}</td>
                    <td>
                        <a :href="data.dokumen" target="_blank" class="btn-sm btn btn-warning">
                            <i class="fa fa-file pr-3" aria-hidden="true"></i> PP
                        </a>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary">
                            <i class="fa fa-eye" aria-hidden="true"></i>
                            Detail
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>