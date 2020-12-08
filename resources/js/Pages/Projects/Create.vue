<template>
    <app-layout>
        <template #header>
            Create Project
        </template>

        <centered-form @submitted="submit">

            <jet-label for="name" value="Project Name" />
            <jet-input id="name" class="mt-1 block w-full" v-model="form.name" autocomplete="new-name" />
            <jet-input-error :message="form.error('name')" class="mt-2" />

            <jet-label for="description" value="Description" />
            <textarea id="description" v-model="form.description" class="form-input rounded-md shadow-sm mt-1 block w-full" />
            <jet-input-error :message="form.error('description')" class="mt-2" />

            <template #actions>
                <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                    Saved.
                </jet-action-message>
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Save
                </jet-button>
            </template>

        </centered-form>

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetButton from '@/Jetstream/Button'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import CenteredForm from '@/Components/CenteredForm'

    export default {
        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            JetInput,
            JetInputError,
            JetLabel,
            CenteredForm,
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
                this.$inertia.post('/projects', this.form);
            },
        },
    }
</script>
