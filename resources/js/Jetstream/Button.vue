<template>
    <button :type="type" class="inline-flex items-center bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring-gray transition ease-in-out duration-150 dark:bg-gray-300 dark:text-black dark:hover:bg-gray-200 dark:active:bg-gray-400 dark:focus:border-gray-400 dark:focus:ring-yellow" :class="sizeClasses">
        <slot></slot>
    </button>
</template>

<script>
    export default {
        props: {
            type: {
                type: String,
                default: 'submit',
            },
            size: {
                default: null,
            }
        },

        computed: {
            sizeClasses() {
                if (this.size == 'small') {
                    return 'px-2 py-1';
                } else {
                    return 'px-4 py-2';
                }
            },
        }
    }
</script>
