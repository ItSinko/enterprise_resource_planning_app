<script>
    export default {
        data() {
            return {
                form: {
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
    <div class="modal fade modalPart" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
        aria-hidden="true">
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
                                    <input type="text" v-model="form.jumlah" class="form-control col-1"
                                        @keypress="isNumber($event)">
                                    <div class="col-2">
                                        <button class="btn btn-primary">Cari</button>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="card-title text-bold">Detail Produk dan Part</h4>
                                        <div class="card-text mt-5">
                                            <div class="row">
                                                <div class="col-2">
                                                    <p>No PP</p>
                                                </div>
                                                <div class="col-5 text-bold">
                                                    <p>PP/2020/0001</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-2">
                                                    <p>Versi BOM</p>
                                                </div>
                                                <div class="col-5 text-bold">
                                                    <p>1</p>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-2">Jumlah</div>
                                                <div class="col-5 text-bold">
                                                    <p>{{ form.jumlah ? form.jumlah : 0 }}</p>
                                                </div>
                                            </div>

                                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill"
                                                        data-target="#pills-home" type="button" role="tab"
                                                        aria-controls="pills-home" aria-selected="true">Part Terdaftar</a>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill"
                                                        data-target="#pills-profile" type="button" role="tab"
                                                        aria-controls="pills-profile" aria-selected="false">Part Tidak Terdaftar</a>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="pills-tabContent">
                                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                                    aria-labelledby="pills-home-tab">...</div>
                                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                                    aria-labelledby="pills-profile-tab">...</div>
                                            </div>
                                        </div>
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
