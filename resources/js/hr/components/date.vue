<script>
import moment from 'moment'
export default {
    props: ['date'],
    methods: {
        formatDate(date){
            // return moment to format date Indonesia and language
            return moment(date).locale('id').format('dddd, DD MMMM YYYY')
        }
    },
}
</script>
<template>
    <div>
        <span>{{ formatDate(date) }}</span>
    </div>
</template>