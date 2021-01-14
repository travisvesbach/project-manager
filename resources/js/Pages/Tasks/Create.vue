<template>
    <app-layout>
        <template #header>
            Create Task
        </template>

        <centered-form @submitted="submit">

            <jet-label for="name" value="Task Name" />
            <jet-input id="name" class="mt-1 block w-full" v-model="form.name" autocomplete="new-name" required/>
            <jet-input-error :message="form.error('name')" class="mt-2" />

            <jet-label for="project" value="Project" class="mt-4" />
            <select-input id="project" class="mt-1 block w-full" v-model="form.project" v-bind:options="projects" required/>
            <jet-input-error :message="form.error('project')" class="mt-2" />

            <jet-label for="due_date" value="Due Date" class="mt-4" />
            <date-picker id="due_date" v-model="form.due_date" />
            <jet-input-error :message="form.error('due_date')" class="mt-2" />

            <jet-label for="description" value="Description" class="mt-4" />
            <editor id="description" v-model="form.description" />
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
    import TextareaInput from '@/Components/TextareaInput'
    import SelectInput from '@/Components/SelectInput'
    import Editor from '@/Components/Editor'
    import DatePicker from '@/Components/DatePicker'

    export default {
        props: ['projects'],

        components: {
            AppLayout,
            JetActionMessage,
            JetButton,
            JetInput,
            JetInputError,
            JetLabel,
            CenteredForm,
            TextareaInput,
            SelectInput,
            Editor,
            DatePicker,
        },
        data() {
            return {
                form: this.$inertia.form({
                    name: null,
                    project: null,
                    due_date: null,
                    description: null,
                }),
            }
        },
        methods: {
            submit() {
                this.form.post('/projects/' + this.form.project + '/tasks');
            },
        },
    }
</script>
