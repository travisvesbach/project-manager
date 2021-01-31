<template>
    <div class="inline-block px-1 mx-1 text-sm text-color rounded-md" :class="colorClasses">
        {{ value }}
    </div>
</template>

<script>

    export default {
        props: ['value', 'color'],

        computed: {
            colorClasses() {
                switch(this.color) {
                    case 'light-gray':
                        return 'bg-gray-300 dark:bg-gray-500 text-color'
                        break;
                    default:
                        return 'bg-gray-200 dark:bg-gray-600 text-color';
                }
            },
        },
    }
</script>
