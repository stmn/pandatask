<script setup>
import {Head, Link} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import TasksTable from "@/Components/TasksTable.vue";
import {CirclePlusFilled} from "@element-plus/icons-vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    projects: {
        type: Array,
        required: true
    },
});
</script>

<template>
    <Head title="Dashboard"/>

    <div class="el-page-header__header">
        <div class="el-page-header__left">
            <div class="el-page-header__content">
                <div>
                    <span>Dashboard</span>
                </div>
            </div>
        </div>
    </div>

    <el-config-provider size="default">
        <div v-for="project in projects">
            <el-divider content-position="left">
                <div class="flex items-center">
                    <Link :href="route('project.overview', {project: project.id})" style="display: flex; align-items: center;">
                        <el-avatar
                            :size="24"
                            style="margin-right: 10px;"
                            :src="project.avatar"
                        />
                        <span>{{ project.name }}</span>
                    </Link>
                </div>
            </el-divider>

            <div v-if="project.tasks.length">
                <TasksTable :tasks="project.tasks"/>
                <div style="text-align: right;">
                    <Link :href="route('project.tasks', {project: project.id})">
                        <el-button type="primary">View all tasks</el-button>
                    </Link>
                    &nbsp;
                    <Link preserve-state :href="route('project.tasks.create', {project: project.id})">
                        <el-button type="success">
                            <el-icon>
                                <CirclePlusFilled/>
                            </el-icon> &nbsp; Add task
                        </el-button>
                    </Link>
                </div>
            </div>
            <div v-else>
                <el-alert :closable="false" type="info">
                    No tasks found.
                    <Link preserve-state :href="route('project.tasks.create', {project: project.id})" style="margin-left: 5px;">
                        <el-button type="success" size="small">
                            <el-icon>
                                <CirclePlusFilled/>
                            </el-icon> &nbsp; Add
                        </el-button>
                    </Link>
                </el-alert>
            </div>
        </div>
    </el-config-provider>
</template>

<style lang="scss">
/*.el-menu {*/
/*    border: 0;*/
/*}*/

/*.bg-dots-darker {*/
/*    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");*/
/*}*/

/*@media (prefers-color-scheme: dark) {*/
/*    .dark\:bg-dots-lighter {*/
/*        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");*/
/*    }*/
//}
</style>
