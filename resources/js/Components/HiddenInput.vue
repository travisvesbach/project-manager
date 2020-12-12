<template>
    <input class="form-input pl-1 hidden-input-color" :value="value" @input="$emit('input', $event.target.value)" ref="input" :style="{'width': inputWidth}">
</template>

<script>
    export default {
        props: ['value'],

        computed: {
            inputWidth() {
                return ((this.value.length + 1) * 7.5) + 'px';
            }
        },

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

