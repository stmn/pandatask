<script setup>
import {Link, router} from '@inertiajs/vue3'
import Layout from "@/Layouts/Layout.vue";
import {ref, watch} from "vue";
// import Edit from "@/Pages/Profile/Edit.vue";

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
        router.visit(route('project.' + index.paneName, {project: props.project.id}), {
            only: ['tasks', 'activities', 'times', 'pages', 'page'],
            preserveState: true,
            onSuccess: () => {
                activeTab.value = index.paneName;
            }
        })
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
    <el-page-header @back="() => router.visit($route('projects'))">
        <template #content>
            <div style="display: flex; align-items: center;">
                <el-avatar
                    :size="24"
                    style="margin-right: 10px;"
                    :src="project.avatar"
                />

                <el-dropdown trigger="click" ref="dropdownRef" @visible-change="onOpen" style="color: inherit; font-size: inherit; line-height: inherit; cursor: pointer;">
                    <span>{{ project.name }} <i class="fa-solid fa-angle-down" style="opacity: 0.4;"></i></span>

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

                <Link preserve-state preserve-scroll :only="['modal']" :href="$route('project.overview', {project: project.id})">
                    <i class="fa-solid fa-circle-info ml-2 cursor-pointer"></i>
                </Link>
            </div>
        </template>
        <template #extra>
            <div class="flex items-center">
                <Link preserve-state preserve-scroll :only="['modal']" :href="$route('projects.edit', {project: project.id})">
                    <el-button :color="$primaryColor()" class="ml-2">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Edit
                    </el-button>
                </Link>
            </div>
        </template>
    </el-page-header>
    <br>
    <el-tabs v-model="activeTab" @tab-click="handleClick">
        <el-tab-pane name="tasks">
            <template #label>
                <i class="fa-solid fa-list-check mr-2"></i> Tasks
            </template>
        </el-tab-pane>

        <el-tab-pane name="activity">
            <template #label>
                <i class="fa-solid fa-eye mr-2"></i>Activity
            </template>
        </el-tab-pane>

        <el-tab-pane name="timesheets">
            <template #label>
                <i class="fa-solid fa-clock mr-2"></i>Timesheets
            </template>
        </el-tab-pane>

<!--        <el-tab-pane name="pages">-->
<!--            <template #label>-->
<!--                Wiki-->
<!--            </template>-->
<!--        </el-tab-pane>-->

<!--        <el-tab-pane name="overview" style="margin-left: auto; left: auto;">-->
<!--            <template #label>-->
<!--                <i class="fa-solid fa-table-columns mr-2"></i>Overview-->
<!--            </template>-->
<!--        </el-tab-pane>-->
    </el-tabs>
    <slot />
</template>

<style>
//.el-tabs__nav { width: 100% }
//#tab-overview { margin-left: auto; }
</style>
