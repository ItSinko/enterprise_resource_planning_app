<template>
    <div>
        <input type="text" class="form-control" placeholder="00:00" v-model.number="validateTime" @blur="isInputActive = false"
            @focus="isInputActive = true" @keypress="isNumber($event)" @keyup="changeSplitInt($event)">
    </div>
</template>

<script>
export default {
    name: 'TimePicker24',
    props: {
        time: {
            type: String,
            default: '00:00'
        },
    },
    data() {
        return {
            isInputActive: false,
        };
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
        changeSplitInt(evt) {
            let value = evt.target.value;
            let parts = value.split(':');

            if (parts.length === 2) {
                let hours = parseInt(parts[0], 10);
                let minutes = parseInt(parts[1], 10);

                console.log("hours: " + hours);
                console.log("minutes: " + minutes);

                // if (hours < 0 || hours > 23) {
                //     hours = '00';
                // }

                // if (minutes < 0 || minutes > 59) {
                //     minutes = '00';
                // }

                // this.time = `${hours}:${minutes}`;
            }
        },
    },
    computed: {
        validateTime: {
            get() {
                if (this.isInputActive) {
                    return this.time.toString();
                } else {
                    const regex = /^([01]\d|2[0-3]):([0-5]\d)$/; // Regex for valid time in 24-hour format.
                    if (regex.test(this.time)) {
                        return this.time;
                    } else {
                        this.time = '00:00';
                        return this.time;
                    }
                }
            },
            set(modifiedValue) {
                let newValue = parseFloat(modifiedValue.replace(/\./g, '').replace(/\,/g, '.'));
                if (isNaN(newValue)) {
                    newValue = 0;
                }

                this.$emit('input', newValue);
            }
        }
    }
}
</script>
