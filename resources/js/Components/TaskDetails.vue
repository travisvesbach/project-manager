<template>
    <transition name="slidein-left">
        <div class="absolute right-0 top-0 bottom-0 max-w-xl w-1/2 card-color text-color" v-if="task">
            <div class="flex p-2 space-between">
                <jet-button :class="form.completed ? 'bg-green-500 dark:bg-green-500' : ''" @click.native="toggleCompleted()">
                    <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg> {{ completedButtonText }}
                </jet-button>

                <button class="ml-auto link-color" @click="$emit('close')">
                    <svg class="h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <input class="form-input px-1 hidden-input-color" v-model="form.name" ref="name" @blur="updateTask()" @keyup.enter="$event.target.blur()">

        </div>
    </transition>
</template>

<script>

    import JetButton from '@/Jetstream/Button'
    import HiddenInput from '@/Components/HiddenInput'

    export default {
        props: ['task'],

        components: {
            JetButton,
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
        watch: {
            task: function() {
                this.form = this.$inertia.form({
                    id: this.task.id,
                    name: this.task.name,
                    description: this.task.description,
                    completed: this.task.completed,
                });
            }
        },
        computed: {
            completedButtonText() {
                return this.task.completed ? 'Completed' : 'Mark Complete';
            }
        },
        methods: {
            updateTask() {
                if(this.form.name == '') {
                    this.form.name = this.task.name;
                } else {
                    this.$inertia.patch(this.task.path, this.form);
                }
            },
            toggleCompleted() {
                this.task.completed = !this.task.completed ? true : false;
                this.form.completed = this.task.completed;
                this.$inertia.patch(this.task.path, this.form);
            },
            doThing(event) {
                console.log(event);
            }
        }
    }
</script>
