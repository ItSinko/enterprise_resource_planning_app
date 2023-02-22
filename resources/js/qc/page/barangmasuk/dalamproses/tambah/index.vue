<script>
    export default {
        props: {
            selectTable: {
                type: Array,
                default: () => []
            }
        },
        data() {
            return {
                selectYears: null,
                selectMonth: null,
            }
        },
        computed: {
            getAllMonths() {
                const currentYear = moment().year()

                return moment.months().filter(month => {
                    const monthIndex = moment().month(month).year(currentYear).month();
                    return monthIndex >= 0 && monthIndex < 12;
                });
            },

            get5YearsOld() {
                let years = []
                let currentYear = new Date().getFullYear()
                for (let i = 0; i < 5; i++) {
                    years.push(currentYear - i)
                }
                return years
            },
        }
    }

</script>
<template>
    <div class="modal fade modalBarangMasuk" id="modelId" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true" data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Jadwal Pengecekan</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group row">
                        <label for="" class="col-4 text-right">Periode Bulan</label>
                        <v-select class="col-5" :options="getAllMonths" label="month" v-model="selectMonth" />

                    </div>
                    <div class="form-group row">
                        <label for="" class="col-4 text-right">Periode Tahun</label>
                        <v-select class="col-5" :options="get5YearsOld" label="years" v-model="selectYears" />

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>


            </div>
        </div>
    </div>

</template>
