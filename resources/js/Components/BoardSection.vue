<template>
    <div class="w-64 inline-block align-top">
        <div class="flex items-center">
            <svg class="ml-3 h-4 inline-block text-secondary-color drag-section cursor-move" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>
            <input-hidden v-model="form.name" class="ml-1 text-lg heading-color" @blur.native="updateSection()" @keyup.enter.native="$event.target.blur()"/>
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
    </div>
</template>

<script>

    import TaskCard from '@/Components/TaskCard'
    import TaskCardNew from '@/Components/TaskCardNew'
    import InputHidden from '@/Components/InputHidden'

    import draggable  from 'vuedraggable'

    export default {
        props: ['section', 'sort', 'completedFilter'],

        components: {
            TaskCard,
            TaskCardNew,
            InputHidden,
            draggable,
        },

        data() {
            return {
                form: this.$inertia.form({
                    id: this.section.id,
                    name: this.section.name,
                }),
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
            }
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
            }
        }
    }
</script>
