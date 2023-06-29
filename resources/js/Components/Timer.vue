<script setup>

import {VideoPause, VideoPlay} from "@element-plus/icons-vue";
import {Link, usePage} from '@inertiajs/vue3'
import {computed} from "vue";

const props = defineProps({
    task: {
        type: Object,
    }
})

const page = usePage()

const auth = computed(() => page.props.auth)

const isRun = computed(() => {
    if (!props.task) {
        return auth.value.time?.task_id;
    }

    return auth.value.user.active_time?.task_id == props.task?.id;
});

const visible = computed(() => auth.value.user.active_time?.task_id || props.task?.id);

const url = computed(() => route(isRun.value ? 'timer.stop' : 'timer.start'))
</script>

<template>
    <Link v-if="visible"
          :href="url"
          method="post"
          as="span"
          style="display: inline-flex;"
          :data="{ task: task?.id }"
          :only="['auth']"
          preserve-scroll>
        <template v-if="isRun">
            <slot name="stop">
                <el-icon style="font-size: 32px; cursor: pointer;" color="#cc2222">
                    <VideoPause/>
                </el-icon>
            </slot>
        </template>
        <template v-else>
            <slot name="play">
                <el-icon style="font-size: 32px; cursor: pointer;" color="#22cc22">
                    <VideoPlay/>
                </el-icon>
            </slot>
        </template>
    </Link>
</template>

<style lang="scss" scoped>
.el-icon {
    opacity: 0.8;

    &:hover {
        //opacity: 1;
        color: var(--el-text-color-primary)
    }
}
</style>
