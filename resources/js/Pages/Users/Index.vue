<template>
    <app-layout>
        <template #header>
            Users
        </template>

        <div class="w-full md:w-1/2 mx-auto py-10 sm:px-6 lg:px-8 m-2">
            <div class="border-b-2 border-color w-full"></div>
            <div class="border-b-2 border-color w-full" v-for="user in users">
                <div class="pl-1 py-1 flex items-center text-color flex-1 hover-trigger">

                    <img class="h-8 w-8 rounded-full object-cover" :src="user.profile_photo_url" :alt="user.name" />

                    <div class="inline-block ml-1 flex-1">
                        <div>{{ user.name }}</div>
                        <div class="flex items-center">
                            <badge class="ml-0" :value="user.role"/>

                            <a :href="'mailto:' + user.email" class="text-sm link-color">{{ user.email }}</a>
                        </div>
                    </div>
                    <div class="inline-block">
                        <!-- dropdown -->
                        <jet-dropdown align="right" width="48" class="hover-target" v-if="user.id != $page.user.id">
                            <template #trigger>
                                <button class="flex link link-color">
                                    <svg class="fill-current h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <jet-dropdown-link :href="route('users.edit', {id: user.id})">
                                    Edit User
                                </jet-dropdown-link>
                                <jet-dropdown-link @click.native="confirmingDeleteUser = user" as="button">
                                    Delete User
                                </jet-dropdown-link>
                            </template>
                        </jet-dropdown>
                    </div>
                </div>
            </div>
        </div>

        <!-- delete confirmation -->
        <jet-confirmation-modal :show="confirmingDeleteUser" @close="confirmingDeleteUser = false">
            <template #title>
                Delete User
            </template>

            <template #content>
                Are you sure you want to delete this user? All of this user's projects will be deleted as well.
            </template>

            <template #footer>
                <jet-secondary-button @click.native="confirmingDeleteUser = false">
                    Cancel
                </jet-secondary-button>
                <jet-danger-button class="ml-2" @click.native="deleteUser" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Delete User
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>

    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import Badge from '@/Components/Badge'
    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetButton from '@/Jetstream/Button'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'

    export default {
        props: ['users'],

        components: {
            AppLayout,
            Badge,
            JetDropdown,
            JetDropdownLink,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
            JetConfirmationModal,
        },

        data() {
            return {
                confirmingDeleteUser: false,
                form: this.$inertia.form({
                    id: null,
                }),
            }
        },

        methods: {
            deleteUser() {
                // this.form.id = this.confirmingDeleteUser.id;
                // this.$inertia.delete('/users/' + this.form.id);
            }
        }
    }
</script>
