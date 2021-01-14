<template>
    <transition name="slidein-left">
        <div class="fixed right-0 top-16 bottom-0 max-w-xl p-2 w-full sm:w-1/2 flex flex-col card-color text-color" v-if="task">
            <div>
                <div class="flex space-between mb-2">
                    <button class="inline-flex items-center p-1 bg-transparent border rounded-md font-semibold text-xs text-black uppercase tracking-widest hover:border-green-500 hover:ring-green-500 focus:outline-none focus:border-green-500 focus:ring-green transition duration-150 dark:text-gray-300 " :class="form.completed ? 'bg-green-500 border-green-500 dark:bg-green-500' : 'border-gray-300'" @click="toggleCompleted()">
                        <svg class="h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg> {{ completedButtonText }}
                    </button>

                    <div class="ml-auto flex items-center">
                        <jet-dropdown align="right" width="48" class="inline-block mr-4">
                            <template #trigger>
                                <button class="flex link link-color">
                                    <svg class="fill-current h-6 w-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content >
                                <jet-dropdown-link @click.native="deleteTask()" as="button">
                                    <svg class="h-6 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg> Delete Task
                                </jet-dropdown-link>
                            </template>
                        </jet-dropdown>

                        <button class="link-color" @click="$emit('close')">
                            <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <textarea-input class="rounded-lg text-3xl heading-color" v-bind:hidden="true" v-model="form.name" ref="name" @blur.native="updateTask()" @keydown.enter.prevent.native="$event.target.blur()"/>

            </div>
            <div class="flex-1 flex flex-col overflow-y-auto">
                <div class="sm:flex mt-2">
                    <label class="sm:w-1/4 pl-1">Due Date</label>
                    <div class="sm:w-3/4">
                        <date-picker class="rounded-md" v-model="form.due_date" v-bind:id="task.id" v-bind:placeholder="'No due date'" v-bind:hidden="true" @input="updateTask()"/>
                    </div>
                </div>

                <div class="sm:flex mt-2">
                    <label class="sm:w-1/4 pl-1">Description</label>
                    <div class="sm:w-3/4">
                        <editor v-model="form.description" v-bind:id="task.id" v-bind:hidden="true" @blurred="updateTask()"/>
                    </div>
                </div>

                <div class="mt-auto">
                    <p class="my-3" v-for="activity in task.activity">
                        <activity-item v-bind:activity="activity" v-bind:for="'task'"/>
                    </p>

                </div>

            </div>



        </div>
    </transition>
</template>

<script>

    import JetButton from '@/Jetstream/Button'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import InputHidden from '@/Components/InputHidden'
    import TextareaInput from '@/Components/TextareaInput'
    import Editor from '@/Components/Editor'
    import DatePicker from '@/Components/DatePicker'
    import ActivityItem from '@/Components/ActivityItem'

    export default {
        props: ['task'],

        components: {
            JetButton,
            JetDropdown,
            JetDropdownLink,
            InputHidden,
            TextareaInput,
            Editor,
            DatePicker,
            ActivityItem,
        },

        data() {
            return {
                form: this.$inertia.form({
                    id: this.task.id,
                    name: this.task.name,
                    description: this.task.description,
                    completed: this.task.completed,
                    due_date: this.task.due_date,
                }),
            }
        },
        watch: {
            task: function() {
                this.form = this.$inertia.form({
                    id: this.task.id,
                    name: this.task.name,
                    description: this.task.description,
                    completed: this.task.completed,
                    due_date: this.task.due_date,
                });
            }
        },
        computed: {
            completedButtonText() {
                return this.task.completed ? 'Completed' : 'Mark Complete';
            },
        },
        methods: {
            updateTask(description = false) {
                if(description != false) {
                    this.form.description = description;
                }
                if(this.form.name == '') {
                    this.form.name = this.task.name;
                } else if( this.form.name != this.task.name ||
                        this.form.description != this.task.description ||
                        this.form.completed != this.task.completed ||
                        this.form.due_date != this.task.due_date
                    ) {
                    this.form.patch(this.task.path);
                }
            },
            toggleCompleted() {
                this.task.completed = !this.task.completed ? true : false;
                this.form.completed = this.task.completed;
                this.form.patch(this.task.path);
            },
            deleteTask() {
                this.$inertia.delete(this.task.path);
                this.$emit('close');
            },
        }
    }
</script>
