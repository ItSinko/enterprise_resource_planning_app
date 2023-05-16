<script>
export default {
    props: {
        headers: {
            type: Object,
            required: true,
        },
    }
}
</script>
<template>
    <div>
        <div class="row">
            <div class="col">
                <div class="card card-primary">
                    <div class="card-body">
                        <h4 class="card-title">No Permintaan Pembelian</h4>
                        <p class="card-text text-bold">{{ headers.pp_header.nopp }}</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-danger">
                    <div class="card-body">
                        <h4 class="card-title">No Purchase Order</h4>
                        <p class="card-text text-bold">{{ headers.po_header.nopo }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card card-success">
            <div class="card-body">
                <h4 class="card-title">Supplier</h4>
                <p class="card-text text-bold">{{ headers.supplier_header.nama }}</p>
            </div>
        </div>
    </div>
</template>