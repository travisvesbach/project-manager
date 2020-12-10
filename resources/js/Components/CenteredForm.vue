<template>
    <form @submit.prevent="$emit('submitted')" class="w-full md:w-1/2 mx-auto py-10 sm:px-6 lg:px-8 m-2">
        <div class="md:container shadow overflow-hidden sm:rounded-lg mx-auto card-color text-color">
            <div class="p-3">
                <slot></slot>
            </div>

            <div v-if="hasActions" class="p-3 card-header-footer-color">
                <slot name="actions"></slot>
            </div>
        </div>
    </form>
</template>

<script>

    export default {
        computed: {
            hasActions() {
                return !! this.$slots.actions
            }
        }
    }
</script>
