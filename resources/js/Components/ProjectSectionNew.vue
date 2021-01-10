<template>
    <div class="ml-4 my-1 flex items-center text-secondary-color">
        <div class="inline-block ml-2">
            <input-hidden class="text-lg" id="name" v-model="name" @blur.native="createSection()" placeholder="+ New Section" @keyup-enter="createSection()" ref="inputHidden"/>
        </div>
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
