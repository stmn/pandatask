<script setup>
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import Activities from "@/Components/Activities.vue";
import Timesheets from "@/Components/Timesheets.vue";
import {useDateFormat, useTimestamp} from "@vueuse/core";
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

const handleClick = (index) => {
    // router.visit('/project/1/'+activeTab.value, {
    //
    // })
};
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
                <User v-if="project.client" :user="project.client" /> <span v-else>Not assigned</span>
            </el-descriptions-item>
            <el-descriptions-item label="Created at">
                {{ useDateFormat(project.created_at, 'DD-mm-YYYY').value }}
            </el-descriptions-item>
            <el-descriptions-item label="Members">
                <div style="display: flex; padding: 5px;">
                    <User v-for="member in project.members" :user="member"
                          only-avatar
                          style="display: inline; margin: 10px;" />
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

<style lang="scss">
.el-menu {
    border: 0;
}

//.el-col {
//    text-align: center;
//}

.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
