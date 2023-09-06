<template>
  <el-dropdown>
    <template #default>
      <slot />a
    </template>
    <template #dropdown>
      <Link preserve-state preserve-scroll :only="['modal', 'activity', 'activities']"
            v-if="$can('delete activity', activity)"
            method="delete"
            :href="$route('project.activity.delete', {project:activity.project_id, activity: activity.id})">
        <el-dropdown-item>
          <el-text size="small">
            <i class="fa-fw fas fa-trash"/> Delete
          </el-text>
        </el-dropdown-item>
      </Link>

      <Link preserve-state preserve-scroll :only="['modal', 'activity', 'activities']"
            v-if="$can('delete activity', activity)"
            method="post"
            :href="$route('project.activity.private', {project:activity.project_id, activity: activity.id})">
        <el-dropdown-item>
          <el-text size="small">
            <i :class="`fa-fw fas fa-eye${!activity?.private ? '-slash' : ''}`"/> {{ activity?.private ? 'Make Public' : 'Make Private' }}
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
    activity: Object
})
</script>
