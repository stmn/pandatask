<script setup>
import {Link, router} from '@inertiajs/vue3'
import Layout from "@/Layouts/Layout.vue";
import {ref, watch} from "vue";
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
    projects: {
        type: Array,
        required: false,
        default: () => []
    }
});

const activeTab = ref(props.activeTab);

// watch(() => props.activeTab, (value) => {
//     console.log('newval', value)
// })

const handleClick = (index) => {
    // console.log('handleClick', index.paneName);
    // console.log(route('project.' + activeTab.value, {project: props.project.id}))
    // setTimeout(() => {
    //     console.log('visit', activeTab.value)
        router.visit(route('project.' + index.paneName, {project: props.project.id}), {
            only: ['tasks', 'activities', 'times'],
            preserveState: true,
            onSuccess: () => {
                activeTab.value = index.paneName;
            }
        })
    // }, 5000)

};

const projectValue = ref('')
const projectsSelectRef = ref();
const dropdownRef = ref();
const onOpen = (visible) => {
    if(visible) {
        setTimeout(() => {
            projectsSelectRef.value.focus()
        }, 150)
    }
}
const onProjectChange = (value) => {
    dropdownRef.value?.handleClose();
    router.visit(route('project.' + activeTab.value, {project: value.id}), {})
}
</script>

<template>
    <el-page-header @back="() => router.visit(route('projects'))">
        <template #content>
            <div style="display: flex; align-items: center;">
                <el-avatar
                    :size="32"
                    style="margin-right: 10px;"
                    :src="project.avatar"
                />

                <el-dropdown trigger="click" ref="dropdownRef" @visible-change="onOpen" style="color: inherit; font-size: inherit; line-height: inherit; cursor: pointer;">
                    <span>{{ project.name }} <el-icon style="margin-top: 3px; margin-left: 5px; position: absolute; opacity: 0.4;"><arrow-down/></el-icon></span>

                    <template #dropdown>
                        <el-select ref="projectsSelectRef" :persistent="false" v-model="projectValue" @change="onProjectChange" value-key="id" filterable
                                   placeholder="Select">
                            <el-option
                                v-for="item in projects"
                                :key="item.id"
                                :label="item.name"
                                :value="item"
                            >
<!--                                <span style="float: left">{{ item.name }}</span>-->
<!--                                <span-->
<!--                                    style="-->
<!--          float: right;-->
<!--          color: var(&#45;&#45;el-text-color-secondary);-->
<!--          font-size: 13px;-->
<!--        "-->
<!--                                >ID: {{ item.id }}</span-->
<!--                                >-->
                            </el-option>
                        </el-select>
                    </template>
                </el-dropdown>
            </div>
        </template>
        <template #extra>
            <div class="flex items-center">
                <Link preserve-state preserve-scroll :only="['modal']" :href="route('projects.edit', {project: project.id})">
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
    <el-tabs v-model="activeTab" @tab-click="handleClick">
        <el-tab-pane name="overview">
            <template #label>
                <el-icon>
                    <Grid/>
                </el-icon> &nbsp; Overview
            </template>
        </el-tab-pane>
        <el-tab-pane name="tasks">
            <template #label>
                <el-icon>
                    <List/>
                </el-icon> &nbsp; Tasks
            </template>
        </el-tab-pane>
        <el-tab-pane name="timesheets">
            <template #label>
                <el-icon>
                    <Clock/>
                </el-icon> &nbsp; Timesheets
            </template>
        </el-tab-pane>
        <el-tab-pane name="activity">
            <template #label>
                <el-icon>
                    <View/>
                </el-icon> &nbsp; Activity
            </template>
        </el-tab-pane>
    </el-tabs>
    <slot />
</template>
