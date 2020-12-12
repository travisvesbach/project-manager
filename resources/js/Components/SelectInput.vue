<template>
    <select class="form-input rounded-md shadow-sm form-input-color" @input="$emit('input', $event.target.value)" >
        <option :selected="value == null" disabled class="text-secondary-color">
            -- select an option --
        </option>
        <option v-for="option in options" :selected="option == value" :value="isObject(option) ?  option.id : option">
            {{ isObject(option) ?  option.name : option }}
        </option>
    </select>
</template>

<script>
    export default {
        props: ['value', 'options'],

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

