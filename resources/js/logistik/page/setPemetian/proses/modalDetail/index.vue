<script>
import DataTable from '../../../../components/DataTable.vue';
export default {
    props: ['dataModalDetailSeri'],
    components: {
        DataTable,
    },
    data() {
        return {
            search: '',
            headers: [
                { text: 'No', value: 'no' },
                { text: 'Nama Produk', value: 'produk' },
                { text: 'Nomor Seri', value: 'noseri' },
            ],
        }
    },
    methods: {
        closeModal() {
            $('.modalDetailSeri').modal('hide');
            this.$nextTick(() => {
                this.$emit('closeModal');
            });
        },
    },
}
</script>
<template>
<div class="modal fade modalDetailSeri" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Nomor Seri</h5>
                    <button type="button" class="close" @click="closeModal">
                        <span aria-hidden="true">&times;</span>
                    </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm">
                        <label for="">Nomor Peti</label>
                        <div class="card nomor-so">
                            <div class="card-body">
                                <span id="so">
                                    {{ dataModalDetailSeri.no_peti }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm">
                        <label for="">Tanggal Dibuat</label>
                        <div class="card nomor-akn">
                            <div class="card-body">
                                {{ dataModalDetailSeri.tanggal_dibuat }}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm">
                        <label for="">Packer</label>
                        <div class="card nomor-po">
                            <div class="card-body">
                                {{ dataModalDetailSeri.packer }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex flex-row-reverse bd-highlight">
                    <div class="p-2 bd-highlight">
                        <input type="text" class="form-control" placeholder="Cari Produk" v-model="search">
                    </div>
                </div>
                <DataTable :headers="headers" :items="dataModalDetailSeri.seri" :search="search">
                    <template #item.no = "{item, index}">
                        <div>
                            {{ index + 1 }}
                        </div>
                    </template>
                </DataTable>
            </div>
        </div>
    </div>
</div>
</template>