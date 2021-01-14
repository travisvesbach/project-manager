<template>
    <button class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out" :class="classes">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: ['active'],

        computed: {
            classes() {
                return this.active
                            ? 'text-gray-900 border-purple-400 focus:border-purple-700 dark:text-yellow-500 dark:border-yellow-500 dark:focus:border-yellow-400'
                            : 'border-transparent dark:hover:border-yellow-500 dark:focus:border-yellow-400 link-color'
            }
        }
    }
</script>
