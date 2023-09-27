<script>
import Header from '../../components/header.vue';
export default {
    components: {
        Header,
    },
}
</script>
<template>
    <div>
       <h1 v-if="$route.params.id">Edit {{ $route.params.id }}</h1>
       <h1 v-else>Tambah</h1>     
    </div>
</template>