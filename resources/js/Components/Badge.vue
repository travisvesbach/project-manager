<template>
    <div class="inline-block px-1 mx-1 text-sm text-color rounded-md" :class="colorClassesComputed">
        {{ value }}
    </div>
</template>

<script>

    export default {
        props: ['value', 'colorClasses'],


        computed: {
            colorClassesComputed() {
                if(this.colorClasses) {
                    return this.colorClasses;
                } else {
                    return 'bg-gray-200 dark:bg-gray-600 text-color';
                }
            },
        },
    }
</script>
