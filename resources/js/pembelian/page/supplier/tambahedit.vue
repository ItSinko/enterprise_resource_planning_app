<script>
    import Header from '../../components/header.vue'
    export default {
        components: {
            Header
        },
        props: {
            title: {
                type: String,
                default: 'Tambah Supplier'
            },
            breadcumbs: {
                type: Array,
                default: () => [{
                    name: 'Beranda',
                    link: '#'
                }, {
                    name: 'Supplier',
                    link: '/pembelian/supplier'
                }, {
                    name: 'Tambah Supplier',
                    link: '/pembelian/supplier/tambah'
                }]
            },
        }
    }

</script>
<template>
    <div>
        <Header :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-bold">Data Supplier</h4>
                        <div class="card-text text-center">
                            <div class="form-group row">
                                <label for="" class="col-4 text-right">Nama Supplier</label>
                                <div class="col-5">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 text-right">Kode Supplier</label>
                                <div class="col-4">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 text-right">Kurs</label>
                                <div class="col-4">
                                    <input type="text" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-4 text-right">Kurs</label>
                                <div class="row" style="padding-left: 20px">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio1" value="option1">
                                        <label class="form-check-label" for="inlineRadio1">Import</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio2" value="option2">
                                        <label class="form-check-label" for="inlineRadio2">Lokal</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-bold">Informasi Alamat</h4>
                                <div class="card-text pt-5">
                                    <div class="container">
                                        <div class="form-group row">
                                            <label for="" class="col-2">Alamat</label>
                                            <textarea class="col-8 form-control" name="" id="" cols="5"
                                                rows="5"></textarea>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-2">Kode Pos</label>
                                            <input type="text" class="form-control col-3">
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-2">Negara</label>
                                            <input type="text" class="form-control col-5">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title text-bold">Informasi Kontak</h4>
                                <div class="card-text pt-5">
                                    <div class="container">
                                        <div class="form-group row">
                                            <label for="" class="col-2">Email</label>
                                            <textarea class="col-8 form-control" name="" id="" cols="5"
                                                rows="5"></textarea>
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-2">No Telepon</label>
                                            <input type="text" class="form-control col-8">
                                        </div>
                                        <div class="form-group row">
                                            <label for="" class="col-2">Fax</label>
                                            <input type="text" class="form-control col-8">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex bd-highlight">
                    <div class="p-2 flex-grow-1 bd-highlight">
                        <button class="btn btn-danger">Batal</button>
                    </div>
                    <div class="p-2 bd-highlight">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
