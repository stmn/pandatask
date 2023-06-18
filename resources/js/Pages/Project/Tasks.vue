<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import {CirclePlusFilled, Comment, Search} from "@element-plus/icons-vue";
import {watchDebounced} from "@vueuse/core";
import Timer from "@/Components/Timer.vue";
import User from "@/Components/User.vue";
import Time from "@/Components/Time.vue";
import Activity from "@/Components/Activity.vue";
import Pagination from "@/Components/Pagination.vue";
import TasksTable from "@/Components/TasksTable.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    activeTab: {
        type: String,
        required: true,
        default: 'overview'
    },
    search: {
        type: String,
        required: false,
        default: ''
    },
    project: {
        type: Object,
        required: true
    },
    tasks: {
        type: Object,
        required: true
    },
});

const activeTab = ref(props.activeTab);

const handleClick = (index) => {
    // router.visit('/project/1/'+activeTab.value, {
    //
    // })
};

const tableData = props.tasks;

const search = ref(props.search);

watchDebounced(
    search,
    () => {
        router.replace(route('project.tasks', {project: props.project.id, search: search.value}))
    },
    {debounce: 200, maxWait: 500},
)
</script>

<template>
    <Head :title="project.name + ' - Tasks'"/>

    <el-input
        :prefix-icon="Search"
        :clearable="true"
        v-model="search"
        placeholder="Search..."
        size="large"
        class="w-full"></el-input>

    <br><br>

    <Link preserve-state :href="route('project.tasks.create', {project: project.id})">
        <el-button type="success">
            <el-icon>
                <CirclePlusFilled/>
            </el-icon> &nbsp; Add
        </el-button>
    </Link>

    <TasksTable :tasks="tasks.data" />
</template>

<style>
.el-menu {
    border: 0;
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
