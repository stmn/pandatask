<script setup>
import {Head, router} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import {onMounted, provide, ref} from "vue";
import DashboardDemoNotification from "~/js/Pages/Dashboard/DashboardDemoNotification.vue";
import DashboardSettingsButton from "~/js/Pages/Dashboard/DashboardSettingsButton.vue";
import DashboardHello from "~/js/Pages/Dashboard/DashboardHello.vue";
import DashboardProjects from "~/js/Pages/Dashboard/DashboardProjects.vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    hello: String,
    projects: {
        default: () => []
    },
    tasks: {
        default: () => []
    },
    dashboard_settings: Object
});

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
    const page = event.detail.page;
    if (page.props.tasks) {
        handleTasks(page);
    }
})

onMounted(() => {
    router.reload({
        only: ['tasks'],
        onSuccess: (response) => handleTasks(response)
    });
});
</script>

<template>
    <Head title="Dashboard"/>

    <div flex justify-between>
        <div>
            <DashboardHello :hello="hello"/>
        </div>
        <div>
            <DashboardSettingsButton :settings="dashboard_settings"/>
        </div>
    </div>

    <DashboardDemoNotification/>

    <br>

    <DashboardProjects :projects="projects"/>
</template>
