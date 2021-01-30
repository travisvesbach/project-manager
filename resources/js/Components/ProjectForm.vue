<template>
    <div>
        <jet-label for="name" value="Project Name" />
        <jet-input id="name" class="mt-1 block w-full" v-model="value.name" autocomplete="new-name" required/>
        <jet-input-error :message="value.error('name')" class="mt-2" />

        <!-- Project Layout -->
        <jet-label for="layout" value="Layout" class="mt-4" />
        <select-input id="layout" class="mt=1 block w-full" v-model="value.layout" v-bind:options="['board', 'list']" />
        <jet-input-error :message="value.error('layout')" class="mt-2" />

        <jet-label for="description" value="Description" class="mt-4" />
        <textarea-input id="description" v-model="value.description" class="mt-1 h-24"/>
        <jet-input-error :message="value.error('description')" class="mt-2" />
    </div>
</template>

<script>
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import TextareaInput from '@/Components/TextareaInput'
    import SelectInput from '@/Components/SelectInput'

    export default {
        props: ['value'],

        components: {
            JetInput,
            JetInputError,
            JetLabel,
            TextareaInput,
            SelectInput,
        },
    }
</script>
