<template>
    <div class="ProseMirror"
         ref="contentRef"
         v-html="content"></div>
</template>

<script setup>
import {toHtml} from 'hast-util-to-html'
import {lowlight} from 'lowlight'
import {onMounted, onUpdated, ref} from "vue";

const props = defineProps({
    content: {
        type: String,
        default: '',
    },
})

const contentRef = ref(null);

const formatSyntaxHighlighting = () => {
    const code = contentRef.value.querySelectorAll('.ProseMirror-content code');

    for (let i = 0; i < code.length; i++) {
        const highlightedCode = lowlight.highlightAuto(code[i].textContent);
        code[i].innerHTML = toHtml(highlightedCode);
    }
}

onUpdated(formatSyntaxHighlighting);
onMounted(formatSyntaxHighlighting);
</script>
