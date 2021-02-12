<template>
    <div id="alert" class="rounded py-3 px-5 alert-flash" v-bind:class="statusClasses" role="alert" v-show="show">
        <strong v-if="status == 'success'">Success!</strong> {{ message }}
    </div>
</template>

<script>
    export default {
        props: ['message', 'status'],
        data() {
            return {
                show: false
            }
        },
        created() {
            if (this.message) {
                this.flash(this.message);
            }
        },
        watch: {
            message: function() {
                this.flash();
            }
        },
        computed: {
            statusClasses: function() {
                return {
                    'bg-green-100 text-green-800': this.status == 'success',
                    'bg-red-200 text-red-900': this.status == 'danger',
                    'bg-blue-200 text-blue-800': this.status == 'info',
                    'bg-indigo-200 text-indigo-800': this.status == 'primary'
                }
            }
        },
        methods: {
            flash() {
                this.show = true;

                this.hide();
            },
            hide() {
                setTimeout(() => {
                    this.show = false;
                }, 3000);
            }
        }
    };
</script>

<style>
    .alert-flash {
        position: fixed;
        bottom: 25px;
        right: 25px;
        z-index: 9999;
    }
</style>
