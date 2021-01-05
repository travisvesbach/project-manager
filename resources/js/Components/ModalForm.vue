<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <form @submit.prevent="$emit('submitted')" class="flex flex-col max-h-full">
            <div class="px-6 py-4 text-lg heading-color card-header-footer-color">
                <slot name="title"></slot>
            </div>

            <div class="px-6 py-4 flex-1 overflow-y-auto card-color text-color" ref="scrollable">
                <slot name="content"></slot>
            </div>

            <div class="px-6 py-4 bg-gray-100 text-right card-header-footer-color">
                <slot name="actions"></slot>
            </div>
        </form>
    </modal>
</template>

<script>
    import Modal from '@/Jetstream/Modal'

    export default {
        components: {
            Modal,
        },

        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: '2xl'
            },
            closeable: {
                default: true
            },
        },
        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        },
        methods: {
            close() {
                this.$emit('close')
            },
        },
        watch: {
            show: function() {
                this.$refs['scrollable'].scrollTop = 0;
            }
        },
    }
</script>
