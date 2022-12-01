<script>
    import moment from 'moment'

    export default {
        props: {
            monthYears: {
                type: String,
                required: true
            }
        },
        methods: {

            convertMonthYear(monthYear) {
                return moment(monthYear, 'MM-YYYY').lang("ID").format('MMMM YYYY')
            },
            nextMonthYear() {
                this.monthYears = moment(this.monthYears, 'MM-YYYY').lang("ID").add(1, 'months').format('MM YYYY')
            },
            prevMonthYear() {
                this.monthYears = moment(this.monthYears, 'MM-YYYY').lang("ID").subtract(1, 'months').format('MM YYYY')
            },
        },
    }

</script>
<template>
    <div>

        <div class="d-flex justify-content-between">
            <div class="bd-highlight">
                <button type="button" class="btn btn-outline-info" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    Pilih Periode
                    <i class="fa fa-caret-down" aria-hidden="true"></i>
                </button>
            </div>
            <div class="bd-highlight">
                <h2 class="font-bold">
                    {{ convertMonthYear(monthYears) }}
                </h2>
            </div>
            <div class="bd-highlight">
                <div class="btn-group">
                    <button type="button" class="btn btn-outline-info" @click="prevMonthYear">
                        <i class="fa fa-caret-left" aria-hidden="true"></i>
                    </button>
                    <button type="button" class="btn btn-outline-info" @click="nextMonthYear">
                        <i class="fa fa-caret-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div class="d-flex">
                        <div class="box-ket box-grey"></div>
                        <p class="pr-2">Jumlah Barang yang <strong>belum</strong> diperiksa</p>
                    </div>
                    <div class="d-flex">
                        <div class="box-ket box-orange"></div>
                        <p class="pr-2">Jumlah Barang yang belum diperiksa <strong>Gudang</strong></p>
                    </div>
                                        <div class="d-flex">
                        <div class="box-ket box-yellow"></div>
                        <p class="pr-2">Jumlah Barang yang belum diperiksa <strong>QC</strong></p>
                    </div>
                    <div class="d-flex">
                        <div class="box-ket box-green"></div>
                        <p class="pr-2">Jumlah Barang yang <strong>telah</strong> diperiksa</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
    .box-ket {
        width: 20px;
        height: 20px;
        border-radius: 5px;
        margin-right: 5px;
    }

    .box-grey {
        background-color: #D9D9D9;
    }

    .box-orange {
        background-color: #F06414;
    }

    .box-yellow {
        background-color: #FFC107;
    }

    .box-green {
        background-color: #28A745;
    }

</style>
