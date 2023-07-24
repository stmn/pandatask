<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import Layout from "~/js/Layouts/Layout.vue";
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import TasksTable from "~/js/Components/Task/TasksTable.vue";
import useList from "@/Composables/useList.js";
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

const tableData = props.tasks;

const {query} = useList({only: ['tasks']});

const predefinedFilters = [
    {
        text: 'All tasks',
        value: ''
    },
    {
        text: 'My tasks',
        value: 'assignees: (Dariusz Cichoń)'
    },
    {
        text: 'My tasks or free tasks',
        value: 'assignees: (Dariusz Cichoń) or not assignees'
    },
];

const changeFilter = (value) => {
    query.search = value;
};

const filters = [];

const checkboxGroup1 = ref(['Shanghai'])
const cities = ['Not started', 'In Progress', 'Done']

onMounted(() => {
    router.reload({only: ['tasks']});
});
</script>

<template>
    <Head :title="project.name + ' - Tasks'"/>

    <!--    <el-dropdown style="margin-bottom: 10px;">-->
    <!--    <span class="el-dropdown-link">-->
    <!--      Saved filters-->
    <!--    </span>-->
    <!--        <template #dropdown>-->
    <!--            <el-dropdown-menu>-->
    <!--                <el-dropdown-item v-for="filter in predefinedFilters" @click="changeFilter(filter.value)">-->
    <!--                    {{ filter.text }}-->
    <!--                </el-dropdown-item>-->
    <!--            </el-dropdown-menu>-->
    <!--        </template>-->
    <!--    </el-dropdown>-->

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

    <!--    <el-switch-->
    <!--        size="small"-->
    <!--        active-text="Advanced search"-->
    <!--    />-->

    <!--    <br><br>-->
    <!--    <el-config-provider size="small">-->
    <!--    <el-card>-->
    <!--        <el-checkbox-group v-model="checkboxGroup1">-->
    <!--            <el-checkbox-button v-for="city in cities" :key="city" :label="city">-->
    <!--                {{ city }}-->
    <!--            </el-checkbox-button>-->
    <!--        </el-checkbox-group>-->
    <!--        <br>-->
    <!--        <el-input-->
    <!--            :clearable="true"-->
    <!--            placeholder="Cost"></el-input>-->
    <!--    </el-card>-->
    <!--    </el-config-provider>-->

    <br><br>

    <div flex items-center>
        <Link preserve-state preserve-scroll :only="['modal']"
              :href="$route('project.tasks.create', {project: project.id})">
            <el-button type="success">
                <i class="fa-solid fa-circle-plus mr-2"></i>Add
            </el-button>
        </Link>

        <div ml-auto>
            Group by <u>status</u> <el-divider direction="vertical"></el-divider> Sort by <u>priority</u>
        </div>
    </div>

    <KanbanBoard :tasks="tasks" :project="project" />
</template>

<style>
.el-dropdown {
    cursor: pointer;
}

.el-dropdown span:focus-visible {
    outline: 0;
}
</style>
