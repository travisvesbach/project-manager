<template>
    <div class="w-64 inline-block align-top">
        <div class="flex items-center hover-trigger">
            <svg class="ml-3 h-4 inline-block text-secondary-color drag-section cursor-move hover-target" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>
            <input-hidden v-model="form.name" class="ml-1 text-lg heading-color" @blur.native="updateSection()" @keyup.enter.native="$event.target.blur()"/>

            <!-- dropdown -->
            <jet-dropdown align="left" width="48" v-if="project.sections.length > 1" class="hover-target">
                <template #trigger>
                    <button class="flex link link-color">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </template>

                <template #content>
                    <jet-dropdown-link @click.native="confirmingDeleteSection = true" as="button">
                        Delete Section
                    </jet-dropdown-link>
                </template>
            </jet-dropdown>
        </div>

        <div class="mx-2">
            <draggable :list="section.tasks" @change="updateTaskWeights" handle=".drag-task" group="tasks" v-if="sort == null">
                <div v-for="(task, index) in filteredTasks">
                    <task-card v-bind:task="task" v-bind:section="section" @show="$emit('show', task)" @focusnew="focusNew()"/>
                </div>
            </draggable>

            <div v-for="(task, index) in filteredTasks" v-if="sort">
                <task-card v-bind:task="task" v-bind:section="section" @show="$emit('show', task)" @focusnew="focusNew()"/>
            </div>


            <div>
                <task-card-new v-bind:section="section" ref="newTaskInput"/>
            </div>
        </div>

        <!-- delete confirmation -->
        <jet-confirmation-modal :show="confirmingDeleteSection" @close="confirmingDeleteSection = false">
            <template #title>
                Delete Section
            </template>

            <template #content>
                <p>
                    This seciton includes {{ section.tasks.filter((x) => x.completed === true).length }} completed tasks and {{ section.tasks.filter((x) => x.completed === false).length }} incomplete tasks.
                </p>
                <div class="mt-5">
                    <label><input type="radio" id="keep" value="keep" v-model="deleteForm.tasks"> Delete this section, but keep all its tasks</label>
                    <br>
                    <br>
                    <label><input type="radio" id="delete" value="delete" v-model="deleteForm.tasks"> Delete this section and delete all its tasks</label>
                </div>

            </template>

            <template #footer>
                <jet-secondary-button @click.native="confirmingDeleteSection = false">
                    Cancel
                </jet-secondary-button>
                <jet-danger-button class="ml-2" @click.native="deleteSection" :class="{ 'opacity-25': deleteForm.processing }" :disabled="deleteForm.processing">
                    Delete Section
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>

    </div>
</template>

<script>

    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
    import JetButton from '@/Jetstream/Button'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import TaskCard from '@/Components/TaskCard'
    import TaskCardNew from '@/Components/TaskCardNew'
    import InputHidden from '@/Components/InputHidden'

    import draggable  from 'vuedraggable'

    export default {
        props: ['section', 'project', 'sort', 'completedFilter'],

        components: {
            JetDropdown,
            JetDropdownLink,
            JetConfirmationModal,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
            TaskCard,
            TaskCardNew,
            InputHidden,
            draggable,
        },

        data() {
            return {
                confirmingDeleteSection: false,
                form: this.$inertia.form({
                    id: this.section.id,
                    name: this.section.name,
                }),
                deleteForm: this.$inertia.form({
                    '_method': 'DELETE',
                    tasks: 'keep',
                })
            }
        },
        computed: {
            sortedTasks() {
                let output = this.section.tasks;
                if(this.sort == 'due date') {
                    output.sort(function(a, b) {
                        return (a.due_date === null) - (b.due_date === null) || + (a.due_date > b.due_date) || - (a.due_date < b.due_date);
                    });
                } else if(this.sort == 'alphabetical') {
                    output.sort((a, b) => (a.name.toLowerCase() > b.name.toLowerCase()) ? 1 : -1);
                } else {
                    output.sort(function(a, b) {
                        return (b.weight != null) - (a.weight != null) || a.weight - b.weight;
                    });
                }
                return output;
            },
            filteredTasks() {
                let output = this.sortedTasks;
                if(this.completedFilter == 'Incomplete') {
                    output = output.filter(function(task) {
                        return task.completed == false;
                    });
                } else if(this.completedFilter == 'Completed') {
                    output = output.filter(function(task) {
                        return task.completed == true;
                    });
                }
                return output;
            },
        },
        watch: {
            section: function() {
                this.form = this.$inertia.form({
                    id: this.section.id,
                    name: this.section.name,
                });
            }
        },
        methods: {
            focusNew() {
                this.$refs.newTaskInput.focus();
            },
            updateSection() {
                if(this.form.name == '') {
                    this.form.name = this.section.name;
                } else if( this.form.name != this.section.name) {
                    this.form.patch(this.section.path);
                }
            },
            updateTaskWeights(target) {
                this.$emit('updateTaskWeights', target);
            },
            deleteSection() {
                this.deleteForm.post(this.section.path);
            }
        }
    }
</script>
