<script>
import moment from "moment";
export default {
    props: ['monthYears', 'dataTable'],
    methods: {
        isWeekend(date) {
            // check if the date is a Saturday or Sunday
            let dayOfWeek = moment(`${date}-${this.monthYears}`, "D-MMMM YYYY").format("dddd")
            return dayOfWeek === "Saturday" || dayOfWeek === "Sunday";
        },
        isInRange(date, data) {
            // check if the date is within the range of tanggal_mulai and tanggal_selesai
            let tanggalMulai = moment(data.tanggal_mulai, "YYYY-MM-DD");
            let tanggalSelesai = moment(data.tanggal_selesai, "YYYY-MM-DD");
            if (this.isWeekend(date)) {
                return false;
            }
            // set date with monthYears
            let dateWithMonthYears = moment(`${date}-${this.monthYears}`, "D-MMMM YYYY");
            // check if dateWithMonthYears is between tanggalMulai and tanggalSelesai
            return dateWithMonthYears.isBetween(tanggalMulai, tanggalSelesai, null, '[]');
            
        },
        edit(data) {
            this.$emit('edit', data)
        },
        hapus(data) {
            this.$emit('hapus', data)
        }
    },
    computed: {
        getDatesMonthNow() {
            // convert monthYears November 2023 to 11-2023
            let monthYearNow = moment(this.monthYears, "MMMM YYYY").format("MM-YYYY");
            let dates = [];
            // get dates only like 1,2,3
            for (let i = 1; i <= moment(monthYearNow, "MM-YYYY").daysInMonth(); i++) {
                dates.push(i);
            }
            return dates;
        },
    }

}
</script>
<template>
    <div class="table-container">
        <table class="table is-bordered has-text-centered" style="white-space: nowrap;">
            <thead>
                <tr>
                    <th rowspan="2">No Urut</th>
                    <th rowspan="2">Nama Produk</th>
                    <th rowspan="2">Jumlah</th>
                    <th rowspan="2">Aksi</th>
                    <th :colspan="getDatesMonthNow.length">Tanggal</th>
                </tr>
                <tr>
                    <th v-for="date in getDatesMonthNow" :key="date">{{ date }}</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.no_urut }}</td>
                    <td>{{ data?.produk_id.label }}</td>
                    <td>{{ data.jumlah }}</td>
                    <td>
                        <span>
                            <i class="fas fa-edit pointerHand" @click="edit(data)"></i>
                        </span>
                        &nbsp;
                        <span>
                            <i class="fas fa-trash pointerHand" @click="hapus(data)"></i>
                        </span>
                    </td>
                    <td v-for="date in getDatesMonthNow" :key="date"
                        :class="{ 'yellow-bg': isInRange(date, data), 'black-bg': isWeekend(date) }">
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="100%">Tidak ada data</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<style scoped>
.pointerHand {
    cursor: pointer;
}

.black-bg {
    background-color: black;
}

.yellow-bg {
    background-color: yellow;
}
</style>
                        