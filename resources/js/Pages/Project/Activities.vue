<script setup>
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import Activities from "@/Components/Activities.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    activeTab: {
        type: String,
        required: true,
        default: 'overview'
    },
    project: {
        type: Object,
        required: true
    },
    activities: {
        type: Object,
        required: true
    },
});

const activeTab = ref(props.activeTab);
</script>

<template>
    <Head :title="project.name + ' - Activities'"/>

    <Activities :activities="activities" show-task/>
</template>
