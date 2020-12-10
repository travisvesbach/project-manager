<template>
    <div>
        <button type="submit" class="block w-full px-4 py-2 text-sm leading-5 text-left focus:outline-none transition duration-150 ease-in-out " :class="colorClasses" v-if="as == 'button'">
            <slot></slot>
        </button>

        <inertia-link :href="href" class="block px-4 py-2 text-sm leading-5 focus:outline-none transition duration-150 ease-in-out" :class="colorClasses" v-else>
            <slot></slot>
        </inertia-link>
    </div>
</template>

<script>
    export default {
        props: ['href', 'as'],
        computed: {
            colorClasses() {
                return 'text-gray-700 hover:bg-gray-100 focus:bg-gray-100 dark:text-yellow-600 dark:hover:bg-gray-600'
            }
        }
    }
</script>
