<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import Pagination from "@/Components/Pagination.vue";
import {CirclePlusFilled, List, Menu, Search} from '@element-plus/icons-vue'
import User from "@/Components/User.vue";
import Time from "@/Components/Time.vue";
import Activity from "@/Components/Activity.vue";
import useList from "@/Composables/useList.js";

defineOptions({layout: [Layout]})

const props = defineProps({
    projects: {
        required: true
    },
    search: {
        type: String,
        required: false,
        default: ''
    },
});

const {query} = useList({only: ['projects']});
</script>

<template>
    <Head title="Projects"/>

    <el-page-header @back="() => router.visit($route('dashboard'))">
        <template #content>
            <div style="display: flex; align-items: center;">
                <el-icon style="margin-right: 10px; margin-top: -2px;">
                    <Menu/>
                </el-icon>
                <span>Projects</span>
            </div>
        </template>
        <template #extra>
            <div class="flex items-center">
                <Link preserve-state preserve-scroll :only="['modal', 'flash']" :href="$route('projects.create')">
                    <el-button type="success" :icon="CirclePlusFilled" style="">Create</el-button>
                </Link>
            </div>
        </template>
    </el-page-header>

    <br>

    <el-input
        :prefix-icon="Search"
        :clearable="true"
        v-model="query.search"
        placeholder="Type to search"
        size="large"
        autofocus
        class="w-full"></el-input>

    <br><br>

    <div v-if="projects?.data.length > 0">
        <div v-for="item in projects.data" :key="item.id">
            <el-card class="box-card project-card">
                <template #header>
                    <div class="card-header">
                        <div style="display: flex; align-items:center;">
                            <el-avatar
                                :size="32"
                                style="margin-right: 10px; margin-top: -2px;"
                                :src="item.avatar"
                            />
                            <Link :href="$route('project', {project: item.id})">
                                {{ item.name }}
                            </Link>

                            <template v-if="item.latest_activity">
                                <el-divider direction="vertical"></el-divider>
                                <div class="last-activity">
                                    <User :user="item.latest_activity.user"/>
                                    <Activity :activity="item.latest_activity"/>
                                    <Time :time="item.latest_activity.created_at"/>
                                </div>
                            </template>
                        </div>
                        <div>
                            <Link :href="$route('project.tasks', {project: item.id})">
                                <el-button class="button" type="primary" :icon="List">Tasks</el-button>
                            </Link>
                            &nbsp;
                            <Link preserve-state preserve-scroll :only="['modal']"
                                  :href="$route('project.tasks.create', {project: item.id})">
                                <el-button class="button" type="success" :icon="CirclePlusFilled">Add task</el-button>
                            </Link>
                        </div>
                    </div>
                </template>
                <div class="text item">
                    {{ item.description || '&nbsp;' }}
                </div>
            </el-card>
            <br>
        </div>
        <pagination :data="projects"/>
    </div>
    <div v-else>
        <el-empty description="Projects not found"/>
    </div>
</template>

<style lang="scss" scoped>
.project-card {
    .last-activity {
        display: flex;
        align-items: center;
        font-size: 12px;
        color: var(--el-text-color-secondary);

        * {
            margin: 0 5px;
        }
    }
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
</style>
