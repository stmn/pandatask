<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    project: Object
});

</script>

<template>
    <el-card :closable="false" type="info" shadow="never">
        <div flex items-center>
            <el-text>
                No tasks found.
            </el-text>

            <Link preserve-state
                  v-if="$can('create tasks', project)"
                  :href="$route('project.tasks.create', {project: project.id})"
                  :only="['modal', 'project']"
                  class="ml-4">
                <el-button type="success">
                    <i class="fas fa-circle-plus mr-2"/>Add
                </el-button>
            </Link>
        </div>
    </el-card>
</template>
