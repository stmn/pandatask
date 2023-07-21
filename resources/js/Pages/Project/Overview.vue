<script setup>
import {Head} from '@inertiajs/vue3';
import {ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import {useDateFormat} from "@vueuse/core";
import User from "@/Components/User.vue";
import Modal from "@/Layouts/Modal.vue";

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
    activities: {
        type: Array,
    },
    times: {
        type: Array,
    },
});

const activeTab = ref(props.activeTab);
</script>

<template>
    <Head :title="project.name + ' - Overview'"/>

    <Modal>
        <template #title>Project informations</template>
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
                    <div style="display: flex; padding: 5px;">
                        <User v-for="client in project.clients" :user="client"
                              only-avatar
                              style="display: inline; margin: 10px;"/>
                    </div>
                </el-descriptions-item>
                <el-descriptions-item label="Created at">
                    {{ useDateFormat(project.created_at, 'DD-mm-YYYY').value }}
                </el-descriptions-item>
                <el-descriptions-item label="Team members">
                    <div style="display: flex; padding: 5px;">
                        <User v-for="member in project.team_members" :user="member"
                              only-avatar
                              style="display: inline; margin: 10px;"/>
                    </div>
                </el-descriptions-item>
                <el-descriptions-item label="Description">
                    {{ project.description || 'No description' }}
                </el-descriptions-item>
            </el-descriptions>
        </el-config-provider>
    </Modal>
</template>
