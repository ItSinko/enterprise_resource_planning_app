<script>
export default {
    data() {
        return {
            divisi: parseInt(localStorage.getItem('divisi'))
        }
    },
}
</script>
<template>
    <div>
        <button class="btn btn-info">
            <i class="fa fa-check"></i>
            Tambah Penerimaan
        </button>
        <table class="table text-center mt-1">
            <thead class="thead-light">
                <tr>
                    <th v-if="divisi === 7"><input type="checkbox" name="" id=""></th>
                    <th>Nama Produk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</template>