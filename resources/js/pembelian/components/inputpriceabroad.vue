<template>
    <div>
        <input :disabled="disabled" type="text" class="form-control" v-model="displayValue" @blur="isInputActive = false" @focus="isInputActive = true" @keypress="isNumber($event)" @keyup="changeSplitInt($event)">
    </div>
</template>
<script> 
export default {
    props: {
        nilai: {
            type: Number,
            default: 0
        },
        disabled: {
            type: Boolean,
            default: false
        },
        kurs: {
            type: String,
            default: 'Rp.'
        }
    },
    data() {
        return {
            isInputActive: false,
        }
    },
    methods: {
        isNumber(evt) {
            new RegExp('[0-9]').test(evt.key) || evt.preventDefault()
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