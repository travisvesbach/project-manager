<template>
    <app-layout>
        <template #header>
            {{ project.name }}

            <!-- dropdown -->
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
                            <jet-dropdown-link @click.native="showingActivity = true" as="button">
                                Activity Log
                            </jet-dropdown-link>

                            <jet-dropdown-link @click.native="showingUsers = true" as="button">
                                Users
                            </jet-dropdown-link>

                            <div class="border-t dropdown-divider-color"></div>

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

        <template #subheader>
            <div class="dark:bg-gray-900 flex items-center px-10">
                <nav-button class="mx-4 pb-1 text-base" :active="layout == 'list'" title="List" @click.native="layoutButton = 'list'">
                    List
                </nav-button>
                <nav-button class="mx-4 pb-1 text-base" :active="layout == 'board'" title="Board" @click.native="layoutButton = 'board'">
                    Board
                </nav-button>
                <jet-dropdown class="mx-4 pb-1" align="left" width="48">
                    <template #trigger>
                        <button class="flex link link-color">
                            <span class="text-base">{{ completedFilter }}</span>
                            <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </template>

                    <template #content>
                        <jet-dropdown-link @click.native="completedFilter = 'Incomplete'" as="button">
                            <svg v-if="completedFilter == 'Incomplete'" class="h-5 absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-6">Incomplete Tasks</span>
                        </jet-dropdown-link>

                        <jet-dropdown-link @click.native="completedFilter = 'Completed'" as="button">
                            <svg v-if="completedFilter == 'Completed'" class="h-5 absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-6">Completed Tasks</span>
                        </jet-dropdown-link>

                        <jet-dropdown-link @click.native="completedFilter = 'All'" as="button">
                            <svg v-if="completedFilter == 'All'" class="h-5 absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-6">All Tasks</span>
                        </jet-dropdown-link>
                    </template>
                </jet-dropdown>
                <jet-dropdown class="mx-4 pb-1" align="left" width="48" v-if="completedFilter == 'Incomplete'">
                    <template #trigger>
                        <button class="flex link link-color">
                            <span class="text-base">Sort</span>
                            <svg class="fill-current h-4 w-4 ml-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </template>

                    <template #content>
                        <jet-dropdown-link @click.native="sort = null" as="button">
                            <svg v-if="sort == null" class="h-5 absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-6">Drag & Drop</span>
                        </jet-dropdown-link>

                        <jet-dropdown-link @click.native="sort = 'due date'" as="button">
                            <svg v-if="sort == 'due date'" class="h-5 absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-6">Due Date</span>
                        </jet-dropdown-link>

                        <jet-dropdown-link @click.native="sort = 'alphabetical'" as="button">
                            <svg v-if="sort == 'alphabetical'" class="h-5 absolute" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="ml-6">Alphabetical</span>
                        </jet-dropdown-link>
                    </template>
                </jet-dropdown>
            </div>
        </template>

<!--         <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8 text-color">
                {{ project.description }}
            </div>
        </div> -->

        <div class="flex-1 relative overflow-x-hidden">
            <div v-outside-click="{ exclude: ['taskDetails', 'datePicker'], handler: 'closeDetails'}">

                <div v-if="layout == 'list'" >
                    <draggable v-model="project.sections" @change="updateSectionWeights" handle=".drag-section">
                        <section-list v-for="(section, key) in project.sections" v-bind:section="section" v-bind:project="project" v-bind:key="key" v-bind:sort="sort" v-bind:completedFilter="completedFilter" @show="showTask" @updateTaskWeights="updateTaskWeights" class="mt-5"/>
                    </draggable>

                    <section-list-new v-bind:project="project" class="mt-12" />
                </div>

                <div v-if="layout == 'board'" class="flex overflow-x-auto">
                    <draggable class="inline-block flex" v-model="project.sections" @change="updateSectionWeights"  handle=".drag-section">
                        <section-board v-for="(section, key) in project.sections" v-bind:section="section" v-bind:project="project" v-bind:key="key" v-bind:sort="sort" v-bind:completedFilter="completedFilter" @show="showTask" @updateTaskWeights="updateTaskWeights"/>
                    </draggable>

                    <section-board-new v-bind:project="project" />
                </div>

                <task-details v-bind:task="showingTask" @close="showingTask = false" ref="taskDetails"/>
            </div>

        </div>


        <!-- activity -->
        <jet-dialog-modal :show="showingActivity" @close="showingActivity = false" >
            <template #title>
                Project Activity Log
            </template>

            <template #content>
                <p v-for="activity in project.activity.slice().reverse()" class="my-3">
                    <activity-item v-bind:activity="activity" />
                </p>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="showingActivity = false">
                    Close
                </jet-secondary-button>
            </template>
        </jet-dialog-modal>

        <!-- users -->
        <jet-dialog-modal :show="showingUsers" @close="showingUsers = false" >
            <template #title>
                Project Users
            </template>

            <template #content>
                <div v-if="filteredUsers && filteredUsers.length > 0 && $page.user.id == project.owner.id" class="flex items-center">
                    <select-input id="user" class="mr-4" v-model="userForm.id" v-bind:options="filteredUsers" v-bind:placeholder="'-- select user --'" required/>
                    <jet-button type="submit" size="small" :class="{ 'opacity-25': userForm.processing }" :disabled="userForm.processing" @click.native="addUser">
                        Add User
                    </jet-button>
                </div>
                <p class="my-3 text-lg heading-color">
                    Owner
                </p>
                <p class="my-3">
                    <span>{{ project.owner.name }}</span>
                    <span class="text-secondary-color ml-1">({{ project.owner.email }})</span>
                </p>
                <div v-if="project.users.length > 0">
                    <hr>
                    <p class="my-3 text-lg heading-color">
                        Members
                    </p>
                    <div v-for="user in project.users" class="my-3">
                        <p class="flex items-center" :class="{ 'opacity-25': removeUserForm.processing && removeUserForm.id == user.id }">
                            <span>{{ user.name }}</span>
                            <span v-if="user.email" class="text-secondary-color ml-1">({{ user.email }})</span>

                            <span v-if="$page.user.id == project.owner.id" class="inline-block ml-2">
                                <jet-dropdown align="center" width="48" position="fixed">
                                    <template #trigger>
                                        <button class="flex link link-color">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content >
                                        <jet-dropdown-link :disabled="removeUserForm.processing" @click.native="removeUser(user)" as="button">
                                            Remove from project
                                        </jet-dropdown-link>
                                    </template>
                                </jet-dropdown>
                            </span>
                        </p>
                    </div>
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="showingUsers = false">
                    Close
                </jet-secondary-button>
            </template>
        </jet-dialog-modal>

        <!-- edit modal -->
        <modal-form :show="editingProject" @close="editingProject = false" @submitted="updateProject">
            <template #title>
                Edit Project
            </template>

            <template #content>
                <project-form v-model="form" />
            </template>

            <template #actions>
                <jet-secondary-button @click.native="editingProject = false">
                    Cancel
                </jet-secondary-button>
                <jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
    import JetDialogModal from '@/Jetstream/DialogModal'
    import TaskRow from '@/Components/TaskRow'
    import TaskRowNew from '@/Components/TaskRowNew'
    import TaskDetails from '@/Components/TaskDetails'
    import ActivityItem from '@/Components/ActivityItem'
    import SelectInput from '@/Components/SelectInput'
    import SectionList from '@/Components/SectionList'
    import SectionListNew from '@/Components/SectionListNew'
    import ProjectForm from '@/Components/ProjectForm'
    import SectionBoard from '@/Components/SectionBoard'
    import SectionBoardNew from '@/Components/SectionBoardNew'
    import NavButton from '@/Components/NavButton'

    import draggable  from 'vuedraggable'

    import OutsideClick from '@/Directives/OutsideClick'

    export default {
        props: ['project', 'users'],

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
            JetDialogModal,
            TaskRow,
            TaskRowNew,
            TaskDetails,
            ActivityItem,
            SelectInput,
            SectionList,
            SectionListNew,
            draggable,
            ProjectForm,
            SectionBoard,
            SectionBoardNew,
            NavButton,
        },

        directives: {
            OutsideClick
        },

        data() {
            return {
                showingActivity: false,
                editingProject: false,
                confirmingDeleteProject: false,
                showingTask: false,
                showingUsers: false,
                layoutButton: false,
                sort: null,
                completedFilter: 'Incomplete',
                form: this.$inertia.form({
                    id: this.project.id,
                    name: this.project.name,
                    description: this.project.description,
                    layout: this.project.layout,
                }),
                userForm: this.$inertia.form({
                    id: null,
                }),
                removeUserForm: this.$inertia.form({
                    id: null,
                }),
            }
        },
        computed: {
            filteredUsers() {
                if(!this.users) {
                    return false
                } else if(this.users.length == this.project.users.length ) {
                    return false;
                } else if(this.users.length > 0 && this.project.users.length > 0) {
                    return this.users.filter(x => this.project.users.some(y => x.id != y.id));
                } else {
                    return this.users;
                }
            },
            layout() {
                if(this.layoutButton) {
                    return this.layoutButton;
                }else if(this.$page.user.project_layout != "project default") {
                    return this.$page.user.project_layout;
                } else {
                    return this.project.layout;
                }
            },
        },
        watch: {
            project: function() {
                for(let i=0;i<this.project.tasks.length;i++) {
                    if(this.showingTask && this.project.tasks[i].id == this.showingTask.id) {
                        this.showingTask = this.project.tasks[i];
                    }
                }
                this.userForm.id = null;

                this.form =  this.$inertia.form({
                    id: this.project.id,
                    name: this.project.name,
                    description: this.project.description,
                    layout: this.project.layout,
                });
            },
        },
        methods: {
            showTask(task) {
                this.showingTask = task;
            },
            updateProject() {
                this.form.patch(this.project.path);
                this.editingProject = false;
            },
            deleteProject() {
                this.$inertia.delete(this.project.path);
            },
            closeDetails() {
                this.showingTask = false;
            },
            addUser() {
                this.userForm.post(this.project.path + '/users');
            },
            removeUser(user) {
                this.removeUserForm.id = user.id;
                this.removeUserForm.delete(this.project.path + '/users/' + user.id, {
                    preserveState: true,
                });
            },
            updateSectionWeights(target) {
                let newIndex = target.moved.newIndex + 1;
                this.project.sections.forEach(function(section, i) {
                    if(section.id == target.moved.element.id) {
                        section.weight = newIndex;
                    } else if(section.weight == newIndex && section.weight < i + 1) {
                        section.weight++;
                    } else {
                        section.weight = i + 1;
                    }
                });
                let weightForm = this.$inertia.form({
                    sections_array: this.project.sections.map(function (obj) {
                            return obj.id;
                        }),
                });

                weightForm.patch(this.project.path + '/updatesectionweights', {
                    preserveState: true,
                });
            },
            updateTaskWeights(target) {
                let oldIndex = null;
                let newIndex = null;
                let targetId = null;
                if(target.added) {
                    oldIndex = target.added.oldIndex + 1;
                    newIndex = target.added.newIndex + 1;
                    targetId = target.added.element.id;
                } else if(target.moved) {
                    oldIndex = target.moved.oldIndex + 1;
                    newIndex = target.moved.newIndex + 1;
                    targetId = target.moved.element.id;
                }

                if(newIndex && targetId) {
                    this.project.sections.forEach(function(section) {
                        let weight = 1;
                        section.tasks.forEach(function(task) {
                            task.section_id = section.id;
                            // if section contains the modified task
                            if(section.tasks.some(x => x.id === targetId) && oldIndex) {
                                if(task.completed) {
                                    task.weight = null;
                                } else if(task.id == targetId) {
                                    task.weight = newIndex;
                                } else if(task.weight > oldIndex && task.weight <= newIndex) {
                                    task.weight--;
                                } else if(task.weight < oldIndex && task.weight >= newIndex) {
                                    task.weight++;
                                }
                            } else {
                                if(task.completed) {
                                    task.weight = null;
                                } else {
                                    task.weight = weight;
                                    weight++;
                                }
                            }
                        });
                    });

                    let weightForm = this.$inertia.form({
                        tasks_array: this.project.sections.map(function (section) {
                                return [ section.id, section.tasks.map(function (task) {
                                    return [task.id, task.weight];
                                })];
                            }),
                    });

                    weightForm.patch(this.project.path + '/updatetaskweights', {
                        preserveState: true,
                    });

                }



            }
        }
    }
</script>
