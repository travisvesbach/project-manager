<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Project
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <form @submit.prevent="submit">
                    <label for="name">Name:</label>
                    <input id="name" v-model="form.name" />
                    <label for="description">Description:</label>
                    <textarea id="description" v-model="form.description" />
                    <button type="submit">Submit</button>
                </form>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        components: {
            AppLayout,
        },
        data() {
            return {
                form: {
                    name: null,
                    description: null,
                },
            }
        },
        methods: {
            submit() {
                this.$inertia.post('/projects', this.form)
            },
        },
    }
</script>
