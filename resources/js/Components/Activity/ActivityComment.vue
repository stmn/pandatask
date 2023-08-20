<script setup>
import EditorContent from "@/Components/Forms/EditorContent.vue";

const props = defineProps({
    comment: {
        type: String,
        required: false
    },
})
</script>

<template>
    <el-card v-if="comment"
             mt-3
             max-w-screen-lg
             shadow="never">
        <EditorContent :content="comment.content"/>
        <slot name="after"/>
    </el-card>
</template>
