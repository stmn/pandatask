<script setup>
import {Head, router} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import {List, Search} from "@element-plus/icons-vue";
import TasksTable from "@/Components/TasksTable.vue";
import useList from "@/Composables/useList.js";
import {onMounted} from "vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    tasks: {},
    search: {
        type: String,
        required: false,
        default: ''
    },
});

const {query} = useList({only: ['tasks']});

onMounted(() => {
    router.reload({
        only: ['tasks']
    });
});
</script>

<template>
    <Head title="Tasks"/>

    <el-page-header @back="() => router.visit($route('dashboard'))">
        <template #content>
            <div style="display: flex; align-items: center;">
                <el-icon style="margin-right: 10px; margin-top: -2px;">
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
        v-model="query.search"
        placeholder="Search..."
        size="large"
        class="w-full"></el-input>

    <br><br>

    <TasksTable :tasks="tasks" show-project/>
</template>
