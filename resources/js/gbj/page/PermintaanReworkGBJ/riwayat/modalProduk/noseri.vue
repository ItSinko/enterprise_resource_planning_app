<script>
import DataTable from '../../../../components/DataTable.vue';
export default {
    props: ['seriSelected'],
    components: {
        DataTable
    },
    data() {
        return {
            headers: [
                { text: 'No.', value: 'no' },
                { text: 'No. Seri', value: 'noseri' },
            ],
            search: '',
        }
    },
    methods: {
        closeModal() {
            $('.modalSeri').modal('hide')
            this.$nextTick(() => {
                this.$emit('closeModal')
            })
        },
    
    },
}
</script>
<template>
    <div class="modal fade modalSeri" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm">
                                    <label for="">Nama Produk</label>
                                    <div class="card nomor-so">
                                        <div class="card-body">
                                            <span id="so">{{ seriSelected?.kelompok }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm">
                                    <label for="">Jumlah Transfer</label>
                                    <div class="card nomor-akn">
                                        <div class="card-body">
                                            <span id="akn">{{ seriSelected?.jumlah }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-row-reverse bd-highlight">
                                <div class="p-2 bd-highlight">
                                    <input type="text" v-model="search" class="form-control" placeholder="Cari...">
                                </div>
                            </div>
                            <DataTable :headers="headers" :items="seriSelected?.item" :search="search">
                                <template #item.no="{ item, index }">
                                    <div>
                                        {{ index + 1 }}
                                    </div>
                                </template>
                            </DataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>