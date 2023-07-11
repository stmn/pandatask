<script setup>
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import TasksTable from "@/Components/TasksTable.vue";
import {CirclePlusFilled, Setting} from "@element-plus/icons-vue";
import {onMounted, provide, ref} from "vue";
import usePageLoading from "@/Composables/usePageLoading.js";
import Settings from "@/Components/Dashboard/Settings.vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    hello: {
        type: String,
        required: false,
        default: 'Hello'
    },
    projects: {
        required: true
    },
    tasks: {
        required: false,
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
    for (let i = 0; i < projects.value.length; i++) {
        projects.value[i].tasks = response.props.tasks['project_' + projects.value[i].id];
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
    router.reload({
        only: ['projects'], onSuccess: (response) => {
            handleProjects(response);

            router.reload({
                only: ['tasks'], onSuccess: (response) => {
                    handleTasks(response);
                    usePageLoading().loading.value = false;
                }
            });

            usePageLoading().loading.value = false;
        }
    });
});

const showSettings = ref(false);
</script>

<template>
    <Head title="Dashboard"/>

    <div class="el-page-header__header chuj">
        <div class="el-page-header__left">
            <div class="el-page-header__content">
                <div style="display: flex; justify-content: space-around; width: 100%;">
                    <span><b>{{ hello }}</b>, {{ usePage().props.auth.user.first_name }}!</span>
                </div>
            </div>
        </div>
        <div class="el-page-header__right">
            <!-- Customization Button -->
            <el-popover :visible="showSettings" trigger="click" placement="left" width="300">
                <template #default>
                    <Settings :settings="settings" @close="showSettings = false"/>
                </template>
                <template #reference>
                    <el-button link @click="showSettings = !showSettings">
<!--                        <el-tooltip show-after="300" :disabled="showSettings" placement="left" content="Customize">-->
                            <el-icon size="24" style="cursor: pointer;" class="hover-rotate">
                                <Setting/>
                            </el-icon>
<!--                        </el-tooltip>-->
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
                <div class="flex items-center">
                    <Link :href="route('project', {project: project.id})"
                          style="display: flex; align-items: center;">
                        <el-avatar
                            :size="24"
                            style="margin-right: 10px;"
                            :src="project.avatar"
                        />
                        <span>{{ project.name }}</span>
                    </Link>
                </div>
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
                    <div style="text-align: right;">
                        <Link :href="route('project.tasks', {project: project.id})">
                            <el-button type="primary">View all tasks</el-button>
                        </Link>
                        &nbsp;
                        <Link preserve-state preserve-scroll
                              :href="route('project.tasks.create', {project: project.id})"
                              :only="['modal']">
                            <el-button type="success" :icon="CirclePlusFilled">Add task</el-button>
                        </Link>
                    </div>
                </lazy-component>
            </div>
            <div v-else>
                <el-alert :closable="false" type="info">
                    No tasks found.
                    <Link preserve-state :href="route('project.tasks.create', {project: project.id})"
                          style="margin-left: 5px;">
                        <el-button type="success" size="small" :icon="CirclePlusFilled">Add</el-button>
                    </Link>
                </el-alert>
            </div>
        </div>
    </el-config-provider>
</template>
