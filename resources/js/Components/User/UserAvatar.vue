<script setup>
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";
import {computed} from "vue";

const props = defineProps({
    user: {
        type: Object,
    },
    size: {
        type: Number,
        default: undefined
    },
    tooltip: {
        type: Boolean,
        default: false
    },
})

const fullName = computed(() => {
    return props.user?.first_name + ' ' + props.user?.last_name
})
</script>

<template>
    <el-tooltip :content="fullName" placement="top" :disabled="!tooltip">
        <BaseAvatar :avatar="user.avatar"
                    :name="user.full_name"
                    :size="props.size"
                    style="vertical-align: middle;"/>
    </el-tooltip>
</template>
