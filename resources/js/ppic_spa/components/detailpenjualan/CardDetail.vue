<template>
    <div>
        <div class="card">
            <header class="card-header">
                <p class="card-header-title has-text-centered">
                    {{ detailProdukStok.detail.penjualan_produk.nama }}
                </p>
            </header>
            <div class="card-content">
                <div class="content">
                    <div class="box">
                        <p class="has-text-centered">Gudang</p>
                        <p class="has-text-centered"><span class="has-text-danger">{{ totalStok[0] }}</span> dari
                            {{ detailProdukStok.detail.count_gudang }}</p>
                    </div>
                    <div class="box">
                        <p class="has-text-centered">Quality Control</p>
                        <p class="has-text-centered"><span class="has-text-danger">{{ totalStok[1] }}</span> dari
                            {{ detailProdukStok.detail.count_qc_ok }}</p>
                    </div>

                    <div class="box">
                        <p class="has-text-centered">Logistik</p>
                        <p class="has-text-centered"><span class="has-text-danger">{{ totalStok[2] }}</span> dari
                            {{ detailProdukStok.detail.count_log }}</p>
                    </div>

                    <div class="box">
                        <p class="has-text-centered">Kirim</p>
                        <p class="has-text-centered"><span class="has-text-danger">{{ totalStok[3] }}</span> Unit</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            detailProdukStok: {
                type: Object,
                required: true,
                default: null
            }
        },
        computed: {
            totalStok() {
                let totalStok = Object.values(this.detailProdukStok)
                totalStok.shift()
                return totalStok
            },
        }
    }

</script>
