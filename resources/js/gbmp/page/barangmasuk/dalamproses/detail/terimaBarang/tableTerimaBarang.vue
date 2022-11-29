<script>
export default {
    props: {
        parts: {
            type: Array,
            default: () => []
        }
    },
    methods: {
            isNumber(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (
                charCode > 31 &&
                (charCode < 48 || charCode > 57) &&
                charCode !== 46
            ) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        calc(jml, diterima, idx){
            const total = jml * diterima
            this.parts[idx].total = total
        }
    }
}
</script>
<template>
    <div>
        <table class="table tableDaftarBarang">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>Kode & Nama Part</th>
                    <th>Layout</th>
                    <th>Jumlah Dipesan</th>
                    <th>Jumlah Diterima</th>
                    <th>Jumlah <small>/ set</small></th>
                    <th>Total Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(part, index) in parts" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ part.kode_part }} - {{ part.nama_part }}</td>
                    <td><v-select v-model="part.layout"></v-select></td>
                    <td>{{ part.dipesan }}</td>
                    <td><input type="text" @keypress="isNumber($event)" @keyup="calc(part.diterima, part.jumlah, index)" v-model="part.diterima" class="form-control"></td>
                    <td><input type="text" @keypress="isNumber($event)" @keyup="calc(part.diterima, part.jumlah, index)" v-model="part.jumlah" class="form-control"></td>
                    <td><input type="text" v-model="part.total" disabled></td>
                </tr>
            </tbody>
        </table>
    </div>
</template>