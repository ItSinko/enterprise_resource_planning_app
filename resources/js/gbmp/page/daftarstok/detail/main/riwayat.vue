<script>
export default {
    data() {
        return {
            search: '',
            dataTable: [
                {
                    tgl_transaksi: '2020-01-01',
                    divisi: 'Divisi 1',
                    jumlah_part: 100,
                    jenis: 'kedatangan',
                    keterangan: 'Pengembalian',
                },
                {
                    tgl_transaksi: '2020-01-02',
                    divisi: 'Divisi 2',
                    jumlah_part: 200,
                    jenis: 'pengurangan',
                    keterangan: 'Permintaan',
                }
            ]
        }
    },
    methods: {
        jenisPart(data) {
            if (data.jenis == 'kedatangan') {
                return `<span class="text-success">+ ${data.jumlah_part}</span>`
            } else if (data.jenis == 'pengurangan') {
                return `<span class="text-danger">- ${data.jumlah_part}</span>`
            }
        }
    },
    computed: {
        filteredDataTable() {
            return this.dataTable.filter(data => {
                return Object.keys(data).some(key => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" class="form-control" v-model="search" placeholder="Cari">
            </div>
        </div>

        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Tanggal Transaksi</th>
                    <th>Divisi</th>
                    <th>Jumlah Part</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in filteredDataTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ formatTanggal(data.tgl_transaksi) }}</td>
                    <td>{{ data.divisi }}</td>
                    <td v-html="jenisPart(data)"></td>
                    <td>{{ data.keterangan }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>