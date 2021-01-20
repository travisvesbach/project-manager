<template>
    <div>
        <img class="h-6 w-6 rounded-full object-cover inline-block" :src="notification.data['user']['profile_photo_url']" :alt="notification.data['user']['name']" v-if="notification.data['user']['profile_photo_url']"/>
        <span v-html="description" />
        <span class="text-xs text-secondary-color" :title="created_at"><vue-moments-ago prefix="" suffix="ago" :date="created_at_standard" lang="en" /></span>
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
                    case 'App\\Notifications\\InvitedToProject':
                        description += 'invited you to ' + this.notification.data['project_name'];
                        break;

                    default:
                }
                return description;
            },
        },
    }
</script>
