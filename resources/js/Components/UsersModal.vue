<template>
    <jet-dialog-modal :show="show" @close="close">
        <template #title>
            {{ type == 'task' ? 'Task Collaborators' : 'Project Users' }}
        </template>

        <template #content>
            <div class="flex mb-1 mx-2 text-color items-end">
                <div class="flex items-center">
                    <svg class="h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <span class="ml-1">{{ usersCurrent.length }} {{ usersCurrent.length == 1 ? 'user' : 'users' }}</span>
                </div>

                <div class="ml-auto" :class="{ 'opacity-25': addDisabled }">
                    <select-input id="user" class="mr-4" v-model="form.id" v-bind:options="filteredUsers" v-bind:placeholder="'-- select user --'" required :disabled="addDisabled"/>
                    <jet-button type="submit" size="small" :disabled="addDisabled" @click.native="addUser">
                        {{ addText }}
                    </jet-button>
                </div>
            </div>

            <div class="border-b-2 border-secondary-color w-full"></div>

            <div class="border-b-2 border-secondary-color w-full" :class="{ 'opacity-25': removeForm.processing && removeForm.id == user.id }" v-for="user in usersCurrent" >
                <div class="pl-1 py-1 flex items-center text-color flex-1 hover-trigger">

                    <img class="h-8 w-8 rounded-full object-cover" :src="user.profile_photo_url" :alt="user.name" />

                    <div class="inline-block ml-2 flex-1">
                        <div class="text-lg mb-1">{{ user.name }}</div>
                        <div class="flex items-center">
                            <badge class="ml-0" :color="'light-gray'" :value="'Project Owner'" v-if="owner.id == user.id"/>
                            <badge class="ml-0" :color="'light-gray'" :value="user.role"/>
                            <a :href="'mailto:' + user.email" class="text-sm link-color">{{ user.email }}</a>
                        </div>
                    </div>
                    <div class="inline-block">
                        <!-- dropdown -->
                        <jet-dropdown align="right" width="48" position="fixed" class="hover-target">
                            <template #trigger>
                                <button class="flex link link-color">
                                    <svg class="fill-current h-8 w-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content >
                                <jet-dropdown-link :disabled="removeForm.processing" @click.native="removeUser(user)" as="button">
                                    {{ type == 'task' ? 'Remove from task' : 'Remove from project' }}
                                </jet-dropdown-link>
                            </template>
                        </jet-dropdown>
                    </div>
                </div>
            </div>
        </template>

        <template #footer>
            <jet-secondary-button @click.native="close">
                Close
            </jet-secondary-button>
        </template>
    </jet-dialog-modal>
</template>

<script>

    import JetDropdown from '@/Jetstream/Dropdown'
    import JetDropdownLink from '@/Jetstream/DropdownLink'
    import JetButton from '@/Jetstream/Button'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import SelectInput from '@/Components/SelectInput'
    import Badge from '@/Components/Badge'

    export default {
        props: ['owner', 'usersCurrent', 'usersFrom', 'type', 'path', 'show'],

        components: {
            JetDropdown,
            JetDropdownLink,
            JetButton,
            JetSecondaryButton,
            JetDangerButton,
            JetDialogModal,
            SelectInput,
            Badge,
        },

        data() {
            return {
                form: this.$inertia.form({
                    id: null,
                }),
                removeForm: this.$inertia.form({
                    id: null,
                }),
            }
        },
        computed: {
            filteredUsers() {
                if(!this.usersFrom) {
                    return false;
                } else if(this.usersFrom.length == this.usersCurrent.length ) {
                    return false;
                } else if(this.usersFrom.length > 0 && this.usersCurrent.length > 0) {
                    return this.usersFrom.filter(x => !this.usersCurrent.some(y => x.id === y.id));
                } else {
                    return this.usersFrom;
                }
            },
            addDisabled() {
                if(!this.filteredUsers) {
                    return true;
                } else if(this.form.processing) {
                    return true;
                } else {
                    return false;
                }
            },
            addText() {
                if(this.form.processing) {
                    return 'processing';
                } else if(!this.filteredUsers) {
                    return 'no users available';
                } else {
                    return 'Add User';
                }
            },
        },
        methods: {
            addUser() {
                this.form.post(this.path);
            },
            removeUser(user) {
                if(this.usersCurrent.includes(user)) {
                    this.removeForm.id = user.id;
                    this.removeForm.delete(this.path + '/' + user.id, { preserveState: true });
                }
            },
            close() {
                this.form.id = null;
                this.removeForm.id = null;
                this.$emit('close');
            }
        }
    }
</script>
