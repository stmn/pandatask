<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    project: Object
});
</script>

<template>
    <Link :href="$route('project.tasks', {project: project.id})">
        <el-button :color="$primaryColor()">
            <i class="fas fa-list-check mr-2"/>Tasks
        </el-button>
    </Link>
    &nbsp;
    <Link preserve-state preserve-scroll
          :href="$route('project.tasks.create', {project: project.id})"
          :only="['modal']">
        <el-button type="success">
            <i class="fas fa-circle-plus mr-2"/>Add task
        </el-button>
    </Link>
</template>
