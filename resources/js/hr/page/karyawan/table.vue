<script>
import modalPegawaiComp from './modalPegawai.vue';
export default {
    props: ['dataTable'],
    components: {
        modalPegawaiComp
    },
    data() {
        return {
            modalPegawai: false,
        }
    },
    methods: {
        image(src) {
            return src ? src : '/assets/image/user/blank.png';
        },
        kelamin(kelamin) {
            return kelamin == 'L' ? 'Laki-laki' : 'Perempuan';
        },
        hapus(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal(
                        'Terhapus!',
                        'Data berhasil dihapus.',
                        'success'
                    )
                }
            })
        },
        edit(id) {
            this.$router.push({ name: 'edit-karyawan', params: { id } })
        },
        status(id) {
            this.modalPegawai = true;
            this.$nextTick(() => {
                $('.modalPegawai').modal('show');
            })
        },
    },
}
</script>
<template>
    <div>
        <modalPegawaiComp v-if="modalPegawai" @closeModal="modalPegawai = false"></modalPegawaiComp>
        <table class="table">
            <thead class="text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Karyawan</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Masuk</th>
                    <th>Bagian</th>
                    <th>Status Karyawan</th>
                    <th>Status Pegawai</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody v-if="dataTable.length > 0">
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td class="text-center">
                        {{ index + 1 }}
                    </td>
                    <td>
                        <div class="d-flex bd-highlight">
                            <div class="p-2 bd-highlight">
                                <img :src="image(data.foto)" class="rounded float-left" width="50">
                            </div>
                            <div class="align-self-start p-2 bd-highlight">
                                <span class="text-bold">{{ data.nama }}</span><br>
                                <span class="text-muted">{{ data?.divisi_id }} (Nomor Induk Karyawan)</span>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        {{ kelamin(data.kelamin) }}
                    </td>
                    <td class="text-center">
                        {{ dateFormat(data?.tgl_kerja) }}
                    </td>
                    <td class="text-center">
                        {{ data?.divisi.nama }}
                    </td>
                    <td class="text-center">
                        Tetap
                    </td>
                    <td class="text-center">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" class="custom-control-input" :id="`customSwitch${index}`" @click="status(data.id)">
                            <label class="custom-control-label" :for="`customSwitch${index}`">Aktif</label>
                        </div>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-warning" @click="edit(data.id)">
                            <i class="fa fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-sm btn-outline-info" @click="detail(data.id)">
                            <i class="fa fa-eye"></i> Detail
                        </button>
                    </td>
                </tr>
            </tbody>
            <tbody v-else>
                <tr>
                    <td colspan="8" class="text-center">
                        Data tidak ditemukan
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>