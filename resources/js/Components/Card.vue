<template>
    <div class="card">
        <h3 class="text-xl -mt-3 -mx-3 pt-3 px-3 pb-3 rounded-t-lg bg-gray-200 dark:text-yellow-600 dark:bg-gray-700">
            <slot name="title"></slot>
        </h3>
        <div class="pt-2">
            <slot></slot>
        </div>
    </div>
</template>

<script>

    export default {

    }
</script>
