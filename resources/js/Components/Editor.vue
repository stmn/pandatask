<template>
    <editor-content
        class="el-textarea__inner"
        :editor="editor" />
</template>

<script>
import Placeholder from '@tiptap/extension-placeholder'
import StarterKit from '@tiptap/starter-kit'
import {Editor, EditorContent} from '@tiptap/vue-3'

// import Document from '@tiptap/extension-document'
// import Paragraph from '@tiptap/extension-paragraph'
// import Text from '@tiptap/extension-text'

export default {
    components: {
        EditorContent,
    },

    props: {
        modelValue: {
            type: String,
            default: '',
        },
    },

    emits: ['update:modelValue'],

    data() {
        return {
            editor: null,
        }
    },

    watch: {
        modelValue(value) {
            // HTML
            const isSame = this.editor.getHTML() === value

            // JSON
            // const isSame = JSON.stringify(this.editor.getJSON()) === JSON.stringify(value)

            if (isSame) {
                return
            }

            this.editor.commands.setContent(value, false)
        },
    },

    mounted() {
        this.editor = new Editor({
            extensions: [
                StarterKit,
                Placeholder.configure({
                    placeholder: 'Add comment...',
                })
            ],
            content: this.modelValue,

            emptyEditorClass: 'is-editor-empty',
            emptyNodeClass: 'my-custom-is-empty-class',
            placeholder: 'My Custom Placeholder',

            onUpdate: () => {
                // HTML
                this.$emit('update:modelValue', this.editor.getHTML())

                // JSON
                // this.$emit('update:modelValue', this.editor.getJSON())
            },
        })
    },

    beforeUnmount() {
        this.editor.destroy()
    },
}
</script>

<style lang="scss">
/* Basic editor styles */
.ProseMirror p.is-editor-empty:first-child::before {
    color: #adb5bd;
    content: attr(data-placeholder);
    float: left;
    height: 0;
    pointer-events: none;
}

.ProseMirror {
    font-size: 14px;
    min-height: 60px;
    &-focused {
        outline: none;
        //border: 1px solid var(--el-border-color);
    }
    p {
        margin: 0;
    }
    //> * + * {
    //    margin-top: 0.75em;
    //}
    //
    //code {
    //    background-color: rgba(#616161, 0.1);
    //    color: #616161;
    //}
}

//.content {
//    padding: 1rem 0 0;
//
//    h3 {
//        margin: 1rem 0 0.5rem;
//    }
//
//    pre {
//        border-radius: 5px;
//        color: #333;
//    }
//
//    code {
//        display: block;
//        white-space: pre-wrap;
//        font-size: 0.8rem;
//        padding: 0.75rem 1rem;
//        background-color:#e9ecef;
//        color: #495057;
//    }
//}
</style>
