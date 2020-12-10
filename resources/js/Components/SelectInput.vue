<template>
    <select class="form-input rounded-md shadow-sm form-input-color" @input="$emit('input', $event.target.value)" >
        <option v-for="option in options" :selected="option == value">
            {{ option }}
        </option>
    </select>
</template>

<script>
    export default {
        props: ['value', 'options'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

