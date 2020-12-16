<template>
    <textarea class="form-input rounded-md shadow-sm mt-1 block w-full pl-1 form-input-color h-0" :style="inputStyle" :value="value" @input="$emit('input', $event.target.value)" ref="textarea"></textarea>
</template>

<script>
    export default {
        props: ['value'],

        data() {
            return {
                minHeight: 0,
            }
        },

        computed: {
            inputStyle() {
                return {
                    'min-height': this.minHeight,
                }
            }
        },

        watch: {
            value: function() {
                this.resize();
            }
        },

        mounted() {
            this.resize();
        },

        methods: {
            focus() {
                this.$refs.textarea.focus()
            },
            resize() {
                this.minHeight = '0px';
                this.minHeight = this.$refs.textarea.scrollHeight + 'px';
            }
        }
    }
</script>

