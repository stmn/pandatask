<template>
    <el-button :type="type">
        <i v-if="icon" :class="`${icon} mr-2`"></i>{{ text }}
    </el-button>
</template>

<script setup>

const props = defineProps({
    type: String,
    icon: String,
    text: String,
})
</script>
