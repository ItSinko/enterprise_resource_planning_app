<script>
    import HeaderFirst from '../../../../components/header'
    import HeaderSecond from './header.vue'
    export default {
        components: {
            HeaderFirst,
            HeaderSecond
        },
        data() {
            return {
                title: 'Permintaan Pembelian',
                breadcumbs: [{
                        name: 'Beranda',
                        link: '#'
                    },
                    {
                        name: 'Permintaan Pembelian',
                        link: '/pembelian/permintaan'
                    },
                    {
                        name: 'Tambah Permintaan Pembelian',
                        link: '#'
                    }
                ]
            }
        },
        computed: {
            dateNow() {
                return new Date().toISOString().substr(0, 10)
            }
        }
    }

</script>
<template>
    <div>
        <header-first :title="title" :breadcumbs="breadcumbs" />
        <div class="card">
            <div class="card-body">
                <header-second />
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Detail Permintaan</h4>
                        <div class="card-text">
                            <div class="form-group row">
                                <label for="" class="col-5 text-right">Tanggal Dibutuhkan</label>
                                <div class="col-3"><input type="date" class="form-control" :max="dateNow"></div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-5 text-right">Kebutuhan Barang</label>
                                <div class="col-5">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio1" value="umum">
                                        <label class="form-check-label" for="inlineRadio1">Umum</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="inlineRadioOptions"
                                            id="inlineRadio2" value="part">
                                        <label class="form-check-label" for="inlineRadio2">Part Produk</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="" class="col-5 text-right">Tujuan</label>
                                <div class="col-4">
                                    <textarea name="" id="" cols="2" rows="3" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
