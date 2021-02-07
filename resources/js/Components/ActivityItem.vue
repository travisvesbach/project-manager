<template>
    <div>
        <img class="h-6 w-6 rounded-full object-cover inline-block" :src="activity.user.profile_photo_url" :alt="activity.user.name" v-if="activity.user.profile_photo_url"/>
        <span v-html="description" />
        <span class="text-xs text-secondary-color" :title="created_at"><vue-moments-ago prefix="" suffix="ago" :date="created_at_standard" lang="en" /></span>
    </div>
</template>

<script>

    import moment from 'moment'
    import VueMomentsAgo from 'vue-moments-ago'

    export default {
        props: ['activity', 'for'],

        components: {
            VueMomentsAgo
        },

        computed: {
            created_at() {
                return this.activity.created_at ? moment(this.activity.created_at).format('MMMM Do YYYY, h:mm:ss a') : null;
            },
            created_at_standard() {
                return this.activity.created_at ? moment(this.activity.created_at).format() : null;
            },
            description() {
                let description = this.activity.user.name + ' ';

                switch(this.activity.description) {
                    case 'created_project':
                        description += 'created the project';
                        break;

                    case 'updated_project':
                        if(Object.keys(this.activity.changes.after).length == 1 && Object.keys(this.activity.changes.after)[0] == 'owner_id') {
                            let user = this.$page.users.find(x => x.id === this.activity.changes.after.owner_id);
                            description += 'made ' + user.name + ' the project owner';
                        } else if(Object.keys(this.activity.changes.after).length == 1) {
                            description += 'updated the project\'s ' + Object.keys(this.activity.changes.after)[0];
                        } else {
                            description += 'updatd the project';
                        }
                        break;

                    case 'created_task':
                        description += 'created ' + (this.for == 'task' ? 'this task' : '<strong>' + this.activity.subject.name + '</strong>');
                        break;

                    case 'updated_task':
                        let identifier = (this.for == 'task' ? 'this task' : '<strong>' + this.activity.subject.name + '</strong>');
                        if(Object.keys(this.activity.changes.after).length == 1) {
                            if(Object.keys(this.activity.changes.after)[0] == 'completed') {
                                description += 'marked ' + identifier + (this.activity.changes.after.completed_at ? ' complete' : ' incomplete');
                            } else {
                                description += 'updated ' + identifier + '\'s ' + Object.keys(this.activity.changes.after)[0];
                            }
                        } else {
                            description += 'updated ' + identifier;
                        }
                        break;

                    case 'created_section':
                        description += 'created <strong>' + this.activity.subject.name + '</strong>';
                        break;

                    case 'updated_section':
                        if(Object.keys(this.activity.changes.after).length == 1) {
                            description += 'updated <strong>' + this.activity.subject.name + '</strong>\'s ' + Object.keys(this.activity.changes.after)[0];
                        } else {
                            description += 'updated <strong>' + this.activity.subject.name + '</strong>';
                        }
                        break;

                    case 'joined_project':
                        description += 'joined the project';
                        break;

                    case 'left_project':
                        description += 'left the project';
                        break;

                    default:
                }
                return description;
            },
        },
    }
</script>
