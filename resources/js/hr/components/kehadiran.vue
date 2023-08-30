<script>
export default {
    props: ['kehadiran'],
    methods: {
        kehadiranIcon(value){
            if(typeof value == 'string'){
                value = value.toLowerCase()
            }
            switch(value){
                case 'hadir':
                    return 'fa-check-circle text-success'
                    break;
                case 'tidak_hadir':
                    return 'fa-times-circle text-danger'
                    break;
                default:
                    return 'fa-question-circle text-warning'
            }
        }
    },
}
</script>
<template>
    <div>
        <i :class="'fa ' + kehadiranIcon(kehadiran)"></i>
    </div>
</template>