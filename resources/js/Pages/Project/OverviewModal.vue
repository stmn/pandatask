<script setup>
import {Head} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import {useDateFormat} from "@vueuse/core";
import Modal from "~/js/Layouts/Modal.vue";
import UserAvatar from "@/Components/User/UserAvatar.vue";
import UserPopover from "@/Components/User/UserPopover.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    project: {
        type: Object,
    },
});
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
                        <UserPopover :user="client"
                                     v-for="client in project.clients"
                                     style="display: inline; margin: 10px;">
                            <div>
                                <UserAvatar :user="client" tooltip/>
                            </div>
                        </UserPopover>
                    </div>
                </el-descriptions-item>
                <el-descriptions-item label="Created at">
                    {{ useDateFormat(project.created_at, 'DD-mm-YYYY').value }}
                </el-descriptions-item>
                <el-descriptions-item label="Team members">
                    <div style="display: flex; padding: 5px;">
                        <UserPopover :user="member"
                                     v-for="member in project.team_members"
                                     style="display: inline; margin: 10px;">
                            <div>
                                <UserAvatar :user="member" tooltip/>
                            </div>
                        </UserPopover>
                    </div>
                </el-descriptions-item>
                <el-descriptions-item label="Description">
                    {{ project.description || 'No description' }}
                </el-descriptions-item>
            </el-descriptions>
        </el-config-provider>
    </Modal>
</template>
