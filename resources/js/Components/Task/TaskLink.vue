<template>
    <div v-if="task.subject">
        <el-text truncated>
            <Link :href="$route('project.task', {project: task.project_id, task: task.number})">
                {{ task.subject }}
            </Link>
        </el-text>

        <span class="task-number">#{{ task.number }}</span>

        <template v-if="task.comments_count">
            <i class="task-comments fa-solid fa-comment-dots" style="margin: 1px 2px 0 6px;"></i>
            <span style="font-size: 12px; font-weight: 600;">{{ task.comments_count }}</span>
        </template>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";

defineProps({
    task: Object
})
</script>

<style scoped>
.task-number {
    background: var(--el-bg-color);
    padding: 0 3px;
    margin-left: 5px;
    margin-right: 5px;
    word-break: keep-all;
    font-size: 13px;
    line-height: 22px;
}
</style>
