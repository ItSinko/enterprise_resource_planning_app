<script>
    import terdaftar from './partterdaftar.vue'
    import tidakTerdaftar from './parttidakterdaftar'
    export default {
        components: {
            terdaftar,
            tidakTerdaftar
        },
        props: {
            title : {
                type: String,
                default: ''
            }
        },
        data() {
            return {
                partsTerdaftar: [{
                    nama_part: 'Part 1',
                    dipesan: 10,
                    diterima: 10,
                    keterangan: 'Keterangan',
                    sample: 10,
                    ok: 10,
                    nok: 10,
                    permasalahan: 'Permasalahan',
                    keterangan: 'Keterangan',
                    tindak_lanjut: 'Tindak Lanjut'
                }],
                partsNotTerdaftar: [
                    {
                    id: 1,
                    nama_packing_list: 'Packing List 1',
                    jumlah_pemesanan: 10
                    },
                    {
                    id: 2,
                    nama_packing_list: 'Packing List 2',
                    jumlah_pemesanan: 20
                    },
                ],
            }
        },
    }

</script>
<template>
    <div class="modal fade modalDetail">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ title }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="pills-partsterdaftar-tab" data-toggle="pill"
                                    data-target="#pills-partsterdaftar" type="button" role="tab" aria-controls="pills-partsterdaftar"
                                    aria-selected="true">Part Terdaftar</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="pills-partsnonterdaftar-tab" data-toggle="pill"
                                    data-target="#pills-partsnonterdaftar" type="button" role="tab" aria-controls="pills-partsnonterdaftar"
                                    aria-selected="false">Part Tidak Terdaftar</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-partsterdaftar" role="tabpanel"
                                aria-labelledby="pills-partsterdaftar-tab">
                                <terdaftar :dataTable="partsTerdaftar"/>
                            </div>
                            <div class="tab-pane fade" id="pills-partsnonterdaftar" role="tabpanel"
                                aria-labelledby="pills-partsnonterdaftar-tab">
                                <tidakTerdaftar :dataTable="partsNotTerdaftar"/>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>
