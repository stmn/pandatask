<script setup>
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
          :only="['auth', 'flash', 'times']"
          class="timer"
          preserve-scroll>
        <template v-if="isRun">
            <slot name="stop">
                <i class="fa-solid fa-circle-stop hover-opacity"
                   style="font-size: 26px; cursor: pointer; color: var(--el-color-danger-light-3);"></i>
            </slot>
        </template>
        <template v-else>
            <slot name="play">
                <i class="fa-solid fa-circle-play hover-opacity"
                   style="font-size: 26px; cursor: pointer; color: var(--el-color-info);"></i>
            </slot>
        </template>
    </Link>
</template>

<style lang="scss" scoped>
.el-icon {
    opacity: 0.8;

    &:hover {
        color: var(--el-text-color-primary)
    }
}
</style>
