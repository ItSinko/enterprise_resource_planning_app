<script>
import Table from './table.vue'
export default {
    components: {
        Table
    },
    props: {
        riwayatPO: {
            type: Array,
            required: true
        },
    },
    data() {
        return {
            searchRiwayatPO: '',
            filterRiwayatPO: [],
        }
    },
    methods: {
        clickFilterRiwayatPO(filter) {
            if (this.filterRiwayatPO.includes(filter)) {
                this.filterRiwayatPO = this.filterRiwayatPO.filter(item => item !== filter)
            } else {
                this.filterRiwayatPO.push(filter)
            }
        },
    },
    computed: {
        filteredRiwayatPO() {
            let filtered = []
            if (this.filterRiwayatPO.length == 0) {
                filtered = this.riwayatPO
            } else {
                this.filterRiwayatPO.forEach(filter => {
                    filtered = filtered.concat(this.riwayatPO.filter(riwayatPO => riwayatPO
                        .status == filter))
                })
            }
            return filtered.filter((riwayatPO) => {
                return Object
            })
        },
        getAllStatusUnique() {
            return this.riwayatPO.map((riwayatPO) => {
                return riwayatPO.status
            }).filter((value, index, self) => {
                return self.indexOf(value) === index
            })
        },
    },
}
</script>
<template>
    <div>
                
    </div>
</template>