<script setup>
import {router, Link} from '@inertiajs/vue3'
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
// import Edit from "@/Pages/Profile/Edit.vue";
import {Clock, List, View} from "@element-plus/icons-vue";

defineOptions({layout: Layout})

const props = defineProps({
    activeTab: {
        type: String,
        required: true,
        default: 'overview'
    },
    project: {
        type: Object,
        required: true
    },
});

const activeTab = ref(props.activeTab);

router.on('start', (event) => {

})

router.on('success', (event) => {
    activeTab.value = event.detail.page.props.activeTab;
})

const handleClick = (index) => {
    router.visit(route('project.' + activeTab.value, {project: props.project.id}), {})
};

const onBack = () => {
    router.visit(route('projects'))
}
</script>

<template>
    <el-page-header @back="onBack">
        <!--        <template #breadcrumb>-->
        <!--            <el-breadcrumb separator="/">-->
        <!--                <el-breadcrumb-item :to="{ path: './page-header.html' }">-->
        <!--                    homepage-->
        <!--                </el-breadcrumb-item>-->
        <!--                <el-breadcrumb-item-->
        <!--                ><a href="./page-header.html">route 1</a></el-breadcrumb-item-->
        <!--                >-->
        <!--                <el-breadcrumb-item>route 2</el-breadcrumb-item>-->
        <!--            </el-breadcrumb>-->
        <!--        </template>-->
        <template #content>
            <div style="display: flex; align-items: center;">
                <el-avatar
                    :size="32"
                    style="margin-right: 10px;"
                    :src="project.avatar"
                />
                <span class="text-large font-600 mr-3"> {{ project.name }} </span>
                <small
                    class="text-sm mr-2"
                    style="color: var(--el-text-color-regular)"
                >
                    <!--             ({{ activeTab }})-->
                </small>
                <!--                <el-tag>Default</el-tag>-->
            </div>
        </template>
        <template #extra>
            <div class="flex items-center">
                <Link :href="route('projects.edit', {project: project.id})">
                <el-button type="primary" class="ml-2">
                    <el-icon>
                        <Edit/>
                    </el-icon> &nbsp; Edit
                </el-button>
                </Link>
            </div>
        </template>
    </el-page-header>
    <br>
    <el-tabs v-model="activeTab" class="demo-tabs" @tab-change="handleClick">
        <el-tab-pane name="overview" lazy>
            <template #label>
                <el-icon>
                    <Grid/>
                </el-icon> &nbsp; Overview
            </template>
            <slot/>
        </el-tab-pane>
        <el-tab-pane name="tasks" lazy>
            <template #label>
                <el-icon>
                    <List/>
                </el-icon> &nbsp; Tasks
            </template>
            <slot/>
        </el-tab-pane>
        <el-tab-pane name="timesheets" lazy>
            <template #label>
                <el-icon>
                    <Clock/>
                </el-icon> &nbsp; Timesheets
            </template>
            <slot/>
        </el-tab-pane>
        <el-tab-pane name="activity" lazy>
            <template #label>
                <el-icon>
                    <View/>
                </el-icon> &nbsp; Activity
            </template>
            <slot/>
        </el-tab-pane>
    </el-tabs>
</template>
