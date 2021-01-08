<template>
    <div class="input-group flex items-center w-36">
        <flat-pickr class="px-1 w-28" :class="classes" v-model="date" :config="config" :placeholder="placeholder" ref="datePicker" @input="onInput"></flat-pickr>
        <button class="btn btn-default ml-1" type="button" title="Clear" data-clear v-if="date" @click="date = ''">
            <svg class="h-5 w-5 text-color" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </div>
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
                    wrap: true,
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
            },
            value: function() {
                if(this.value && this.value != this.date) {
                    this.date = this.value;
                } else if(this.id != this.currentId) {
                    this.date = this.value;
                } else {
                    this.date = '';
                }
            }
        },
        methods: {
            onInput() {
                // needed otherwise will emit when chaning between tasks
                if(this.id == this.currentId && this.value != this.date) {
                    this.$emit('input', this.date);
                } else {
                    this.currentId = this.id;
                }
            }
        }
    }
</script>
