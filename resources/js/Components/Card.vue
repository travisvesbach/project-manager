<template>
    <div class="p-3 rounded-lg shadow card-color text-color">
        <div class="text-xl -mt-3 -mx-3 pt-3 px-3 pb-3 rounded-t-lg card-header-footer-color heading-color">
            <h3>
                <slot name="title"></slot>
            </h3>
        </div>
        <div class="pt-2">
            <slot></slot>
        </div>
    </div>
</template>

<script>

    export default {

    }
</script>
