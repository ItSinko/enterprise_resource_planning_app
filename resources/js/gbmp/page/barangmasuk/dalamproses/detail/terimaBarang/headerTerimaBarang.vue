<script>
export default {
    props: {
        headerBarang: {
            type: Object,
            default: () => {}
        }
    },
}
</script>
<template>
    <div>
                            <div class="row">
                        <div class="col">
                            <div class="card card-primary">
                                <div class="card-body">
                                    <h4 class="card-title">Supplier</h4>
                                    <p class="card-text text-bold">
                                        PT. Sinar Jaya
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-success">
                                <div class="card-body">
                                    <h4 class="card-title">Nama Produk</h4>
                                    <p class="card-text text-bold">
                                        BRG-001
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card card-danger">
                                <div class="card-body">
                                    <h4 class="card-title">Jumlah Pemesanan</h4>
                                    <p class="card-text text-bold">
                                        20
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-purple">
                                <div class="card-body">
                                    <h4 class="card-title">Jumlah Pemesanan</h4>
                                    <p class="card-text text-bold">
                                        20
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card card-success">
                                <div class="card-body">
                                    <h4 class="card-title">No Surat Jalan</h4>
                                    <p class="card-text text-bold">
                                        123456789
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
</template>