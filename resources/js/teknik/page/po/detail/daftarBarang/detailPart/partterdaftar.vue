<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        },
    },
    data() {
        return {
            search: '',
        }
    },
    computed: {
        filteredData() {
            const dataIsNotNull = (data) => data !== null && data !== undefined && data !== "" ? data : "";
            return this.dataTable.filter((row) => {
                return Object.keys(row).some((key) => {
                    return String(dataIsNotNull(row[key])).toLowerCase().includes(this.search.toLowerCase())
                })
            })
        }
    }
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse">
  <div class="p-2">
    <input type="text" class="form-control" v-model="search">
  </div>
</div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2"></th>
                    <th rowspan="2">Nama Part</th>
                    <th rowspan="2">Dipesan</th>
                    <th colspan="2">Gudang</th>
                    <th colspan="6">QC Incoming</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Diterima</th>
                    <th>Keterangan</th>
                    <th>Sample</th>
                    <th>OK</th>
                    <th>NOK</th>
                    <th>Permasalahan</th>
                    <th>Keterangan</th>
                    <th>Tindak Lanjut</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in filteredData" :key="index">
                    <td><img src="https://picsum.photos/20" alt=""></td>
                    <td>{{ item.nama_part }}</td>
                    <td>{{ item.dipesan }}</td>
                    <td>{{ item.diterima }}</td>
                    <td>{{ item.keterangan }}</td>
                    <td>{{ item.sample }}</td>
                    <td>{{ item.ok }}</td>
                    <td>{{ item.nok }}</td>
                    <td>{{ item.permasalahan }}</td>
                    <td>{{ item.keterangan }}</td>
                    <td>{{ item.tindak_lanjut }}</td>
                    <td>
                        <!-- <button class="btn btn-sm btn-primary" @click="edit(item)">Edit</button>
                        <button class="btn btn-sm btn-danger" @click="hapus(item)">Hapus</button> -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>