<template>
    <div class="w-64 inline-block align-top">
        <input-hidden class="text-lg" id="name" v-model="name" @blur.native="createSection()" placeholder="+ New Section" @keyup-enter="createSection()" ref="inputHidden"/>
    </div>
</template>

<script>

    import InputHidden from '@/Components/InputHidden'

    export default {
        props: ['project'],

        components: {
            InputHidden,
        },

        data() {
            return {
                name: null,
                form: this.$inertia.form({
                    name: null,
                }),
            }
        },
        methods: {
            focus() {
                this.$refs.inputHidden.focus();
            },
            createSection() {
                if(this.name != null && this.name.length > 0) {
                    this.form.name = this.name;
                    this.form.post(this.project.path + '/sections');
                    this.name = null;
                }
            }
        }
    }
</script>
