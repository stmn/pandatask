<script setup>
const props = defineProps({
    message: {
        type: String,
        required: false
    },
})
</script>

<template>
    <div class="error" v-show="message">
        {{ message }}
    </div>
</template>

<style lang="scss" scoped>
.error {
    color: var(--el-color-danger);
    text-align: left;
    line-height: var(--el-font-line-height-primary);
}
</style>
