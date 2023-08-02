<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {onMounted} from "vue";
import Layout from "~/js/Layouts/Layout.vue";
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import TasksTable from "~/js/Components/Task/TasksTable.vue";
import {useList} from "@/Composables/useList.js";
import ListContainer from "@/Components/List/ListContainer.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    search: {
        type: String,
        required: false,
        default: ''
    },
    project: {
        type: Object,
        required: true
    },
    tasks: {
        type: Object,
    },
});

onMounted(() => {
    router.reload({only: ['tasks']});
});
</script>

<template>
    <Head :title="project.name + ' - Tasks'"/>

    <!--    {{ JSON.stringify(list) }}-->
    <div flex items-center mb-3>
        <Link preserve-state preserve-scroll :only="['modal']"
              :href="$route('project.tasks.create', {project: project.id})">
            <el-button type="success">
                <i class="fa-solid fa-circle-plus mr-2"></i>Add
            </el-button>
        </Link>
    </div>

    <ListContainer searchable sortable columns
                   :only="['tasks']"
                   :sort="{value: 'latest_activity_at', label: 'Latest activity'}"
                   :list-key="`tasks_${project.id}`"
                   :selected-columns="['status_id', 'priority_id', 'latest_activity_at']">
        <template #default="{list}">
            <TasksTable :tasks="tasks"
                        :selected-columns="list.selectedColumns"/>
        </template>
    </ListContainer>
</template>

<style>
.el-dropdown {
    cursor: pointer;
}

.el-dropdown span:focus-visible {
    outline: 0;
}
</style>
