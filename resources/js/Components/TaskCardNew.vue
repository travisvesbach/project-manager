<template>
    <textarea-input
        id="name"
        v-model="form.name"
        v-bind:hidden="true"
        placeholder="Add Task"
        ref="textareaInput"
        @blur.native="createTask()"
        @keyup.enter.native="createTask()"
        @keydown.enter.prevent.native="createTask()"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
    />

</template>

<script>

    import TextareaInput from '@/Components/TextareaInput'

    export default {
        props: ['section'],

        components: {
            TextareaInput,
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: null,
                    section_id: this.section.id
                }),
            }
        },
        methods: {
            focus() {
                this.$refs.textareaInput.focus();
            },
            createTask() {
                if(this.form.name != null && this.form.name.length > 0) {
                    this.form.post(this.section.project.path + '/tasks').then(() => {
                        this.$refs.textareaInput.focus();
                    });
                }
            }
        }
    }
</script>
