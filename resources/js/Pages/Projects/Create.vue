<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Project
            </h2>
        </template>

        <form @submit.prevent="submit" class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 m-2">
            <jet-label for="name" value="Project Name" />
            <jet-input id="name" class="mt-1 block w-full" v-model="form.name" autocomplete="new-name" />
            <jet-input-error :message="form.error('name')" class="mt-2" />

            <jet-label for="description" value="Description" />
            <textarea id="description" v-model="form.description" class="form-input rounded-md shadow-sm mt-1 block w-full" />
            <jet-input-error :message="form.error('description')" class="mt-2" />

            <jet-action-message :on="form.recentlySuccessful" class="mr-3 mt-2">
                Saved.
            </jet-action-message>
            <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </jet-button>

        </form>

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            JetInput,
            JetInputError,
            JetLabel,
        },
        data() {
            return {
                form: this.$inertia.form({
                    name: null,
                    description: null,
                }),
            }
        },
        methods: {
            submit() {
                this.$inertia.post('/projects', this.form)
            },
        },
    }
</script>
