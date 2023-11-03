<script>
import DataTable from '../../../components/DataTable.vue';
export default {
    props: ['dataTable'],
    components: {
        DataTable
    },
    data() {
        return {
            headers: [
                {
                    text: 'No. Urut',
                    value: 'no_urut'
                },
                {
                    text: 'Nama Produk',
                    value: 'nama'
                },
                {
                    text: 'Jumlah',
                    value: 'jumlah'
                },
                {
                    text: 'Aksi',
                    value: 'aksi'
                }
            ],
            search: '',
        }
    },
    methods: {
        kirim(id) {
            this.$swal({
                title: 'Apakah anda yakin?',
                text: "Data yang sudah dikirim tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    this.$swal({
                        title: 'Berhasil!',
                        text: 'Data berhasil dikirim.',
                        icon: 'success',
                        showConfirmButton: false,
                        timer: 1500
                    })
                    this.$emit('refresh')
                }
            })
        }
    },
}
</script>
<template>
    <div>
        <div class="d-flex flex-row-reverse bd-highlight">
            <div class="p-2 bd-highlight">
                <input type="text" v-model="search" class="form-control" placeholder="Cari...">
            </div>
        </div>
        <data-table :headers="headers" :items="dataTable" :search="search">
            <template v-slot:item.aksi="{ item }">
                <button class="btn btn-sm btn-outline-primary" @click="kirim(item)">
                    <i class="fa fa-check"></i>
                    Terima</button>
            </template>
        </data-table>
    </div>
</template>