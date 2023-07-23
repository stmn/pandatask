<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import TasksTable from "~/js/Components/Task/TasksTable.vue";
import {onMounted, provide, ref} from "vue";
import usePageLoading from "@/Composables/usePageLoading.js";
import Settings from "~/js/Components/Dashboard/DashboardSettings.vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    hello: String,
    projects: {
        default: () => []
    },
    tasks: {
        default: () => []
    },
    settings: {
        required: true,
    }
});

usePageLoading().loading.value = true;

const projects = ref(props.projects);
const tasks = ref(props.tasks);

function handleTasks(response) {
    for (let i = 0; i < projects.value?.length || 0; i++) {
        projects.value[i].tasks = response.props.tasks?.['project_' + projects.value[i].id];
    }
}

function handleProjects(response) {
    projects.value = response.props.projects;
}

provide('handleTasks', handleTasks);
provide('handleProjects', handleProjects);

router.on('success', (event) => {
    if (event.detail.page.props.tasks) {
        handleTasks(event.detail.page);
    }
})

onMounted(() => {
    // router.reload({
    //     only: ['projects'], onSuccess: (response) => {
    //         handleProjects(response);

            router.reload({
                only: ['tasks'], onSuccess: (response) => {
                    handleTasks(response);
                    usePageLoading().loading.value = false;
                }
            });

            // usePageLoading().loading.value = false;
        // }
    // });
});

const showSettings = ref(false);
</script>

<template>
    <Head title="Dashboard"/>

    <div flex justify-between>
        <div>
            <b>{{ hello }}</b>, {{ usePage().props.auth.user.first_name }}!
            <i class="fa-regular fa-face-smile-wink ml-1"/>
        </div>

        <div>
            <!-- Customization Button -->
            <el-popover :visible="showSettings" trigger="click" placement="left" width="300">
                <template #default>
                    <Settings :settings="settings" @close="showSettings = false"/>
                </template>
                <template #reference>
                    <el-button link @click="showSettings = !showSettings">
                        <i class="fas fa-gear hover-rotate" style="cursor: pointer; font-size: 24px;"/>
                    </el-button>
                </template>
            </el-popover>
        </div>
    </div>

    <el-alert
        type="info"
        show-icon
        style="margin: 10px 0 0 0; text-align:left;">
        <template #title>
            <b>Attention!</b>
        </template>
        <template #default>
            This is a demonstration version of the <b>Pandatask</b> early build. Database is reset <b>every 4 hours</b>.<br>
            <b>Feel free</b> to create new users, projects, tasks, timesheets, etc.<br>
            Some features may be disabled or limited.
        </template>
    </el-alert>

    <br>

    <el-config-provider size="default">
        <div v-for="project in projects" :key="project.id">
            <el-divider content-position="left">
                <Link :href="$route('project', {project: project.id})"
                      flex items-center>
                    <el-avatar
                        :size="24"
                        class="mr-3"
                        :src="project.avatar"
                    />
                    <span>{{ project.name }}</span>
                </Link>
            </el-divider>

            <div v-if="project.tasks === undefined">
                <el-skeleton :rows="9" animated/>
            </div>

            <div v-else-if="project.tasks.length">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="9" animated/>
                    </template>

                    <TasksTable :tasks="project.tasks"/>

                    <div text-right>
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
                    </div>
                </lazy-component>
            </div>

            <div v-else>
                <el-alert :closable="false" type="info">
                    No tasks found.

                    <Link preserve-state
                          :href="$route('project.tasks.create', {project: project.id})"
                          class="ml-2">
                        <el-button type="success" size="small">
                            <i class="fas fa-circle-plus mr-2"/>Add
                        </el-button>
                    </Link>
                </el-alert>
            </div>
        </div>
    </el-config-provider>
</template>
