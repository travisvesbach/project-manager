<template>
    <app-layout>
        <template #header>
            Projects
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <div class="card" v-for="project in projects">
                    <inertia-link :href="route('projects.show', {id: project.id})">
                        <h2>{{ project.name }}</h2>
                    </inertia-link>
                    <p>{{ project.description }}</p>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        props: ['projects'],

        components: {
            AppLayout,
        },
    }
</script>
