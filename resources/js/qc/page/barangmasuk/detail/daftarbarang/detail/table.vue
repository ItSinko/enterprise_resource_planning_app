<script>
export default {
    data() {
        return {
            dataTable: [
                {
                    image: 'https://picsum.photos/200/300',
                    nama_produk: 'Produk 1',
                    jumlah_dipesan: 10,
                    jumlah_diterima: 10,
                    jumlah_sampel: 10,
                    hasil_ok: 10,
                    hasil_nok: 10,
                    permasalahan: 'Permasalahan 1',
                    keterangan: 'Keterangan 1',
                    tindak_lanjut: 'Tindak Lanjut 1',
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="5"></th>
                    <th rowspan="5">Nama Produk</th>
                    <th colspan="3">Jumlah</th>
                    <th colspan="2">Hasil</th>
                    <th rowspan="5">Permasalahan</th>
                    <th rowspan="5">Keterangan</th>
                    <th rowspan="5">Tindak Lanjut</th>
                </tr>
                <tr>
                    <th>Dipesan</th>
                    <th >Diterima</th>
                    <th>Sampel</th>
                    <th>OK</th>
                    <th>NOK</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>
                        <img :src="data.image" alt="" width="25px">
                    </td>
                    <td>{{ data.nama_produk }}</td>
                    <td>{{ data.jumlah_dipesan }}</td>
                    <td>{{ data.jumlah_diterima }}</td>
                    <td>{{ data.jumlah_sampel }}</td>
                    <td>{{ data.hasil_ok }}</td>
                    <td>{{ data.hasil_nok }}</td>
                    <td>{{ data.permasalahan }}</td>
                    <td>{{ data.keterangan }}</td>
                    <td>{{ data.tindak_lanjut }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>