<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import {CirclePlusFilled, Search} from "@element-plus/icons-vue";
import {watchDebounced} from "@vueuse/core";
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
        router.replace(route('project.tasks', {project: props.project.id, search: search.value || null}))
    },
    {debounce: 200, maxWait: 500},
)

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
    search.value = value;
};

const filters = [];

const checkboxGroup1 = ref(['Shanghai'])
const cities = ['Not started', 'In Progress', 'Done']


// function convertQueryToString(query) {
//     const params = new URLSearchParams(query);
//
//     let result = '';
//
//     for (const [key, value] of params.entries()) {
//         const [field, fieldKey] = key.split('[');
//         const fieldValue = fieldKey ? `${field}:${value}` : `${key}:${value}`;
//         result += result ? ` ${fieldValue}` : fieldValue;
//     }
//
//     return result;
// }

// Funkcja do zamiany czytelnego stringa na zapytanie
// function convertStringToQuery(string) {
//     const pairs = string.split(' ');
//
//     const params = new URLSearchParams();
//
//     pairs.forEach((pair) => {
//         const [key, value] = pair.split(':');
//         const [field, fieldKey] = key.split('[');
//
//         const paramKey = fieldKey ? `${field}[${fieldKey}` : key;
//         params.append(paramKey, value);
//     });
//
//     return params.toString();
// }

// setInterval(() => {
//     try {
//         console.log(convertStringToQuery('assignees:test category:1,2,3 status:1,2,3'))
//     } catch (e) {
//
//     }
// }, 1000)
</script>

<template>
    <Head :title="project.name + ' - Tasks'"/>

    <el-dropdown style="margin-bottom: 10px;">
    <span class="el-dropdown-link">
      Saved filters
      <el-icon class="el-icon--right">
        <arrow-down/>
      </el-icon>
    </span>
        <template #dropdown>
            <el-dropdown-menu>
                <el-dropdown-item v-for="filter in predefinedFilters" @click="changeFilter(filter.value)">
                    {{ filter.text }}
                </el-dropdown-item>
            </el-dropdown-menu>
        </template>
    </el-dropdown>

    <el-input
        :prefix-icon="Search"
        :clearable="true"
        v-model="search"
        placeholder="Search..."
        size="large"
        class="w-full"></el-input>

    <el-switch
        size="small"
        active-text="Advanced search"
    />

    <br><br>
    <el-config-provider size="small">
    <el-card>
        <el-checkbox-group v-model="checkboxGroup1">
            <el-checkbox-button v-for="city in cities" :key="city" :label="city">
                {{ city }}
            </el-checkbox-button>
        </el-checkbox-group>
        <br>
        <el-input
            :clearable="true"
            placeholder="Cost"></el-input>
    </el-card>
    </el-config-provider>

    <br><br>

    <Link preserve-state :href="route('project.tasks.create', {project: project.id})">
        <el-button type="success">
            <el-icon>
                <CirclePlusFilled/>
            </el-icon> &nbsp; Add
        </el-button>
    </Link>

    <TasksTable :tasks="tasks.data"/>
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

.el-dropdown { cursor: pointer;}
.el-dropdown span:focus-visible {
    outline: 0;
}
</style>
