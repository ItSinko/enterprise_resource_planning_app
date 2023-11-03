<script>
import Table from './table.vue';
export default {
    components: {
        Table,
    },
    props: ['dataTable'],
}
</script>
<template>
    <div v-if="!$store.state.loading">
        <Table :dataTable="dataTable"></Table>
    </div>
</template>