<template>
    <app-layout>
        <template #header>
            Projects
        </template>

        <div>
            <div class="lg:flex lg:flex-wrap -mx-3">
                <div class="lg:w-1/3 px-3 pb-6" v-for="project in projects">
                    <card>
                        <template #title>
                            <inertia-link :href="route('projects.show', {id: project.id})">
                                {{ project.name }}
                            </inertia-link>
                        </template>
                        <p>{{ project.description }}</p>
                    </card>
                </div>
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
