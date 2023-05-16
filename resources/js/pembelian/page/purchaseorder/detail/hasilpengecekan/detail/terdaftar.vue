<script>
export default {
    data() {
        return {
            dataTable: [
                {
                    image: 'https://picsum.photos/200/300',
                    nama_part: 'Part 1',
                    gudang: {
                        dipesan: 10,
                        diterima: 10,
                        keterangan: 'Keterangan 1',
                    },
                    qc_incoming: {
                        sample: 5,
                        ok: 5,
                        not_ok: 0,
                        permasalahan: 'Permasalahan 1',
                        keterangan: 'Keterangan 1',
                        tindakan_lanjut: 'Tindakan Lanjut 1',
                    }
                }
            ],
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
    <label for="">
        Cari Part
    </label>
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
                    <td><img :src="item.image" width="30"></td>
                    <td>{{ item.nama_part }}</td>
                    <td>{{ item.gudang.dipesan }}</td>
                    <td>{{ item.gudang.diterima }}</td>
                    <td>{{ item.gudang.keterangan }}</td>
                    <td>{{ item.qc_incoming.sample }}</td>
                    <td>{{ item.qc_incoming.ok }}</td>
                    <td>{{ item.qc_incoming.not_ok }}</td>
                    <td>{{ item.qc_incoming.permasalahan }}</td>
                    <td>{{ item.qc_incoming.keterangan }}</td>
                    <td>{{ item.qc_incoming.tindakan_lanjut }}</td>
                    
                    <td>
                        <!-- <button class="btn btn-sm btn-primary" @click="edit(item)">Edit</button>
                        <button class="btn btn-sm btn-danger" @click="hapus(item)">Hapus</button> -->
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>