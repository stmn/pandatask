<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import Layout from "~/js/Layouts/Layout.vue";
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import {useList} from "@/Composables/useList.js";
import KanbanBoard from "@/Components/Task/KanbanBoard.vue";

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
    },
});

const activeTab = ref(props.activeTab);

const {query} = useList({only: ['tasks']});

onMounted(() => {
    router.reload({only: ['tasks']});
});
</script>

<template>
    <Head :title="project.name + ' - Tasks'"/>

    <el-input
        :clearable="true"
        v-model="query.search"
        placeholder="Type to search"
        size="large"
        class="w-full">
        <template #prefix>
            <i class="fa-solid fa-search"></i>
        </template>
    </el-input>

    <br><br>

    <div flex items-center>
        <Link preserve-state preserve-scroll :only="['modal']"
              :href="$route('project.tasks.create', {project: project.id})">
            <el-button type="success">
                <i class="fa-solid fa-circle-plus mr-2"></i>Add
            </el-button>
        </Link>

        <div ml-auto>
            Group by <u>status</u>
            <el-divider direction="vertical"></el-divider>
            Sort by <u>priority</u>
        </div>
    </div>

    <KanbanBoard :tasks="tasks" :project="project"/>
</template>

<style>
.el-dropdown {
    cursor: pointer;
}

.el-dropdown span:focus-visible {
    outline: 0;
}
</style>
