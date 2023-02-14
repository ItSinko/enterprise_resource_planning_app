<script>
export default {
    props: {
        dataTable: {
            type: Array,
            default: () => []
        }
    }
}
</script>
<template>
    <div class="modal fade detailLot" id="modelId" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
        data-keyboard="false" data-backdrop="static" style="display: block">
        <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail LOT Number</h5>
                        <button type="button" class="close" @click="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <table class="table">
                        <thead class="thead-light">
                            <tr>
                                <th>No</th>
                                <th>Lot Number</th>
                                <th>Jumlah Diserahkan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(data, index) in dataTable" :key="index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ data.lot_number }}</td>
                                <td>{{ data.jumlah_diserahkan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
</template>