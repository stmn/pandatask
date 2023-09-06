<template>
  <el-dropdown>
    <template #default>
      <slot />
    </template>
    <template #dropdown>
      <Link preserve-state preserve-scroll :only="['modal', 'tasks']"
            v-if="$can('delete task', task)"
            method="delete"
            :href="$route('project.task.delete', {project:task.project_id, task: task.number})">
        <el-dropdown-item>
          <el-text size="small">
            <i class="fas fa-trash pr-1"/>Delete
          </el-text>
        </el-dropdown-item>
      </Link>
    </template>
  </el-dropdown>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
import TaskNumber from "@/Components/Task/TaskNumber.vue";

defineProps({
    task: Object
})
</script>
