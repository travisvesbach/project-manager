<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <form @submit.prevent="$emit('submitted')">
            <div class="px-6 py-4">
                <div class="text-lg">
                    <slot name="title">
                    </slot>
                </div>

                <div class="mt-4">
                    <slot name="content">
                    </slot>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-100 text-right">
                <slot name="actions">
                </slot>
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
        }
    }
</script>
