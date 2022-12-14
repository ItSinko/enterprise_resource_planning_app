<script>
export default {
    data() {
        return {
            form : {
                produk: null,
                versi: null,
                jumlah: null,
            }
        }
    },
    methods: {
        isNumber(event) {
            return new RegExp('[0-9]').test(event.key) || event.preventDefault()
        }
    }
}
</script>
<template>
    <div class="modal fade modalPart" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-bold">Tambah Part</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-text">
                                <div class="form-group row">
                                    <label for="" class="col-1 text-center">Nama Produk</label>
                                    <v-select v-model="form.produk" class="col-2"></v-select>
                                    <label for="" class="col-1 text-center">Versi BOM</label>
                                    <v-select v-model="form.versi" class="col-2"></v-select>
                                    <label for="" class="col-1 text-center">Jumlah</label>
                                    <input type="text" v-model="form.jumlah" class="form-control col-1" @keypress="isNumber($event)">
                                    <div class="col-2">
                                        <button class="btn btn-primary">Cari</button>
                                    </div>                                              
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>