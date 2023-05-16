<script>
export default {
    props: {
        formPO: {
            nomor_po: '',
            tanggal_po: '',
            estimasi_datang: '',
            ekspedisi: '',
        },
    },
    data() {
        return {
            ekspedisiSelect: [
                'JNE',
                'TIKI',
                'POS',
                'J&T',
                'Wahana',
                'Sicepat',
                'Ninja',
                'JET',
                'DSE',
                'First Logistics',
                'RPX Holding',
                'Indah Logistic',
                'IDL Logistics',
                'Star Cargo',
                'Nusantara Card Semesta',
                'SLIS',
                'SAP Express',
                'PCP Express',
                'JET Express',
                'DHL',
                'FedEx',
                'UPS',
                'EMS',
            ]
        }
    },
}
</script>
<template>
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Informasi Purchase Order</h4>
                <div class="card-text mt-5">
                    <div class="form-group row">
                        <label for="nomor_po" class="col-sm-4 col-form-label">Nomor PO</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nomor_po" v-model="formPO.nomor_po">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tanggal_po" class="col-sm-4 col-form-label">Tanggal PO</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="tanggal_po" v-model="formPO.tanggal_po">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="estimasi_datang" class="col-sm-4 col-form-label">Estimasi Datang</label>
                        <div class="col-sm-8">
                            <input type="date" class="form-control" id="estimasi_datang" v-model="formPO.estimasi_datang">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="ekspedisi" class="col-sm-4 col-form-label">Ekspedisi</label>
                        <div class="col-sm-8">
                            <v-select :options="ekspedisiSelect" v-model="formPO.ekspedisi"></v-select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</template>