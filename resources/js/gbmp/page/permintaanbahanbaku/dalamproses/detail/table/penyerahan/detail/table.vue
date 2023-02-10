<script>
export default {
    data() {
        return {
            dataTable: [
                {
                    kode_part: 'P-001',
                    nama_part: 'Part 1',
                    jumlah_diserahkan: 100,
                    detail: [
                        {
                            lot_number: 'P-001 - Lot 1',
                            jumlah_diserahkan: 100,
                        },
                        {
                            lot_number: 'P-001 - Lot 2',
                            jumlah_diserahkan: 100,
                        }
                    ]
                },
                {
                    kode_part: 'P-002',
                    nama_part: 'Part 2',
                    jumlah_diserahkan: 100,
                    detail: [
                        {
                            lot_number: 'P-001 - Lot 1',
                            jumlah_diserahkan: 100,
                        },
                        {
                            lot_number: 'P-001 - Lot 2',
                            jumlah_diserahkan: 100,
                        }
                    ]
                }
            ]
        }
    },
}
</script>
<template>
    <div>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Kode Part</th>
                    <th>Nama Part</th>
                    <th>Jumlah Diserahkan</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, index) in dataTable" :key="index">
                    <td>{{ data.kode_part }}</td>
                    <td>{{ data.nama_part }}</td>
                    <td>{{ data.jumlah_diserahkan }}</td>
                </tr>
                
            </tbody>
        </table>
    </div>
</template>