<template>
    <div>
        <input-hidden v-model="form.name" class="ml-3 text-lg heading-color" @blur.native="updateSection()" @keyup.enter.native="$event.target.blur()"/>
        <div class="border-t-2 border-color">
            <div class="border-b-2 border-color" v-for="(task, index) in section.tasks">
                <task-row v-bind:task="task" @show="$emit('show', task)" @focusnew="focusNew()"/>
            </div>
            <div>
                <task-row-new v-bind:section="section" ref="newTaskInput"/>
            </div>
        </div>
    </div>
</template>

<script>

    import TaskRow from '@/Components/TaskRow'
    import TaskRowNew from '@/Components/TaskRowNew'
    import InputHidden from '@/Components/InputHidden'

    export default {
        props: ['section'],

        components: {
            TaskRow,
            TaskRowNew,
            InputHidden,
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
        }
    }
</script>
