<script setup>
import {Head} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import TasksTable from "@/Components/Task/TasksTable.vue";
import {useCreateList} from "@/Composables/useList.js";
import TasksHeader from "~/js/Pages/Tasks/TasksHeader.vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    tasks: {},
});

const {list} = useCreateList({only: ['tasks']});
</script>

<template>
    <Head title="Tasks"/>

    <div mb-5>
        <TasksHeader />
    </div>

    <TasksTable :tasks="tasks" show-project
                :selected-columns="['project_id', 'status_id', 'priority_id', 'latest_activity_at']"/>
</template>
