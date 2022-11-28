<script>
    export default {
        props: {
            url: {
                type: String,
                required: true
            },
            filename: {
                type: Array,
                required: true,
                default: () => []
            }
        },
        methods: {
            downloadFile(file) {
                window.open(this.url + file, '_blank');
            }
        }
    }

</script> 
<template>
    <div class="row row-whith-wide-cards">
        <div class="col-3 d-flex align-self-stretch" v-for="(file, idx) in filename" :key="idx">
            <div class="card">
                <div class="card-body">
                    <div class="card-text text-center">
                        <div class="d-flex justify-content-center">
                            <i class="fa fa-download" aria-hidden="true"></i>
                        </div>
                        <a href="#" @click="downloadFile(file.path)">{{ file.path }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<style>
            .row-whith-wide-cards .card {
  width: 100%;
}
</style>
