<script setup>
import {router, usePage} from '@inertiajs/vue3'
import {computed} from "vue";

const props = defineProps({
    task: {
        type: Object,
    }
})

const auth = computed(() => usePage().props.auth)

const isRun = computed(() => {
    if (!props.task) {
        return auth.value.time?.task_id;
    }

    return auth.value.user.active_time?.task_id == props.task?.id;
});

const visible = computed(() => auth.value.user.active_time?.task_id || props.task?.id);

const url = computed(() => route(isRun.value ? 'timer.stop' : 'timer.start'))

const toggleTimer = () => {
    const toggle = () => {
        router.visit(url.value, {
            method: 'post',
            data: {task: props.task?.id},
            preserveState: true,
            preserveScroll: true,
            only: ['auth', 'flash', 'times'],
        })
    }

    if (!isRun.value) {
        axios.post(route('timer.check', {task: props.task.id}))
            .then(({data}) => {
                const isAdmin = usePage().props.auth.groups?.admin;
                if (!data.assigned && !isAdmin) {
                    ElMessageBox.confirm(
                        'You are not assigned to this task. Do you want to start the timer anyway?',
                        'Warning',
                        {
                            confirmButtonText: 'Start timer',
                            cancelButtonText: 'Cancel',
                            type: 'warning',
                        }
                    ).then(() => {
                        toggle()
                    });
                } else {
                    toggle()
                }
            }).catch(error => {
            console.log(error)
        });

    } else {
        toggle()
    }
}
</script>

<template>
    <span v-if="visible"
          @click="toggleTimer"
          style="display: inline-flex;"
          class="timer">
        <template v-if="isRun">
            <slot name="stop">
                <i class="fa-solid fa-circle-stop hover-opacity"
                   style="color: var(--el-color-danger-light-3);"></i>
            </slot>
        </template>
        <template v-else>
            <slot name="play">
                <i class="fa-solid fa-circle-play hover-opacity"
                   style="color: var(--el-color-primary-light-3);"></i>
            </slot>
        </template>
    </span>
</template>

<style lang="scss" scoped>
.el-icon {
    opacity: 0.8;

    &:hover {
        color: var(--el-text-color-primary)
    }
}

i {
    font-size: 26px;
    cursor: pointer;
}
</style>
