<script>
export default {
    props: {
        headers: {
            type: Object,
            default: () => {}
        },
    }
}
</script>
<template>
    <div>
        
    </div>
</template>
<style>
    .card-primary {
        background-color: #eff2f4;
        color: #5b7a8f;
    }

    .card-danger {
        background-color: #f5efef;
        color: #985d61;
    }

    .card-success {
        background-color: #e7e9e7;
        color: #616e61;
    }

    .card-secondary {
        background-color: #d8d8d8;
        color: #616161;
    }

    .row-whith-wide-cards .card {
        width: 100%;
    }

</style>
