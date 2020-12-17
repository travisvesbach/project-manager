<template>
    <div class="ml-4 my-1 flex items-center text-secondary-color">
        <svg class="h-5 inline-block invisible" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="inline-block ml-2">
            <input-hidden id="name" v-model="name" @blur.native="createTask()" placeholder="New Task" @keyup-enter="createTask()" ref="inputHidden"/>
        </div>
    </div>
</template>

<script>

    import InputHidden from '@/Components/InputHidden'

    export default {
        props: ['project'],

        components: {
            InputHidden,
        },

        data() {
            return {
                name: null,
                form: this.$inertia.form({
                    name: null,
                    completed: false,
                }),
            }
        },
        methods: {
            focus() {
                this.$refs.inputHidden.focus();
            },
            createTask() {
                if(this.name != null && this.name.length > 0) {
                    this.form.name = this.name;
                    this.$inertia.post(this.project.path + '/tasks', this.form);
                    this.name = null;
                }
            }
        }
    }
</script>
