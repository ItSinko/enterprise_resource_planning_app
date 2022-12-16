<script>
    import inputprice from '../../../../../../components/inputprice.vue'
    import mix from '../../../../../../mix'
    import mixpart from './mix.js'
    export default {
        mixins: [mix, mixpart],
        props: {
            dataTable: {
                type: Array,
                default: () => []
            },
        },
        data() {
            return {
                search: '',
                formPart: []
            }
        },
        components: {
            inputprice
        },
        methods: {
            checkedAll() {
                this.formPart != this.dataTable ? this.formPart = this.dataTable : this.formPart = []
                this.$refs.checked.forEach(item => {
                    item.checked = this.formPart.length > 0
                })
            },
            checked(idx) {
                if (this.formPart.find(item => item.id === this.dataTable[idx].id)) {
                    this.formPart.splice(this.formPart.indexOf(this.dataTable[idx]), 1)
                } else {
                    this.formPart.push(this.dataTable[idx])
                }
            },
        },
        computed: {
            filteredDatatables() {
                const dataIsNotNull = (data) => data !== null && data !== undefined && data !== ''
                return this.dataTable.filter(item => {
                    return (
                        dataIsNotNull(item.kode) && item.kode.toLowerCase().includes(this.search.toLowerCase()) ||
                        dataIsNotNull(item.nama) && item.nama.toLowerCase().includes(this.search.toLowerCase()) ||
                        dataIsNotNull(item.jumlah_per_set) && item.jumlah_per_set.toString().toLowerCase().includes(this.search.toLowerCase()) ||
                        dataIsNotNull(item.jumlah_kebutuhan) && item.jumlah_kebutuhan.toString().toLowerCase().includes(this.search.toLowerCase()) ||
                        dataIsNotNull(item.harga) && item.harga.toString().toLowerCase().includes(this.search.toLowerCase())
                    )
                })
            }
        }
    }

</script>
<template>
    <div>
        <div class="d-flex justify-content-end bd-highlight mb-3">
            <div class="p-2 bd-highlight"> 
                <input type="text" class="form-control" v-model="search" placeholder="Cari">
            </div>
        </div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>
                        <input type="checkbox" @click="checkedAll">
                    </th>
                    <th>
                        Kode & Nama Part
                    </th>
                    <th>
                        Jumlah Per Set
                    </th>
                    <th>
                        Jumlah Kebutuhan
                    </th>
                    <th>
                        Harga
                    </th>
                    <th>
                        Subtotal
                    </th>
                </tr>
            </thead>
            <tbody v-if="filteredDatatables.length > 0">
                <tr v-for="(item, idx) in filteredDatatables" :key="idx">
                    <td>
                        <input type="checkbox" ref="checked" @click="checked(idx)">
                    </td>
                    <td>
                        {{ item.kode }} - {{ item.nama }}
                    </td>
                    <td>
                        {{ item.jumlah_per_set }}
                    </td>
                    <td>
                        <input type="text" class="form-control" v-model="item.jumlah_kebutuhan" @keypress="onlyNumber($event)">
                    </td>
                    <td>
                        <inputprice :nilai="item.harga" v-model="item.harga" @keypress="onlyNumber($event)">
                        </inputprice>
                    </td>
                    <td>
                        {{ formatRupiah(subtotal(item.harga, item.jumlah_kebutuhan)) }}
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="6" class="text-center">
                        Tidak ada data
                    </td>
                </tr>
            </tbody>
        </table>
                <div class="d-flex flex-row-reverse bd-highlight">
            <nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item">
                        <a class="page-link" :disabled="currentPage == 1" @click="previousPage">Previous</a>
                    </li>
                    <li class="page-item" :class="paginate == currentPage ? 'active' : ''" v-for="paginate in pages"
                        :key="paginate">
                        <a class="page-link" @click="nowPage(paginate)">{{paginate}}</a>
                    </li>
                    <li class="page-item">
                        <a class="page-link" :disabled="currentPage == pages[pages.length - 1]"
                            @click="nextPage">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
