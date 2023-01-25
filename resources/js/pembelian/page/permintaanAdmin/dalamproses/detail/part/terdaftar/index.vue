<script>
    import lodash from 'lodash'
    import axios from 'axios'
    export default {
        data() {
            return {
                dataTable: [{
                        kode: 'P-0001',
                        nama: 'Part 1',
                        produk: 'Produk 1',
                        kurs: 'EUR',
                        jumlah: 10,
                        harga: 50,
                        ongkir: 50,
                        lain: 50,
                        status: 'belum proses',
                    },
                    {
                        kode: 'P-0002',
                        nama: 'Part 2',
                        produk: 'Produk 1',
                        kurs: 'AUD',
                        jumlah: 20,
                        harga: 100,
                        ongkir: 100,
                        lain: 100,
                        status: 'selesai',
                    },
                    {
                        kode: 'P-0003',
                        nama: 'Part 3',
                        produk: 'Produk 2',
                        kurs: 'USD',
                        jumlah: 30,
                        harga: 150,
                        ongkir: 150,
                        lain: 150,
                        status: 'belum proses',
                    }
                ],
                link: 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies',
                showTable: false,
            }
        },
        methods: {
            formatHarga(price, kurs) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: kurs
                }).format(price)
            },
            subtotal(item) {
                return item.jumlah * item.harga + item.ongkir + item.lain
            },
            changeToRupiah(item) {
                const subtotal = this.subtotal(item)
                const kurs = item.kurs

                const convert = (price, kurs) => {
                    let kursToRupiah = 0
                    try {
                        axios.get(`${this.link}/${kurs.toLowerCase()}/idr.json`).then((response) => {
                            kursToRupiah = response.data.idr
                            this.showTable = true
                        })
                    } catch (error) {
                        console.log(error)
                    }
                    return price * kursToRupiah
                }
                return this.dataTable.map((item) => {
                    return this.formatHarga(convert(subtotal, kurs), 'IDR')
                })
                
            }
        },

        computed: {
            groupedData() {
                return lodash.groupBy(this.dataTable, 'produk')
            }
        },
    }

</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><input type="checkbox"></th>
                    <th>Kode & Nama Part</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Ongkir</th>
                    <th>Biaya Lain</th>
                    <th>Subtotal</th>
                    <th>Konversi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, index) in groupedData">
                    <tr class="table-active">
                        <td colspan="9" class="text-bold text-capitalize">{{ index }}</td>
                    </tr>
                    <template v-for="(item2) in item">
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>{{ item2.kode }} - {{ item2.nama }}</td>
                            <td>{{ item2.jumlah }}</td>
                            <td>{{ formatHarga(item2.harga, item2.kurs) }}</td>
                            <td>{{ formatHarga(item2.ongkir, item2.kurs) }}</td>
                            <td>{{ formatHarga(item2.lain, item2.kurs) }}</td>
                            <td>{{ formatHarga(subtotal(item2), item2.kurs) }}</td>
                            <td>{{ changeToRupiah(item2) }}</td>
                            <td>{{ item2.status }}</td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
    </div>
</template>
