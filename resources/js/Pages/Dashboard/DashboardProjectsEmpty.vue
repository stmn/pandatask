<script setup>
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    project: Object
});

</script>

<template>
    <el-alert :closable="false" type="info">
        <div flex items-center>
            No tasks found.

            <Link preserve-state
                  :href="$route('project.tasks.create', {project: project.id})"
                  class="ml-2">
                <el-button type="success" size="small">
                    <i class="fas fa-circle-plus mr-2"/>Add
                </el-button>
            </Link>
        </div>
    </el-alert>
</template>
