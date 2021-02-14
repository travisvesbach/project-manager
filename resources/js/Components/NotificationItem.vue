<template>
    <div @click="markAsRead" class="flex items-center">
        <div>
            <img class="h-6 w-6 rounded-full object-cover inline-block" :src="notification.data['user']['profile_photo_url']" :alt="notification.data['user']['name']" v-if="notification.data['user']['profile_photo_url']"/>
            <span v-html="description" />
            <span class="text-xs text-secondary-color" :title="created_at"><vue-moments-ago prefix="" suffix="ago" :date="created_at_standard" lang="en" /></span>
        </div>
        <span class="dot dot-color ml-auto" v-if="!read"></span>
    </div>
</template>

<script>

    import moment from 'moment'
    import VueMomentsAgo from 'vue-moments-ago'

    export default {
        props: ['notification'],


        components: {
            VueMomentsAgo
        },

        data() {
            return {
                read: this.notification.read_at,
            }
        },

        computed: {
            created_at() {
                return this.notification.created_at ? moment(this.notification.created_at).format('MMMM Do YYYY, h:mm:ss a') : null;
            },
            created_at_standard() {
                return this.notification.created_at ? moment(this.notification.created_at).format() : null;
            },
            description() {
                let description = this.notification.data['user']['name'] + ' ';

                switch(this.notification.type) {
                    case 'App\\Notifications\\ProjectGeneralNotification':
                        switch(this.notification.data['description']) {
                            case 'invited to project':
                                description += 'invited you to ' + this.notification.data['project_name'];
                                break;
                            case 'promoted to project owner':
                                description += 'made you ' + this.notification.data['project_name'] + '\'s owner';
                                break;
                        }
                        break;
                    case 'App\\Notifications\\TaskGeneralNotification':
                        switch(this.notification.data['description']) {
                            case 'complete':
                                description+= 'marked ' + this.notification.data['task_name'] + ' as complete';
                                break;
                            case 'incomplete':
                                description+= 'marked ' + this.notification.data['task_name'] + ' as incomplete'
                                break;
                        }
                        break;

                    default:
                }
                return description;
            },
        },
        methods: {
            markAsRead() {
                if(this.notification.read_at == null) {
                    this.read = true;
                    this.notification.read_at = true;

                    axios.patch('/notifications/' + this.notification.id, {
                        'read': true,
                    }).then(response => {
                        // this.notification = response.data;
                    });
                }
            }
        }
    }
</script>
