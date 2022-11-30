<script>
    import Table from './table.vue'
    export default {
        components: {
            Table
        },
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
        <div class="d-flex">
            <div class="mr-auto p-2">
                <button class="btn btn-warning">Klasifikasi Part</button>
            </div>
            <div class="p-2">
                <input type="text" class="form-control" v-model="search" placeholder="Cari Part">
            </div>
        </div>
        <Table :dataTable="filteredData" />
    </div>
</template>
