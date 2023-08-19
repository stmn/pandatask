<script setup>
import {Link} from "@inertiajs/vue3";
import TasksTable from "@/Components/Task/TasksTable.vue";
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";
import DashboardProjectsEmpty from "~/js/Pages/Dashboard/DashboardProjectsEmpty.vue";
import DashboardProjectsFooter from "~/js/Pages/Dashboard/DashboardProjectsFooter.vue";

const props = defineProps({
    projects: Array
});
</script>

<template>
    <div v-for="project in projects" :key="project.id">
        <el-divider content-position="left">
            <Link :href="$route('project', {project: project.id})"
                  flex items-center>
                <BaseAvatar :avatar="project.avatar"
                            :name="project.name"
                            :size="24"
                            class="mr-3"/>
                <span>{{ project.name }}</span>
            </Link>
        </el-divider>

        <DashboardProjectsEmpty v-if="project.tasks_count === 0"
                                :project="project"/>

        <div v-else-if="project.tasks === undefined"
             style="line-height: 200px; min-height: 200px; text-align: center;">
            <!--                    <i class="fas fa-spinner fa-spin" style="font-size: 48px;"/>-->
        </div>

        <lazy-component v-else-if="project.tasks.length">
            <template #placeholder>
                <el-skeleton :rows="project.tasks_count" animated/>
            </template>

            <TasksTable :tasks="project.tasks"
                        :list-columns="['status_id', 'priority_id', 'latest_activity_at']"
                        :list-key="`tasks_${project.id}`"/>

            <div text-right>
                <DashboardProjectsFooter :project="project"/>
            </div>
        </lazy-component>
    </div>
</template>
