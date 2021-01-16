<template>
    <button class="w-full relative flex flex-col focus:outline-none focus:border-0 my-2 p-2 rounded-lg" :class="this.task.completed ? 'text-secondary-color card-secondary-color' : 'text-color card-color drag-task'" title="details" @click="$emit('show')">
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
                <svg class="h-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
        props: ['task', 'section'],

        components: {
            InputHidden,
        },

        data() {
            return {
                form: this.$inertia.form({
                    id: this.task.id,
                    name: this.task.name,
                    completed: this.task.completed,
                    weight: this.task.weight,
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
                    completed: this.task.completed,
                    weight: this.task.weight,
                });
            }
        },
        methods: {
            toggleCompleted() {
                this.task.completed = !this.task.completed ? true : false;
                this.task.weight = this.task.completed ? null : Math.max.apply(Math, this.section.tasks.map(function(x) { return x.weight; })) + 1;
                this.form.completed = this.task.completed;
                this.form.weight = this.task.weight;
                this.form.patch(this.task.path);
            }
        }
    }
</script>
