<template>
    <div class="ml-4 my-1 flex items-center h-7" :class="this.task.completed ? 'text-secondary-color' : 'text-color'">
        <svg class="ml-3 h-4 inline-block text-secondary-color drag-task cursor-move" :class="draggable && !task.completed ? '' : 'invisible'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
        </svg>
        <svg class="h-5 ml-3 inline-block hover:text-green-500" :class="task.completed ? 'text-green-500' : ''" @click="toggleCompleted()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="inline-block ml-1">
            <input-hidden id="name" class="" v-model="form.name" @blur.native="updateTask()" @keyup-enter="$emit('focusnew')" ref="inputHidden" v-if="!task.completed"/>
            <span v-if="task.completed">{{ form.name }}</span>
        </div>
        <div class="flex-1 inline-block h-full">
            <button class="h-full w-full focus:outline-none focus:border-0 text-right text-transparent hover-text-secondary-color" title="details" @click="$emit('show')">
                Details <svg class="h-4 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        <div class="inline-block border-l-2 border-color pl-2">

            <div class="flex items-center w-24" v-if="due_date">
                <svg class="h-3 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="ml-1 text-sm">{{ due_date }}</span>
            </div>
        </div>
    </div>
</template>

<script>

    import InputHidden from '@/Components/InputHidden'

    import moment from 'moment'

    export default {
        props: ['task', 'section', 'draggable'],

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
            focus() {
                this.$refs.inputHidden.focus()
            },
            updateTask() {
                if(this.form.name == '') {
                    this.form.name = this.task.name;
                } else if(this.form.name != this.task.name) {
                    this.form.patch(this.task.path);
                }
            },
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
