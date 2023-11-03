<script>
import pagination from './pagination.vue';
export default {
    components: {
        pagination,
    },
    props: {
        headers: {
            type: Array,
            required: true,
        },
        items: {
            type: Array,
            required: true,
        },
        search: {
            type: String,
            required: false,
            default: '',
        },
        scrollable: {
            type: Boolean,
            required: false,
            default: false,
        },
    },
    data() {
        return {
            renderPaginate: [],
            sortColumn: 'no_urut',
            sortDirection: 'asc',
        }
    },
    methods: {
        updateFilteredDalamProses(data) {
            this.renderPaginate = data;
        },
        sort(header) {
            if (header.sortable == false) return;
            const column = header.value
            if (column === this.sortColumn) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc'
            } else {
                this.sortColumn = column
                this.sortDirection = 'asc'
            }
        }
    },
    computed: {
        filteredDalamProses() {
            return this.sortedDataTable.filter((data) => {
                return Object.keys(data).some((key) => {
                    return String(data[key]).toLowerCase().includes(this.search.toLowerCase());
                });
            });
        },
        sortedDataTable() {
            const sorted = this.items.sort((a, b) => {
                const modifier = this.sortDirection === 'asc' ? 1 : -1
                if (a[this.sortColumn] < b[this.sortColumn]) return -1 * modifier
                if (a[this.sortColumn] > b[this.sortColumn]) return 1 * modifier
                return 0
            })
            return sorted
        }
    },
}
</script>
<template>
    <div>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" v-for="header in headers" :key="header.text"
                            :class="header.align ? header.align : 'text-center'" @click="sort(header)"
                            :sortable="header.sortable == false ? false : true">
                            <slot :name="`header.${header.value}`">
                                {{ header.text }}
                            </slot>
                            <span v-if="sortColumn === header.value">
                                <i v-if="sortDirection === 'asc'" class="fas fa-arrow-up"></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody v-if="renderPaginate.length > 0">
                    <!-- sesuaikan dengan header -->
                    <tr v-for="(data, idx) in renderPaginate" :key="idx">
                        <td v-for="header in headers" :key="header.value"
                            :class="header.align ? header.align : 'text-center'">
                            <slot :name="`item.${header.value}`" :item="data" :index="idx">
                                {{ data[header.value] }}
                            </slot>
                        </td>
                    </tr>
                </tbody>
                <tbody v-else>
                    <tr>
                        <td colspan="100%" class="text-center">Tidak ada data</td>
                    </tr>
                </tbody>
            </table>
        <pagination :filteredDalamProses="filteredDalamProses" @updateFilteredDalamProses="updateFilteredDalamProses" />
    </div>
</template>