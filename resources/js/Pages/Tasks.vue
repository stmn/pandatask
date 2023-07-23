<script setup>
import {Head, router} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import TasksTable from "~/js/Components/Task/TasksTable.vue";
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
    // router.reload({
    //     only: ['tasks']
    // });
});
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
        v-model="query.search"
        placeholder="Type to search..."
        size="large"
        class="w-full">
        <template #prefix>
            <i class="fa-solid fa-search"></i>
        </template>
    </el-input>

    <br><br>

    <TasksTable :tasks="tasks" show-project/>
</template>
