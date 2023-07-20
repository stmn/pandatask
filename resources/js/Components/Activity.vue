<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {computed, ref} from "vue";

const props = defineProps({
    activity: {
        type: Object,
        required: true
    },
    task: {
        type: Object,
    },
    onlyIcon: {
        type: Boolean,
        required: false,
        default: false
    }
})

const page = usePage()

const auth = computed(() => page.props.auth)

const url = ref(route('project.task', {project: props.activity.project_id, task: props.activity?.task?.number || props.task?.id}))
</script>

<template>
    <template v-if="onlyIcon">
        <span style="display: inline-flex;">
            <el-tooltip :content="activity.description">
<!--                <EditPen v-if="activity.type === 'task_commented'"/>-->
                <i v-if="activity.type === 'task_commented'" class="fa-solid fa-comment-dots"></i>
<!--                <CirclePlus v-if="activity.type === 'task_created'"/>-->
                <i v-if="activity.type === 'task_created'" class="fa-solid fa-square-plus"></i>
<!--                <Operation v-else />-->
                <i v-else class="fa-solid fa-sliders"></i>
        </el-tooltip>
        </span>
    </template>
    <Link v-else :href="url" class="activity">{{ activity.description }}</Link>
</template>

<style lang="scss" scoped>
.activity {
    cursor: pointer;
    text-decoration: none;
    display: inline-block;

    &.el-icon {
        font-size: 18px;
    }

    &:hover {
        text-decoration: underline;
    }
}
</style>
