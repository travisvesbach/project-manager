<template>
    <textarea class="form-input rounded-md shadow-sm mt-1 block w-full pl-1 form-input-color" :value="value" @input="$emit('input', $event.target.value)" ref="textarea"></textarea>
</template>

<script>
    export default {
        props: ['value'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

