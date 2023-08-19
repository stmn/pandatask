<script setup>
import {Head} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import ProjectsHeader from "~/js/Pages/Projects/ProjectsHeader.vue";
import ProjectsList from "~/js/Pages/Projects/ProjectsList.vue";
import ProjectsListItem from "~/js/Pages/Projects/ProjectsListItem.vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    projects: {
        required: true
    }
});
</script>

<template>
    <Head title="Projects"/>

    <div mb-5>
        <ProjectsHeader/>
    </div>

    <ProjectsList :projects="projects">
        <template #item="{item}">
            <ProjectsListItem :item="item"/>
        </template>
        <template #empty>
            <el-empty description="Projects not found"/>
        </template>
    </ProjectsList>
</template>
