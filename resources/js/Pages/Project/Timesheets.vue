<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {onMounted, ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import Timesheets from "@/Components/Timesheets.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    activeTab: {
        type: String,
        required: true,
        default: 'overview'
    },
    project: {
        type: Object,
    },
    times: {},
});

const activeTab = ref(props.activeTab);

onMounted(() => {
    router.reload({only: ['times']});
});
</script>

<template>
    <Head :title="project.name + ' - Timesheets'"/>

    <Link preserve-state :href="$route('project.timesheets.create', {project: project.id})">
        <el-button type="success">
            <i class="fa-solid fa-circle-plus mr-2"></i>Add
        </el-button>
    </Link>

    <Timesheets :times="times"/>
</template>
