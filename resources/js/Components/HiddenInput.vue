<template>
    <input class="form-input px-1 hidden-input-color" :value="value" @input="$emit('input', $event.target.value)" ref="input" v-autowidth="{maxWidth: 'auto', minWidth: '30px', comfortZone: 0}" @keyup.enter="$emit('keyup-enter')">
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

