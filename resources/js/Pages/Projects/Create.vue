<template>
    <app-layout>
        <template #header>
            Create Project
        </template>

        <centered-form @submitted="submit">

            <project-form v-model="form" />

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
    import CenteredForm from '@/Components/CenteredForm'
    import ProjectForm from '@/Components/ProjectForm'

    export default {
        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            CenteredForm,
            ProjectForm,
        },
        data() {
            return {
                form: this.$inertia.form({
                    name: null,
                    description: null,
                    layout: 'list',
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
