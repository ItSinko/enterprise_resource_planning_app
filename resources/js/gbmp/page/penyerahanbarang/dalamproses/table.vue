<script>
    import Status from '../../../components/status.vue'
    import Detail from './detail/'
export default {
    components: {
        Status,
        Detail
    },
    props: {
        renderPaginate: {
            type: Array,
            default: () => []
        },
    },
    data() {
        return {
            modal: false,
        }
    },
    methods: {
            detail(id){
                this.modal = true
                this.$nextTick(() => {
                    $('.modalDetail').modal('show')
                })
            },
            closeDetail(){
                this.modal = false
                $('.modalDetail').modal('hide')
            },
            kirimBarang(){
                this.$swal({
                    title: 'Kirim Barang',
                    text: "Anda yakin telah memberi part yang diminta?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya'
                }).then((result) => {
                if (result.value) {
                    this.$swal(
                        'Berhasil!',
                        'Barang telah dikirim.',
                        'success'
                    )
                }
            })
            }
    },   
}
</script>
<template>
    <div>
        <detail v-if="modal" @close="closeDetail"/>
                <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>No</th>
                    <th>No Permintaan</th>
                    <th>No Penyerahan</th>
                    <th>Tujuan</th>
                    <th>Tanggal Permintaan</th>
                    <th>Tanggal Penyerahan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in renderPaginate" :key="index">
                    <td>{{ index + 1 }}</td>
                    <td>{{ data.no_permintaan }}</td>
                    <td>{{ data.no_penyerahan }}</td>
                    <td><status :status="data.tujuan" /></td>
                    <td>{{ formatTanggal(data.tanggal_permintaan) }}</td>
                    <td>{{ formatTanggal(data.tanggal_penyerahan) }}</td>
                    <td>
                        <button class="btn btn-info btn-sm" @click="kirimBarang" v-if="data.status === 'kirim barang'" >Kirim Barang</button>
                        <Status :status="data.status" v-else/>
                    </td>
                    <td>
                         <div class="dropdown-toggle" data-toggle="dropdown" id="dropdownMenuButton" aria-haspopup="true"
                            aria-expanded="true">
                            <i class="fas fa-ellipsis-v"></i>
                        </div>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" x-placement="bottom-start"
                            style="
                    position: absolute;
                    transform: translate3d(-117px, 30px, 0px);
                    top: 0px;
                    left: 0px;
                    will-change: transform;
                  ">
                            <button 
                            @click="detail(data.id)"
                            class="dropdown-item" 
                            type="button">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>

                            <button
                             class="dropdown-item" 
                             type="button">
                                <i class="fas fa-pencil-alt"></i>
                                Ubah
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>