<template>
    <button class="h-24 w-full flex flex-col align-center focus:outline-none focus:border-0 my-2 p-2 rounded-lg card-color" :class="this.task.completed ? 'text-secondary-color' : 'text-color'" title="details" @click="$emit('show')">
        <div class="flex items-center">
            <svg class="h-5 inline-block hover:text-green-500" :class="task.completed ? 'text-green-500' : ''" @click="toggleCompleted()" v-on:click.stop xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="ml-1 text-lg">{{ task.name }}</span>
            <svg class="ml-auto h-4 inline-block text-secondary-color drag-task cursor-move" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>
        </div>
        <div>
            <date-picker v-model="form.due_date" v-bind:id="form.id" v-bind:hidden="true" @input="updateTask()" v-on:click.stop />
        </div>
    </button>
</template>

<script>

    import InputHidden from '@/Components/InputHidden'
    import DatePicker from '@/Components/DatePicker'

    export default {
        props: ['task'],

        components: {
            InputHidden,
            DatePicker,
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
