<template>
    <el-avatar
        v-if="avatar"
        :size="size"
        style="vertical-align: sub;"
        :src="avatar"
    />
    <DefaultAvatar v-else
                   :name="name"
                   :style="`width: ${size}px; height: ${size}px;`" />
</template>

<script setup>
import DefaultAvatar from "@/Components/Common/DefaultAvatar.vue";

const props = defineProps({
    avatar: {
        type: String,
    },
    size: {
        type: Number,
        default: 24
    },
    name: {
        type: String,
        default: '',
    },
});

</script>

<style lang="scss" scoped>

</style>
