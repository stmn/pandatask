<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import {List, Menu, Search} from "@element-plus/icons-vue";
import TasksTable from "@/Components/TasksTable.vue";
import {watchDebounced} from "@vueuse/core";

defineOptions({ layout: [Layout] })

const props = defineProps({
    tasks: {
        required: true,
    },
    search: {
        type: String,
        required: false,
        default: ''
    },
});

const search = ref(props.search);

watchDebounced(
    search,
    () => {
        router.replace(route('tasks', {search: search.value}))
    },
    {debounce: 200, maxWait: 500},
)
</script>

<template>
    <Head title="Tasks"/>

    <el-page-header @back="onBack">
        <template #content>
            <div style="display: flex; align-items: center;">
                <el-icon style="margin-right: 5px;">
                        <List/>
                    </el-icon>
                <span>Tasks</span>
            </div>
        </template>
    </el-page-header>

    <br>

    <el-input
        :prefix-icon="Search"
        :clearable="true"
        v-model="search"
        placeholder="Search..."
        size="large"
        class="w-full"></el-input>

    <br><br>

    <TasksTable :tasks="tasks" />
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
