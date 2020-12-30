<template>
    <flat-pickr class="px-1" :class="classes" v-model="date" :config="config" :placeholder="placeholder" ref="datePicker" @input="onInput"></flat-pickr>
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
                currentId: this.id,
                date: this.value,
                config: {
                    altInput: true,
                    altFormat: "M j, Y",
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
        methods: {
            onInput() {
                // needed otherwise will emit when chaning between tasks
                if(this.id == this.currentId) {
                    this.$emit('input', this.date);
                } else {
                    this.currentId = this.id;
                }
            }
        }
    }
</script>
