<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {onMounted} from "vue";
import Layout from "~/js/Layouts/Layout.vue";
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import TasksTable from "@/Components/Task/TasksTable.vue";
import ListContainer from "@/Components/List/ListContainer.vue";
import TasksFilters from "@/Components/Task/TasksFilters.vue";
import HeroCard from "@/Components/Common/HeroCard.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    activeTab: String,
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
<!--    <ProjectLayout :project="project" :active-tab="activeTab">-->
    <Head :title="project.name + ' - Tasks'"/>

<!--    <template #buttons>-->
<!--        <Link preserve-state preserve-scroll :only="['modal']"-->
<!--              :href="$route('project.tasks.create', {project: project.id})">-->
<!--            <el-button type="success" class="ml-2">-->
<!--                <i class="fa-solid fa-circle-plus mr-2"></i> Add task-->
<!--            </el-button>-->
<!--        </Link>-->
<!--    </template>-->


<!--    <div flex items-center mb-3>-->
<!--        <Link preserve-state preserve-scroll :only="['modal']"-->
<!--              :href="$route('project.tasks.create', {project: project.id})">-->
<!--            <el-button type="success">-->
<!--                <i class="fa-solid fa-circle-plus mr-2"></i>Add-->
<!--            </el-button>-->
<!--        </Link>-->
<!--    </div>-->

    <ListContainer searchable sortable columns
                   :only="['tasks']"
                   :sort="{value: 'latest_activity_at', label: 'Latest activity'}"
                   :list-key="`tasks_${project.id}`"
                   :selected-columns="['status_id', 'priority_id', 'latest_activity_at']">
        <template #default="{list}">
<!--            <div style="width: 250px;">-->
<!--                <TasksFilters/>-->
<!--            </div>-->
            <template v-if="tasks === undefined">

            </template>
            <template v-else-if="!tasks?.data?.length">
                <HeroCard title="Tasks not found"
                          description="Tasks are used to track work that needs to be done."
                          type="not-found"/>
            </template>
            <TasksTable :tasks="tasks"
                        :selected-columns="list.selectedColumns"/>
        </template>
    </ListContainer>
<!--    </ProjectLayout>-->
</template>

<style>
.el-dropdown {
    cursor: pointer;
}

.el-dropdown span:focus-visible {
    outline: 0;
}
</style>
