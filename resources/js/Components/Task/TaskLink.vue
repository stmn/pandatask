<template>
  <div v-if="task.subject">
    <el-text truncated>
      <Link :href="$route('project.task', {project: task.project_id, task: task.number})">
        {{ task.subject }}
      </Link>
    </el-text>

    <TaskNumber :number="task.number" mx-1/>

    <template v-if="task.private">
      <el-tag size="small" type="info">Private</el-tag>
    </template>

    <template v-if="task.deleted_at">
      <el-tag size="small" type="danger">Deleted</el-tag>
    </template>

    <template v-if="task.comments_count">
      <i class="task-comments fa-solid fa-comment-dots" style="margin: 1px 2px 0 6px;"></i>
      <span style="font-size: 12px; font-weight: 600;">{{ task.comments_count }}</span>
    </template>
  </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import TaskNumber from "@/Components/Task/TaskNumber.vue";

defineProps({
  task: Object
})
</script>
