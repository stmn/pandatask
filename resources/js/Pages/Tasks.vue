<script setup>
import {Head, router} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import TasksTable from "~/js/Components/Task/TasksTable.vue";
import {useCreateList} from "@/Composables/useList.js";

defineOptions({layout: [Layout]})

const props = defineProps({
    tasks: {},
    search: {
        type: String,
        required: false,
        default: ''
    },
});

const {list} = useCreateList({only: ['tasks']});
</script>

<template>
    <Head title="Tasks"/>

    <el-page-header @back="() => router.visit($route('dashboard'))">
        <template #content>
            <div flex items-center>
                <i class="fas fa-list-check mr-2"></i>
                <span>Tasks</span>
            </div>
        </template>
    </el-page-header>

    <br>

    <el-input
        :clearable="true"
        v-model="list.search"
        placeholder="Type to search"
        size="large"
        class="w-full">
        <template #prefix>
            <i class="fa-solid fa-search"></i>
        </template>
    </el-input>

    <br><br>

    <TasksTable :tasks="tasks" show-project
                :selected-columns="['project_id', 'status_id', 'priority_id', 'latest_activity_at']"/>
</template>
