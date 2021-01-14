<template>
    <button class="w-full relative flex flex-col focus:outline-none focus:border-0 my-2 p-2 rounded-lg card-color" :class="this.task.completed ? 'text-secondary-color' : 'text-color'" title="details" @click="$emit('show')">
        <svg class="ml-auto h-4 text-secondary-color drag-task cursor-move absolute top-1 right-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
        </svg>

        <div>
            <p class="p-0 text-lg text-left">
                <svg class="h-5 -mt-1 hover:text-green-500 inline-block" :class="task.completed ? 'text-green-500' : ''" @click="toggleCompleted()" v-on:click.stop xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ task.name }}
            </p>
        </div>
        <div class="mt-8 flex items-center">
            <div class="flex items-center" v-if="due_date">
                <svg class="h-5 inline-block text-color" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="ml-1">{{ due_date }}</span>
            </div>
        </div>
    </button>
</template>

<script>

    import InputHidden from '@/Components/InputHidden'

    import moment from 'moment'

    export default {
        props: ['task'],

        components: {
            InputHidden,
        },

        data() {
            return {
                form: this.$inertia.form({
                    id: this.task.id,
                    name: this.task.name,
                    description: this.task.description,
                    due_date: this.task.due_date,
                    completed: this.task.completed,
                }),
            }
        },
        computed: {
            due_date() {
                return this.task.due_date ? moment(this.task.due_date).format('MMM Do') : null;
            },
        },
        watch: {
            task: function() {
                this.form = this.$inertia.form({
                    id: this.task.id,
                    name: this.task.name,
                    description: this.task.description,
                    due_date: this.task.due_date,
                    completed: this.task.completed,
                });
            }
        },
        methods: {
            focus() {
                this.$refs.inputHidden.focus()
            },
            updateTask() {
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
            }
        }
    }
</script>
