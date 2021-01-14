<template>
    <textarea class="form-input rounded-md block w-full pl-1 h-0" :class="classes" :value="value" @input="$emit('input', $event.target.value)" ref="textarea"></textarea>
</template>

<script>
    export default {
        props: ['value', 'hidden'],

        updated: function() {
            this.resize();
        },

        mounted() {
            this.resize();
        },

        computed: {
            classes() {
                if(this.hidden) {
                    return 'hidden-input-color';
                }
                return 'form-input-color';
            },
        },

        methods: {
            focus() {
                this.$refs.textarea.focus();
            },
            resize() {
                if(this.$refs.textarea)  {
                    this.$refs.textarea.style.minHeight = "24px";
                    if(this.$refs.textarea.scrollHeight > 24) {
                        this.$refs.textarea.style.minHeight = this.$refs.textarea.scrollHeight + 'px';
                    }
                }
            }
        }
    }
</script>

