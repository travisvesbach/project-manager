<template>
    <div class="inline-block">
        <v-select class="rounded-md shadow-sm form-input-color" v-model="selected" :reduce="obj => obj.id" label="name" :options="options" :placeholder="computedPlaceholder" @input="$emit('input', selected)" v-if="isObject(options[0])" />
        <v-select class="rounded-md shadow-sm form-input-color" v-model="selected" :options="options" :placeholder="computedPlaceholder" @input="$emit('input', selected)" v-if="!isObject(options[0])" />
    </div>
</template>

<script>
    import vSelect from 'vue-select'
    import 'vue-select/dist/vue-select.css';

    export default {
        props: ['value', 'options', 'placeholder'],

        components: {
            vSelect
        },

        data() {
            return {
                selected: this.value
            }
        },

        computed: {
            computedPlaceholder() {
                return this.placeholder ?? '-- select an option --';
            }
        },

        watch: {
            value: function() {
                this.selected = this.value;
            }
        },

        methods: {
            focus() {
                this.$refs.input.focus()
            },
            isObject(option) {
                return Object.prototype.toString.call(option) === '[object Object]' ? true : false;
            }
        }
    }
</script>

<style>
    .style-chooser .vs__dropdown-toggle {
        color: white;
    }
</style>
