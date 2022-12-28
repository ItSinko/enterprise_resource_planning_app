<script>
import moment from 'moment'
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => {
                return []
            }
        },
    },
    data() {
        return {
            dataTableDummy: [
                {
                    id: 1,
                    no_po: 'PO-001',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'selesai',
                },
                {
                    id: 2,
                    no_po: 'PO-002',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'dalam proses',
                },
                {
                    id: 3,
                    no_po: 'PO-003',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'draft',
                },
                {
                    id: 4,
                    no_po: 'PO-004',
                    tanggal_po: '2020-01-01',
                    estimasi_kedatangan: '2020-01-01',
                    tanggal_kedatangan: '2020-01-01',
                    status: 'terima barang',
                }
            ]
        }
    },
    methods: {
        dateFormatIDR(value) {
            return moment(value).lang('id').format('DD MMMM YYYY')
        },
        generateStatusToHTML(status) {
            switch (status) {
                case 'selesai':
                    return `<span class="badge badge-success">${status}</span>`
                case 'dalam proses':
                    return `<span class="badge badge-warning">${status}</span>`
                case 'draft':
                    return `<span class="badge badge-danger">${status}</span>`
                default:
                    return `<span class="badge badge-danger">${status}</span>`
            }
        },
        del(id) {
            this.$emit('del', id)
        },
        terima(id) {
            this.$emit('terima', id)
        }
    }
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No PO</th>
                    <th>Tanggal PO</th>
                    <th>Estimasi Kedatangan</th>
                    <th>Tanggal Kedatangan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, key) in dataTableDummy" :key="key+ 'daftarpo'">
                    <td>{{ key+1 }}</td>
                    <td>{{ item.no_po }}</td>
                    <td>{{ dateFormatIDR(item.tanggal_po) }}</td>
                    <td>{{ dateFormatIDR(item.estimasi_kedatangan) }}</td>
                    <td>{{ dateFormatIDR(item.tanggal_kedatangan) }}</td>
                    <td>
                        <button class="btn btn-sm btn-success" v-if="item.status == 'terima barang'" @click="terima(item.id)">Terima Barang</button>
                        <span v-html="generateStatusToHTML(item.status)" v-else></span>
                    </td>
                    <td>
                        <i class="fa fa-trash red" aria-hidden="true" @click="del(item.id)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>