<script>
export default {
    props: {
        title: {
            type: String,
            default: 'Teknik'
        }   
    }
}
</script>
<template>
            <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{ title }}</h1>
                </div>
            </div>
        </div>
</template>