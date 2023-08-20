<script setup>
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import Layout from "~/js/Layouts/Layout.vue";
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import ActivityList from "@/Components/Activity/ActivityList.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    activeTab: String,
    project: Object,
    activities: Object,
});

const activeTab = ref(props.activeTab);
</script>

<template>
    <Head :title="project.name + ' - Activities'"/>
    <div mt-3>
        <ActivityList :activities="activities"
                      show-details
                      show-task/>
    </div>
</template>
