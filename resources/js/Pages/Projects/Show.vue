<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ project.name }}

                <!-- Create Dropdown -->
                <div class="items-center inline-block">
                    <div class="ml-3 relative">
                        <jet-dropdown align="left" width="48">
                            <template #trigger>
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <jet-dropdown-link @click.native="editingProject = true" as="button">
                                    Edit Project
                                </jet-dropdown-link>
                            </template>
                        </jet-dropdown>
                    </div>
                </div>
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                {{ project.description }}
            </div>
        </div>

            <modal-form :show="editingProject" @close="editingProject = false" @submitted="updateProject">
                <template #title>
                    Edit Project
                </template>

                <template #content>
                        <jet-label for="name" value="Project Name" />
                        <jet-input id="name" class="mt-1 block w-full" v-model="form.name" autocomplete="new-name" />
                        <jet-input-error :message="form.error('name')" class="mt-2" />

                        <jet-label for="description" value="Description" />
                        <textarea id="description" v-model="form.description" class="form-input rounded-md shadow-sm mt-1 block w-full" />
                        <jet-input-error :message="form.error('description')" class="mt-2" />
                </template>

                <template #actions>
                    <jet-button @click.native="updateProject()">
                        Save
                    </jet-button>
                    <jet-secondary-button @click.native="editingProject = false">
                        Cancel
                    </jet-secondary-button>
                </template>
            </modal-form>

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import ModalForm from '@/Components/ModalForm'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetButton from '@/Jetstream/Button'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetInput from '@/Jetstream/Input'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'

    export default {
        props: ['project'],

        components: {
            AppLayout,
            ModalForm,
            JetDropdown,
            JetDropdownLink,
            JetButton,
            JetSecondaryButton,
            JetActionMessage,
            JetInput,
            JetInputError,
            JetLabel,
        },
        data() {
            return {
                editingProject: false,
                form: this.$inertia.form({
                    id: this.project.id,
                    name: this.project.name,
                    description: this.project.description,
                }),
            }
        },
        methods: {
            updateProject() {
                this.$inertia.patch('/projects/' + this.form.id, this.form);
                this.editingProject = false;
            }
        }
    }
</script>
