<script setup>
import {router} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import Timer from "@/Components/Task/TimerButton.vue";
import Time from "@/Components/Common/TimeValue.vue";
import EditorContent from "@/Components/Forms/EditorContent.vue";
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";
import UserPopover from "@/Components/User/UserPopover.vue";
import TaskNumber from "@/Components/Task/TaskNumber.vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    project: {
        type: Object,
        required: true
    },
    task: {
        type: Object,
        required: true
    },
});
</script>

<template>
    <el-page-header @back="() => router.visit($route('project.tasks', {project: props.project.id}))">
        <template #content>
            <div flex items-center>
                <Timer :task="task"/>

                <BaseAvatar
                    :size="26"
                    style="margin: 0 10px;"
                    :name="project.name"
                    :avatar="project.avatar"/>

                <div style="margin-right: 5px; line-height: 18px; font-size: 16px;">
                    {{ task.subject }}
                    <TaskNumber :number="task.number" />
                </div>

                <el-tag v-if="task.private">Private</el-tag>
            </div>
        </template>
    </el-page-header>

    <el-form
        mt-5
        ref="ruleFormRef"
        label-width="90px"
        label-position="left"
        label-suffix=":"
    >
        <el-form-item label="Created by">
            <div style="display: flex; align-items: center;">
                <UserPopover :user="task.author"/> &nbsp;
                <Time :time="task.created_at"/>
            </div>
        </el-form-item>

        <el-form-item label="Description" prop="desc" v-if="task.description">
            <el-card shadow="never">
                <EditorContent :content="task.description"/>
            </el-card>
        </el-form-item>
    </el-form>
</template>
