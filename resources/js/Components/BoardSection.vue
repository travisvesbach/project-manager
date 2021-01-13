<template>
    <div class="w-64 inline-block align-top">
        <div class="flex items-center">
            <svg class="ml-3 h-4 inline-block text-secondary-color drag-section cursor-move" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
            </svg>
            <input-hidden v-model="form.name" class="ml-1 text-lg heading-color" @blur.native="updateSection()" @keyup.enter.native="$event.target.blur()"/>
        </div>

        <div class="mx-2">
            <draggable :list="section.tasks" @change="updateTaskWeights" handle=".drag-task" group="tasks">
                <div v-for="(task, index) in section.tasks">
                    <task-card v-bind:task="task" @show="$emit('show', task)" @focusnew="focusNew()"/>
                </div>
            </draggable>

            <div>
                <task-row-new v-bind:section="section" ref="newTaskInput"/>
            </div>
        </div>
    </div>
</template>

<script>

    import TaskCard from '@/Components/TaskCard'
    import TaskRowNew from '@/Components/TaskRowNew'
    import InputHidden from '@/Components/InputHidden'

    import draggable  from 'vuedraggable'

    export default {
        props: ['section'],

        components: {
            TaskCard,
            TaskRowNew,
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
