<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import Pagination from "@/Components/Pagination.vue";
import {CirclePlusFilled, List, Menu, Search} from '@element-plus/icons-vue'
import User from "@/Components/User.vue";
import Time from "@/Components/Time.vue";
import Activity from "@/Components/Activity.vue";
import useList from "@/Composables/useList.js";
import {onMounted, ref} from "vue";
import usePageLoading from "@/Composables/usePageLoading.js";

defineOptions({layout: [Layout]})

const props = defineProps({
    projects: {
        type: Array,
        required: true
    },
    search: {
        type: String,
        required: false,
        default: ''
    },
});

const {query} = useList({only: ['projects']});

// usePageLoading().loading.value = true;

// onMounted(() => {
//     router.reload({only: ['projects'], onSuccess: () => {
//             // usePageLoading().loading.value = false;
//         }});
// });
</script>

<template>
    <Head title="Projects"/>

    <el-page-header @back="() => router.visit(route('dashboard'))">
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
                <Link preserve-state preserve-scroll :only="['modal']" :href="route('projects.create')">
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
                            <!--                    <el-space :size="5" spacer="|">-->
                            <!--                        <div>-->
                            <el-avatar
                                :size="32"
                                style="margin-right: 10px; margin-top: -2px;"
                                :src="item.avatar"
                            />
                            <Link :href="route('project.overview', {project: item.id})">
                                {{ item.name }}
                            </Link>
                            <!--                        </div>-->

                            <template v-if="item.latest_activity">
                                <el-divider direction="vertical"></el-divider>
                                <div class="last-activity">
                                    <User :user="item.latest_activity.user"/>
                                    <Activity :activity="item.latest_activity"/>
                                    <Time :time="item.latest_activity.created_at"/>
                                </div>
                            </template>
                            <!--                    </el-space>-->
                        </div>
                        <div>
                            <Link :href="route('project.tasks', {project: item.id})">
                                <el-button class="button" type="primary" :icon="List">Tasks</el-button>
                            </Link>
                            &nbsp;
                            <Link preserve-state preserve-scroll :only="['modal']" :href="route('project.tasks.create', {project: item.id})">
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


.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
