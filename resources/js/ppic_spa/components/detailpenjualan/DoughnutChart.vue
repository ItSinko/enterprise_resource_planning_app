<template>
    <div>
        <VueApexCharts width="380" type="donut" :options="options" :series="series"></VueApexCharts>
        <div class="box">
            <small>
                <i class="fas fa-info-circle"></i> <strong>Catatan: </strong>
                <ol style="list-item-style:none; margin-left:0px;padding-left:15px;">
                    <li>Angka warna <b class="text-danger">merah</b> menunjukkan jumlah unit yang <i>belum
                            diproses</i> oleh divisi tersebut</li>
                    <li>Angka warna <b class="text-dark">hitam</b> menunjukkan total yang <i>telah diberikan
                            dan harus diproses</i> oleh divisi tersebut</li>
                    <li>Angka pada Kirim merupakan total unit yang <i>telah terkirim</i></li>
                </ol>
            </small>
        </div>
    </div>
</template>

<script>
    import VueApexCharts from 'vue-apexcharts';
    export default {
        components: {
            VueApexCharts
        },
        props: {
            chartData: {
                type: Object,
                required: true,
                default: null
            }
        },
        computed: {
            options() {
                return {
                    labels: ['Gudang', 'Quality Control', 'Logistik', 'Kirim'],
                    colors: ['#F44336', '#FF9800', '#03A9F4', '#4CAF50'],
                }
            },
            series() {
                let dataChart = Object.values(this.chartData)
                dataChart.shift()
                return dataChart
            },
        }
    }

</script>
