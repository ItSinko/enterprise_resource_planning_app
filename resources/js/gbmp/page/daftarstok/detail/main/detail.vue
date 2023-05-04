<script>
export default {
    data() {
        return {
            deskripsi: 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quod.',
            spesifikasi: {
                dimensi : '100 x 100 x 100',
                fisik : 'Besi',
                batas : '1000',
            }
        }
    },
}
</script>
<template>
    <div class="mt-3">
        <h5 class="text-muted text-bold">Deskripsi</h5>
        <p>{{ deskripsi }}</p>

        <h5 class="text-muted text-bold">Spesifikasi</h5>
        <table class="table table-striped">
            <tbody>
                <tr>
                    <td>Dimensi</td>
                    <td>{{ spesifikasi.dimensi }}</td>
                </tr>
                <tr>
                    <td>Fisik</td>
                    <td>{{ spesifikasi.fisik }}</td>
                </tr>
                <tr>
                    <td>Batas</td>
                    <td>{{ spesifikasi.batas }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>