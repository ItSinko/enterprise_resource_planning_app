<script>
import DataTable from '../../../../components/DataTable.vue';
export default {
    components: {
        DataTable,
    },
    data() {
        return {
            search: '',
            noseri: [
                {
                    noseri: 'TD123456',
                    variasi: 'COKLAT',
                }
            ],
            headers: [
                {
                    text: 'No Seri',
                    value: 'noseri',
                },
                {
                    text: 'Variasi',
                    value: 'variasi',
                },
            ]
        }
    },
    methods: {
        closeModal() {
            $('.modalDetailSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        }
    },
}
</script>
<template>
    <div class="modal fade modalDetailSeri" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nomor seri yang harus diganti</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <DataTable :headers="headers" :items="noseri" :search="search" />
                </div>
            </div>
        </div>
    </div>
</template>