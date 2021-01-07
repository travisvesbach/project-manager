<template>
    <div>
        <img class="h-6 w-6 rounded-full object-cover inline-block" :src="activity.user.profile_photo_url" :alt="activity.user.name" v-if="activity.user.profile_photo_url"/>
        <span v-html="description" />
        <span class="text-xs text-secondary-color" :title="activity.createdAtFormatted">{{ activity.timeSince }}</span>
    </div>
</template>

<script>

    export default {
        props: ['activity', 'for'],

        computed: {
            description() {
                let description = this.activity.user.name + ' ';

                switch(this.activity.description) {
                    case 'created_project':
                        description += 'created the project';
                        break;

                    case 'updated_project':
                        if(Object.keys(this.activity.changes.after).length == 1) {
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
                                description += 'marked ' + identifier + (this.activity.changes.after.completed ? ' complete' : ' incomplete');
                            } else {
                                description += 'updated ' + identifier + '\'s ' + Object.keys(this.activity.changes.after)[0];
                            }
                        } else {
                            description += 'updatd ' + identifier;
                        }
                        break;

                    case 'deleted_task':
                        description += 'deleted <strong>' + this.activity.subject.name + '</strong>';
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
