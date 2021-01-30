<template>
    <app-layout>
        <template #header>
            Projects
        </template>

        <div class="lg:flex lg:flex-wrap">
            <div v-if="projects.length == 0" class="w-full text-center text-color">
                You aren't a part of any projects.
            </div>
            <div class="lg:w-1/3 px-3 pb-6 flex flex-col" v-for="project in projects">
                <inertia-link :href="route('projects.show', {id: project.id})" class="flex flex-col flex-1">
                    <card class="flex-1">
                        <template #title>
                            {{ project.name }}
                        </template>
                        <p>{{ project.description }}</p>
                        <!-- <hr> -->
                        <div class="mt-4 flex items-center text-secondary-color" v-if="project.tasks.length > 0" :title="project.tasks.length + (project.tasks.length == 1 ? ' task' : ' tasks' )">
                            <svg class="h-4 inline-block mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                            {{ project.tasks.length }}
                        </div>
                    </card>
                </inertia-link>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Card from '@/Components/Card'

    export default {
        props: ['projects'],

        components: {
            AppLayout,
            Card,
        },
    }
</script>
