<template>
    <div class="el-textarea">
        <div class="el-textarea__inner">
            <el-scrollbar :min-size="minHeight" :height="height" :max-height="maxHeight" always>
                <editor-content
                    :style="`min-height:${minHeight}px;`"
                    :editor="editor">
                </editor-content>
            </el-scrollbar>
        </div>
    </div>
</template>

<script>
import Placeholder from '@tiptap/extension-placeholder'
import StarterKit from '@tiptap/starter-kit'
// import {HardBreak} from "@tiptap/extension-hard-break";
import {Editor, EditorContent, VueNodeViewRenderer} from '@tiptap/vue-3'


import {lowlight} from 'lowlight'
import CodeBlockLowlight from '@tiptap/extension-code-block-lowlight'
import CodeBlockComponent from './CodeBlockComponent.vue'
// import css from 'highlight.js/lib/languages/css'
// import js from 'highlight.js/lib/languages/javascript'
// import ts from 'highlight.js/lib/languages/typescript'
import html from 'highlight.js/lib/languages/xml'
// import php from 'highlight.js/lib/languages/php'
lowlight.registerLanguage('html', html)
// lowlight.registerLanguage('css', css)
// lowlight.registerLanguage('js', js)
// lowlight.registerLanguage('ts', ts)
// lowlight.registerLanguage('php', php)

export default {
    components: {
        EditorContent,
    },

    props: {
        modelValue: {
            type: String,
            default: '',
        },
        height: {
            type: Number,
            default: null,
        },
        minHeight: {
            type: Number,
            default: 0,
        },
        maxHeight: {
            type: Number,
            default: 300,
        },
        proseStyle: {
            type: String,
            default: '',
        },
        placeholder: {
            type: String,
            default: '',
        },
    },

    emits: ['update:modelValue'],

    data() {
        return {
            editor: null,
            minHeightPx: this.minHeight+'px',
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
                    placeholder: this.placeholder,
                }),
                CodeBlockLowlight
                    .extend({
                        addNodeView() {
                            return VueNodeViewRenderer(CodeBlockComponent)
                        },
                        // addKeyboardShortcuts() {
                        //     return {
                        //         'Tab': (data) => {
                        //             // add 2 spaces on tab
                        //             console.log(this.editor.chain(), 'kurwa')
                        //             this.editor.chain().focus().insertContent('  ').run()
                        //
                        //
                        //             // prevent default tab behaviour
                        //             // event.preventDefault();
                        //             // return false
                        //         }
                        //     }
                        // },
                    })
                    .configure({lowlight}),
                // HardBreak,
            ],
            content: this.modelValue,

            emptyEditorClass: 'is-editor-empty',
            emptyNodeClass: 'my-custom-is-empty-class',
            placeholder: 'My Custom Placeholder',
            // autofocus: true,

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

<style lang="scss" scoped>
::v-deep(.el-scrollbar__bar.is-horizontal) {
    display: none !important;
}
::v-deep(.ProseMirror) {
    min-height: v-bind(minHeightPx) !important;
}
</style>
