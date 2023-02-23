<script>
    export default {
        props: {
            selectTable: {
                type: Array,
                default: () => []
            },
            formPeriode: {
                type: Object,
                default: () => {}
            }
        },
        computed: {
            minMaxDateFormPeriode() {
                const {
                    month,
                    years
                } = this.formPeriode
                const monthIndex = !month ? null : moment().month(month).year(years).month();
                const daysInMonth = !month ? null : moment().month(monthIndex).year(years).daysInMonth()
                const minDate = !month && !years ? null : moment().month(monthIndex).year(years).date(1).format(
                    'YYYY-MM-DD')
                const maxDate = !month && !years ? null : moment().month(monthIndex).year(years).date(daysInMonth)
                    .format('YYYY-MM-DD')
                return {
                    minDate,
                    maxDate
                }
            }
        },
        methods: {
            deleteTable(index) {
                this.$emit('deleteTable', index)
            },
        },
    }

</script>
<template>
    <div>
        <table class="table text-center">
            <thead class="thead-light">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">No Purchase Order</th>
                    <th rowspan="2">Supplier</th>
                    <th rowspan="2">Estimasi Kedatangan</th>
                    <th colspan="2">Penerimaan</th>
                    <th rowspan="2">Aksi</th>
                </tr>
                <tr>
                    <th>Jadwal Mulai</th>
                    <th>Jadwal Selesai</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in selectTable" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ item.no_po }}</td>
                    <td>{{ item.supplier }}</td>
                    <td>{{ formatTanggal(item.estimasi_kedatangan) }}</td>
                    <td><input type="date" v-model="item.jadwal_mulai" 
                            class="form-control"
                            :disabled="!formPeriode.month && !formPeriode.years"
                            :min="minMaxDateFormPeriode.minDate" 
                            :max="minMaxDateFormPeriode.maxDate"></td>
                    <td>
                        <input type="date" v-model="item.jadwal_selesai" 
                            class="form-control"
                            :min="minMaxDateFormPeriode.minDate" 
                            :max="minMaxDateFormPeriode.maxDate"
                            :disabled="!formPeriode.month && !formPeriode.years"
                            >
                    </td>
                    <td>
                        <i class="fa fa-minus text-danger" 
                        aria-hidden="true" 
                        @click="deleteTable(index)"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
