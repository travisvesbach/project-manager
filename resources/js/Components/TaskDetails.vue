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

                    <button class="ml-auto link-color" @click="$emit('close')">
                        <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <input-hidden class="rounded-lg text-3xl heading-color" v-model="form.name" ref="name" @blur.native="updateTask()" @keyup.enter.native="$event.target.blur()"/>

            </div>
            <div class="flex-1 overflow-y-auto">
                <div class="sm:flex mt-2">
                    <label class="sm:w-1/4 pl-1">Due Date</label>
                    <div class="sm:w-3/4">
                        <date-picker class="rounded-md" v-model="form.due_date" v-bind:id="task.id" v-bind:placeholder="'No Due Date'" v-bind:hidden="true" @input="updateTask()"/>
                    </div>
                </div>

                <div class="sm:flex mt-2">
                    <label class="sm:w-1/4 pl-1">Description</label>
                    <div class="sm:w-3/4">
                        <editor v-model="form.description" v-bind:id="task.id" v-bind:hidden="true" @blurred="updateTask()"/>
                    </div>
                </div>

                <div class="">
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
    import InputHidden from '@/Components/InputHidden'
    import TextareaInput from '@/Components/TextareaInput'
    import Editor from '@/Components/Editor'
    import DatePicker from '@/Components/DatePicker'
    import ActivityItem from '@/Components/ActivityItem'

    export default {
        props: ['task'],

        components: {
            JetButton,
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
                    this.$inertia.patch(this.task.path, this.form);
                }
            },
            toggleCompleted() {
                this.task.completed = !this.task.completed ? true : false;
                this.form.completed = this.task.completed;
                this.$inertia.patch(this.task.path, this.form);
            },
        }
    }
</script>
