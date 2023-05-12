<script>
    import lodash from 'lodash'
    import Status from '../../../../../../components/status.vue'
    export default {
        components: {
            Status
        },
        props: {
            dataTable: {
                type: Array,
                default: () => []
            }
        },
        data() {
        return {
                link: 'https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies',
                showTable: false,
                dataSelected: [],
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
            checkAll() {
                if (this.dataSelected.length == this.dataTable.length) {
                    this.dataSelected = []
                    this.$refs.checkAll.checked = false
                    this.$refs.cbChild.forEach((item) => {
                        item.checked = false
                    })
                } else {
                    this.dataSelected = this.dataTable.map((item) => item)
                    this.$refs.checkAll.checked = true
                    this.$refs.cbChild.forEach((item) => {
                        item.checked = true
                    })
                }
                this.$emit('dataSelected', this.dataSelected)
            },
            checkOne(id){
                let index = this.dataTable.findIndex(item => item.id == id)
                if(this.$refs.cbChild[index].checked){
                    this.dataSelected.push(this.dataTable[index])
                }else{
                    this.dataSelected = this.dataSelected.filter(data => data.id != this.dataTable[index].id)
                }
                this.$emit('dataSelected', this.dataSelected)
            }
        },
        updated() {
            this.showTable = true
        },

        computed: {
            groupedData() {
                return lodash.groupBy(this.dataTable, 'produk')
            }
        },
        watch: {
            dataSelected() {
                if (this.dataSelected.length == this.dataTable.length) {
                    this.$refs.checkAll.checked = true
                } else {
                    this.$refs.checkAll.checked = false
                }
            }
        }
    }

</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th><input type="checkbox" ref="checkAll" @click="checkAll"></th>
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
                            <td><input type="checkbox" :value="item2.id" ref="cbChild" @click="checkOne(item2.id)"></td>
                            <td>{{ item2.kode }} - {{ item2.nama }}</td>
                            <td>{{ item2.jumlah }}</td>
                            <td>{{ formatHarga(item2.harga, item2.kurs) }}</td>
                            <td>{{ formatHarga(item2.ongkir, item2.kurs) }}</td>
                            <td>{{ formatHarga(item2.lain, item2.kurs) }}</td>
                            <td>{{ formatHarga(subtotal(item2), item2.kurs) }}</td>
                            <td>{{ formatHarga(item2.konversi, 'idr') }}</td>
                            <td><status :status="item2.status" /></td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
    </div>
</template>
