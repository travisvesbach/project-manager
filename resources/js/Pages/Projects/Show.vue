<template>
    <app-layout>
        <template #header>
            {{ project.name }}

            <!-- Create Dropdown -->
            <div class="items-center inline-block">
                <div class="ml-3 relative">
                    <jet-dropdown align="left" width="48">
                        <template #trigger>
                            <button class="flex link link-color">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </template>

                        <template #content>
                            <jet-dropdown-link @click.native="editingProject = true" as="button">
                                Edit Project
                            </jet-dropdown-link>
                            <jet-dropdown-link @click.native="confirmingDeleteProject = true" as="button">
                                Delete Project
                            </jet-dropdown-link>
                        </template>
                    </jet-dropdown>
                </div>
            </div>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                {{ project.description }}
            </div>
        </div>

        <div class="border-t-2 border-color">
            <div class="border-b-2 border-color" v-for="(task, index) in project.tasks">
                <task-row v-bind:task="task" />
            </div>
        </div>

        <!-- edit modal -->
        <modal-form :show="editingProject" @close="editingProject = false" @submitted="updateProject">
            <template #title>
                Edit Project
            </template>

            <template #content>
                    <jet-label for="name" value="Project Name" />
                    <jet-input id="name" class="mt-1 block w-full" v-model="form.name" autocomplete="new-name" />
                    <jet-input-error :message="form.error('name')" class="mt-2" />

                    <jet-label for="description" value="Description" class="mt-4" />
                    <textarea-input id="description" v-model="form.description" />
                    <jet-input-error :message="form.error('description')" class="mt-2" />
            </template>

            <template #actions>
                <jet-secondary-button @click.native="editingProject = false">
                    Cancel
                </jet-secondary-button>
                <jet-button @click.native="updateProject()">
                    Save
                </jet-button>
            </template>
        </modal-form>

        <!-- delete confirmation -->
        <jet-confirmation-modal :show="confirmingDeleteProject" @close="confirmingDeleteProject = false">
            <template #title>
                Delete Project
            </template>

            <template #content>
                Are you sure you want to delete this project? All of this project's tasks will be deleted as well.
            </template>

            <template #footer>
                <jet-secondary-button @click.native="confirmingDeleteProject = false">
                    Cancel
                </jet-secondary-button>
                <jet-danger-button class="ml-2" @click.native="deleteProject" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Delete Project
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import ModalForm from '@/Components/ModalForm'
    import TextareaInput from '@/Components/TextareaInput'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetButton from '@/Jetstream/Button'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
    import TaskRow from '@/Components/TaskRow'

    export default {
        props: ['project'],

        components: {
            AppLayout,
            ModalForm,
            TextareaInput,
            JetDropdown,
            JetDropdownLink,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
            JetActionMessage,
            JetInput,
            JetInputError,
            JetLabel,
            JetConfirmationModal,
            TaskRow,
        },
        data() {
            return {
                editingProject: false,
                confirmingDeleteProject: false,
                form: this.$inertia.form({
                    id: this.project.id,
                    name: this.project.name,
                    description: this.project.description,
                }),
            }
        },
        methods: {
            updateProject() {
                this.$inertia.patch(this.project.path, this.form);
                this.editingProject = false;
            },
            deleteProject() {
                this.$inertia.delete(this.project.path);
            }
        }
    }
</script>
