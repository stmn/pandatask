<script setup>
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import Activities from "@/Components/Activities.vue";
import {useDateFormat} from "@vueuse/core";
import User from "@/Components/User.vue";

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
        type: Array,
        required: true
    },
    times: {
        type: Array,
        required: true
    },
});

const activeTab = ref(props.activeTab);
</script>

<template>
    <Head :title="project.name + ' - Overview'"/>

    <!--    <br>-->
    <el-config-provider size="small">
        <!--        <el-row>-->
        <!--            <el-col :span="6">-->
        <!--                <el-statistic title="Open tasks" :value="100"/>-->
        <!--            </el-col>-->
        <!--            <el-col :span="6">-->
        <!--                <el-statistic title="Tasks in progress" :value="25"/>-->
        <!--            </el-col>-->
        <!--            <el-col :span="6">-->
        <!--                <el-statistic title="Closed tasks" :value="50"/>-->
        <!--            </el-col>-->
        <!--            <el-col :span="6">-->
        <!--                <el-statistic title="All tasks" :value="150"/>-->
        <!--            </el-col>-->
        <!--        </el-row>-->

        <!--        <br>-->

        <el-divider content-position="left">General</el-divider>

        <el-descriptions
            direction="vertical"
            :column="3"
            border
        >
            <el-descriptions-item label="Client">
                <User v-if="project.client" :user="project.client"/>
                <span v-else>Not assigned</span>
            </el-descriptions-item>
            <el-descriptions-item label="Created at">
                {{ useDateFormat(project.created_at, 'DD-mm-YYYY').value }}
            </el-descriptions-item>
            <el-descriptions-item label="Members">
                <div style="display: flex; padding: 5px;">
                    <User v-for="member in project.members" :user="member"
                          only-avatar
                          style="display: inline; margin: 10px;"/>
                </div>
            </el-descriptions-item>
            <el-descriptions-item label="Description">
                {{ project.description || 'No description' }}
            </el-descriptions-item>
        </el-descriptions>

        <br>

        <el-row :gutter="12">
            <el-col :span="24">
                <el-divider content-position="left">Latest activity</el-divider>
                <Activities :activities="activities" show-task/>
            </el-col>
            <!--            <el-col :span="12">-->
            <!--                <el-divider content-position="left">Latest timesheets</el-divider>-->
            <!--                <Timesheets :times="times"-->
            <!--                            only-avatar-->
            <!--                            :cols="['timer', 'task_id', 'start_at', 'end_at', 'author_id']"/>-->
            <!--            </el-col>-->
        </el-row>
    </el-config-provider>
</template>
