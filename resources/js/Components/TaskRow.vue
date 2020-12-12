<template>
    <div class="ml-4 my-1 flex items-center" :class="this.task.completed ? 'text-secondary-color' : 'text-color'">
        <svg class="h-5 inline-block hover:text-green-500 transition duration-300" :class="task.completed ? 'text-green-500' : ''" @click="toggleCompleted()" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <div class="inline-block ml-2">
            <!-- <form> -->
                <hidden-input id="name" class="" v-model="form.name" @blur.native="updateTask()"/>
            <!-- </form> -->
        </div>
    </div>
</template>

<script>

    import HiddenInput from '@/Components/HiddenInput'

    export default {
        props: ['task'],

        components: {
            HiddenInput,
        },

        data() {
            return {
                form: this.$inertia.form({
                    id: this.task.id,
                    name: this.task.name,
                    description: this.task.description,
                    completed: this.task.completed,
                }),
            }
        },
        methods: {
            updateTask() {
                this.$inertia.patch(this.task.path, this.form);
            },
            toggleCompleted() {
                this.task.completed = !this.task.completed ? true : false;
                this.form.completed = this.task.completed;
                this.$inertia.patch(this.task.path, this.form);
            }
        }
    }
</script>
