<script>
    export default {
        props: {
            addProses: {
                type: Array,
                default: () => []
            },
        },
        data() {
            return {
                month : [{
                    value: '01',
                    label: 'Januari'
                },
                {
                    value: '02',
                    label: 'Februari'
                },
                {
                    value: '03',
                    label: 'Maret'
                },
                {
                    value: '04',
                    label: 'April'
                },
                {
                    value: '05',
                    label: 'Mei'
                },
                {
                    value: '06',
                    label: 'Juni'
                },
                {
                    value: '07',
                    label: 'Juli'
                },
                {
                    value: '08',
                    label: 'Agustus'
                },
                {
                    value: '09',
                    label: 'September'
                },
                {
                    value: '10',
                    label: 'Oktober'
                },
                {
                    value: '11',
                    label: 'November'
                },
                {
                    value: '12',
                    label: 'Desember'
                }],
                selectYears: null,
                selectMonth: null,
            }
        },
        methods: {
            deleteProses(idx, po) {
                this.$emit('deleteProses', idx, po)
            },
            simpan() {
                const dataisValid = () => {
                    let isValid = false
                    if(this.addProses.length > 0 && this.selectMonth && this.selectYears){
                        this.addProses.some(proses => {
                            if(proses.jadwal_mulai && proses.jadwal_selesai && proses.jadwal_mulai <= proses.jadwal_selesai) {
                                isValid = true
                                return true
                            }
                    })
                    }
                    return isValid
                }
                const success = () => {
                    this.$swal('Berhasil', 'Data berhasil disimpan', 'success')
                    $('.modalAddProses').modal('hide')
                }

                const error = () => {
                    this.$swal('Gagal', 'Data gagal disimpan', 'error')
                }

                return dataisValid() ? success() : error()
            },
        },  

        computed: {
            get5YearsOld() {
                let years = []
                let currentYear = new Date().getFullYear()
                for (let i = 0; i < 5; i++) {
                    years.push({
                        value: currentYear - i,
                        label: currentYear - i
                    })
                }
                return years
            },
            getDatesMonthsAndYearsSelected() {
                const date = {
                    minDates: null,
                    maxDates: null
                }
                if(this.selectMonth && this.selectYears) {
                    date.minDates = `${this.selectYears.value}-${this.selectMonth.value}-01`
                    date.maxDates = `${this.selectYears.value}-${this.selectMonth.value}-${new Date(this.selectYears.value, this.selectMonth.value, 0).getDate()}`
                    return date
                }
                return date
            },
        }
    }

</script>
<template>
    <div>
        <!-- Modal -->
        <div class="modal fade modalAddProses" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Jadwal Penerimaan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">Periode Bulan</label>
                                    <v-select v-model="selectMonth" :options="month" class="col-5"></v-select>
                                </div>
                                <div class="form-group row">
                                    <label for="" class="col-4 text-right">Periode Tahun</label>
                                    <v-select v-model="selectYears" :options="get5YearsOld" class="col-5"></v-select>
                                </div>

                                <table class="table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th rowspan="2">No</th>
                                            <th rowspan="2">No Purchase Order</th>
                                            <th rowspan="2">Supplier</th>
                                            <th rowspan="2">Estimasi Kedatangan</th>
                                            <th colspan="2" class="text-center">Penerimaan</th>
                                            <th rowspan="2">Aksi</th>
                                        </tr>
                                        <tr>
                                            <th>Jadwal Mulai</th>
                                            <th>Jadwal Selesai</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(proses, index) in addProses" :key="index">
                                            <td>{{ index + 1 }}</td>
                                            <td>{{ proses.no_po }}</td>
                                            <td>{{ proses.supplier }}</td>
                                            <td>{{ formatTanggal(proses.estimasi_datang) }}</td>
                                            <td>
                                                <input v-model="proses.jadwal_mulai" type="date" name="" id="" class="form-control" :min="getDatesMonthsAndYearsSelected.minDates" :max="getDatesMonthsAndYearsSelected.maxDates">
                                            </td>
                                            <td>
                                                <input v-model="proses.jadwal_selesai" type="date" name="" id="" class="form-control" :min="proses.jadwal_mulai" :max="getDatesMonthsAndYearsSelected.maxDates">
                                            </td>
                                            <td>
                                                <i class="fa fa-minus" style="color: red" aria-hidden="true" @click="deleteProses(index, proses.no_po)"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                        <button type="button" class="btn btn-primary" @click="simpan">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
