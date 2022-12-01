<script>
    import Table from './table.vue'
    import klasifikasi from './klasifikasipart'
    export default {
        components: {
            Table,
            klasifikasi
        },
        props: {
            dataTable: {
                type: Array,
                default: () => []
            },
            headers : {
                type: Object,
                default: () => {}
            },
        },
        data() {
            return {
                search: '',
                partsSelected: [],
            }
        },
        methods: {
            checkedPartsNotTerdaftar(data) {
                this.partsSelected = data
            },
            klasifikasiPart() {
                $('.modalKlasifikasi').modal('show')
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
        <div class="d-flex">
            <div class="mr-auto p-2">
                <button class="btn btn-warning" @click="klasifikasiPart">Klasifikasi Part</button>
            </div>
            <div class="p-2">
                <input type="text" class="form-control" v-model="search" placeholder="Cari Part">
            </div>
        </div>
        <Table :dataTable="filteredData" @checked="checkedPartsNotTerdaftar"/>
        <klasifikasi :dataTable="partsSelected" :headers="headers"/>
    </div>
</template>
