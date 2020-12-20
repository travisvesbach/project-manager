<template>
    <flat-pickr class="rounded-md" :class="classes" v-model="date" :config="config" :placeholder="placeholder" ref="datePicker"></flat-pickr>
</template>

<script>
    import flatPickr from 'vue-flatpickr-component';
    import 'flatpickr/dist/flatpickr.css';

    export default {
        props: ['value', 'placeholder', 'id', 'hidden'],
        components: {
            flatPickr,
        },
        data() {
            return {
                date: this.value,
                config: {
                    altInput: true,
                    altFormat: "F j, Y",
                    dateFormat: 'Y-m-d',
                },
            }
        },
        computed: {
            classes() {
                if(this.hidden) {
                    return 'hidden-input-color';
                }
                return 'form-input-color';
            },
        },
        watch: {
            id: function() {
                this.date = this.value;
            }
        },
    }
</script>
