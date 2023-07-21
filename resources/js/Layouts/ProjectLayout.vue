<script setup>
import {Link, router} from '@inertiajs/vue3'
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";

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

/**
 * Project dropdown
 */

const projectValue = ref('')
const projectsSelectRef = ref();
const dropdownRef = ref();
const onOpen = (visible) => {
    if (visible) {
        setTimeout(() => {
            projectsSelectRef.value.focus()
        }, 150)
    }
}
const onProjectChange = (value) => {
    dropdownRef.value?.handleClose();
    router.visit(route('project.' + activeTab.value, {project: value.id}), {})
}

/**
 * Tabs
 */

const tabs = [
    {'icon': 'fa-list-check', 'name': 'tasks'},
    {'icon': 'fa-eye', 'name': 'activity'},
    {'icon': 'fa-clock', 'name': 'timesheets'},
    {'icon': 'fa-book', 'name': 'pages'},
    {'icon': 'fa-cog', 'name': 'settings'},
];
const activeTab = ref(props.activeTab);

const handleClick = (index) => {
    router.visit(
        route('project.' + index.paneName, {project: props.project.id}), {
            only: ['tasks', 'activities', 'times', 'pages', 'page'],
            preserveState: true,
            onSuccess: () => {
                activeTab.value = index.paneName;
            }
        })
};
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

                <el-dropdown trigger="click" ref="dropdownRef" @visible-change="onOpen"
                             style="color: inherit; font-size: inherit; line-height: inherit; cursor: pointer;">
                    <span>{{ project.name }} <i class="fa-solid fa-angle-down" style="opacity: 0.4;"></i></span>

                    <template #dropdown>
                        <el-select ref="projectsSelectRef" :persistent="false" v-model="projectValue"
                                   @change="onProjectChange" value-key="id" filterable
                                   placeholder="Select">
                            <el-option
                                v-for="item in projects"
                                :key="item.id"
                                :label="item.name"
                                :value="item"
                            ></el-option>
                        </el-select>
                    </template>
                </el-dropdown>

                <Link preserve-state preserve-scroll :only="['modal']"
                      :href="$route('project.overview', {project: project.id})">
                    <i class="fa-solid fa-circle-info ml-2"></i>
                </Link>
            </div>
        </template>

        <template #extra>
            <Link preserve-state preserve-scroll :only="['modal']"
                  :href="$route('projects.edit', {project: project.id})">
                <el-button :color="$primaryColor()">
                    <i class="fa-solid fa-pen-to-square mr-2"></i> Edit
                </el-button>
            </Link>
        </template>
    </el-page-header>

    <br>

    <el-tabs v-model="activeTab" @tab-click="handleClick">
        <el-tab-pane :name="tab.name" v-for="tab in tabs">
            <template #label>
                <i :class="`fa-solid ${tab.icon} mr-2`"></i> {{ tab.name.charAt(0).toUpperCase() + tab.name.slice(1) }}
            </template>
        </el-tab-pane>
    </el-tabs>

    <slot/>
</template>
