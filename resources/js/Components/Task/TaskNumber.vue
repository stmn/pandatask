<script setup>
defineProps({
    number: {
        type: Number,
        required: true
    }
})
</script>

<template>
    <span class="task-number">#{{ number }}</span>
</template>

<style scoped>
.task-number {
    background: var(--el-bg-color);
    padding: 0 3px;
    //margin-left: 5px;
    //margin-right: 5px;
    word-break: keep-all;
    font-size: 13px;
    line-height: 22px;
}
</style>
