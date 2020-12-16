<template>
    <transition name="slidein-left">
        <div class="absolute right-0 top-0 bottom-0 max-w-xl p-2 w-1/2 card-color text-color" v-if="task">
            <div class="flex space-between mb-t">
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

            <div class="">

                <input class="form-input rounded-lg px-1 w-full text-3xl hidden-input-color heading-color" v-model="form.name" ref="name" @blur="updateTask()" @keyup.enter="$event.target.blur()">


            </div>



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
            },
            descriptionRendered() {
                if(this.form.description) {
                    return marked(this.form.description);
                } else {
                    return '';
                }
            }
        },
        methods: {
            updateTask(description = false) {
                if(description != false) {
                    this.form.description = description;
                }
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
            descriptionChanged(event) {
                this.form.description = event.target.innerText;
            }
        }
    }
</script>
