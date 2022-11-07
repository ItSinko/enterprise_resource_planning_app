<script>
    import Header from '../components/header.vue'
    export default {
        components: {
            Header,
        },
        mounted() {
            $('.partTable').DataTable({
                "destroy": true,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        },
        data() {
            return {
                parts: [{
                    id: 1,
                    image: 'https://picsum.photos/200/300',
                    kode: 'PN-0001',
                    name: 'IT',
                    jenis: 'PT. ABC',
                    deskripsi: '2020-01-01',
                    jumlah: '2020-01-01',
                    satuan: 'Dalam Pemeriksaan QC'
                }, {
                    id: 2,
                    image: 'https://picsum.photos/200/300',
                    kode: 'PN-0002',
                    name: 'IT',
                    jenis: 'PT. ABC',
                    deskripsi: '2020-01-01',
                    jumlah: '2020-01-01',
                    satuan: 'Pemeriksaan Lanjutan'
                }]
            }
        },
        methods: {
            detailPart(idx){
                $('.modal').modal('show');
            }
        },
    }

</script>
<template>
    <div>
        <Header :title="'Daftar Part'"></Header>
        <div class="card">
            <div class="card-body">
                <table class="table partTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Kode Part</th>
                            <th>Nama Part</th>
                            <th>Jenis</th>
                            <th>Deskripsi</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="part in parts" :key="part.id">
                            <td><img :src="part.image" alt="" width="50px"></td>
                            <td>{{part.kode}}</td>
                            <td>{{part.name}}</td>
                            <td>{{part.jenis}}</td>
                            <td>{{part.deskripsi}}</td>
                            <td>{{part.jumlah}}</td>
                            <td>{{part.satuan}}</td>
                            <td>
                                <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton"
                                    aria-haspopup="true" aria-expanded="true"><i class="fas fa-ellipsis-v"></i></div>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                    x-placement="bottom-start"
                                    style="position: absolute; transform: translate3d(-117px, 30px, 0px); top: 0px; left: 0px; will-change: transform;">
                                    <button class="dropdown-item" type="button" @click="detailPart(part.id)">
                                        <i class="fas fa-eye"></i>
                                        Detail
                                    </button>

                                    <button class="dropdown-item" type="button">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </button>
                                    
                                    <button class="dropdown-item" type="button">
                                        <i class="far fa-trash-alt"></i>
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
            Launch demo modal
        </button> -->
        <div class="modal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-bold">Detail Part</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                        <div class="col-4">
                            <div class="text-center">
                                <img src="https://picsum.photos/200/300">
                            </div>
                        </div>
                        <div class="col-8">
                            <h1 class="display-4 text-bold">Nama Part</h1>
                            <p class="text-bold">SPC567</p>

                            <div class="mt-1">
                                <p>Jenis</p>
                                <div class="btn btn-outline-secondary mt-n2">Screw</div>
                            </div>

                            <div class="mt-2">
                                <p>Jumlah</p>
                                <p class="text-bold mt-n2">5000 pcs</p>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</template>
