<script setup>
import EditorContent from "@/Components/Forms/EditorContent.vue";

defineProps({
    comment: {
        type: Object,
        default: {content: ''},
    },
})
</script>

<template>
    <el-card mt-3
             max-w-screen-lg
             shadow="never">
        <EditorContent v-if="comment.content" :content="comment.content"/>
        <slot name="after"/>
    </el-card>
</template>
