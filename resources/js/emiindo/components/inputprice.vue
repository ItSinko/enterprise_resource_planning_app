<template>
    <div>
        <input type="text" class="form-control" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true" @keypress="isNumber($event)" @keyup="changeSplitInt($event)">
    </div>
</template>
<script> 
export default {
    props: {
        nilai: {
            type: Number,
            default: 0
        },
    },
    data() {
        return {
            isInputActive: false,
        }
    },
    methods: {
        isNumber(evt) {
            evt = (evt) ? evt : window.event;
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if ((charCode > 31 && (charCode < 48 || charCode > 57)) && charCode !== 46) {
                evt.preventDefault();;
            } else {
                return true;
            }
        },
        changeSplitInt(evt){
            var value = evt.target.value;
            var value = value.replace(/\./g, '');
            var value = value.replace(/\,/g, '.');
            var value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
            evt.target.value = value;
        }
    },
    computed: {
        displayValue: {
            get(){
                if(this.isInputActive){
                    return this.nilai.toString();
                }else{
                    return "Rp. " + this.nilai.toString().split('').reverse().join('').match(/\d{1,3}/g).join('.').split('').reverse().join('');
                }
            },
            set(modifiedValue){
                let newValue = parseFloat(modifiedValue.replace(/\./g, '').replace(/\,/g, '.'));
                if(isNaN(newValue)){
                    newValue = 0;
                }

                this.$emit('input', newValue);
            }
        }
    }
}
</script>