<template>
    <div class="rounded-md" :class="classes">
        <editor-content :editor="editor" />

        <editor-menu-bar :editor="editor" v-slot="{ commands, isActive, focused }" :keep-in-bounds="keepInBounds" >
            <div class="menubar" :class="{ 'block': focused, 'hidden' : !focused }" >
                <button type="button" class="px-2" :class="{ 'is-active': isActive.bold() }" @click="commands.bold" title="bold">
                    <strong>B</strong>
                </button>
                <button type="button" class="px-2" :class="{ 'is-active': isActive.italic() }" @click="commands.italic" title="italic">
                    <em>I</em>
                </button>
                <button type="button" class="px-2" :class="{ 'is-active': isActive.code() }" @click="commands.code" title="code">
                    &lt; &gt;
                </button>
            </div>
        </editor-menu-bar>
    </div>
</template>

<script>
    import { Editor, EditorContent, EditorMenuBar } from 'tiptap';
    import { Bold, Italic, Code } from 'tiptap-extensions';

    export default {
        props: ['value', 'hidden', 'num'],

        components: {
            EditorContent,
            EditorMenuBar,
        },
        data() {
            return {
                editor: null,
            }
        },
        computed: {
            classes() {
                if(this.hidden) {
                    return 'hidden-input-color';
                }
                return 'form-input-color';
            },
        },
        watch: {
            num: function() {
                this.editor.setContent(this.value);
            }
        },
        mounted() {
            this.editor = new Editor({
                content: this.value,
                extensions: [
                    new Bold(),
                    new Italic(),
                    new Code(),
                ],
                onUpdate: ({getJSON}) => {
                  this.$emit('input', getJSON());
                },
                onBlur: ({event}) => {
                    this.$emit('blurred');
                },
            })
        },
        beforeDestroy() {
            this.editor.destroy()
        },
    }
</script>
