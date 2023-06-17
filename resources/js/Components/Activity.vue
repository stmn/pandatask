<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {computed, ref} from "vue";
import {EditPen, Operation, Setting} from "@element-plus/icons-vue";

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
            <el-icon class="activity">
                <EditPen v-if="activity.type === 'task_commented'"/>
                <Operation v-else />
            </el-icon> &nbsp;
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
