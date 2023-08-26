<script setup>
import {Head} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import TasksTable from "@/Components/Task/TasksTable.vue";
import {useCreateList} from "@/Composables/useList.js";
import TasksHeader from "~/js/Pages/Tasks/TasksHeader.vue";
import HeroCard from "@/Components/Common/HeroCard.vue";

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

    <HeroCard v-if="!tasks.data.length"
              title="Tasks not found"
              description="It looks like you don't have access to any tasks yet."
              type="not-found">
    </HeroCard>

    <TasksTable v-else
                :tasks="tasks"
                show-project
                :selected-columns="['project_id', 'status_id', 'priority_id', 'latest_activity_at']"/>
</template>
