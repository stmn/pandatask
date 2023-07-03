<script setup>
import {Link, usePage, router} from '@inertiajs/vue3'
import {computed, inject, provide, ref} from "vue";
import {useDateFormat, useStorage, useTimeAgo} from "@vueuse/core";
import Time from "@/Components/Time.vue";
import Timer from "@/Components/Timer.vue";
import User from "@/Components/User.vue";
import Pagination from "@/Components/Pagination.vue";
import {Comment} from "@element-plus/icons-vue";
import Activity from "@/Components/Activity.vue";

const props = defineProps({
    tasks: {
        required: true
    },
    showProject: {
        type: Boolean,
        required: false,
        default: false
    },
    onlyAvatar: {
        type: Boolean,
        required: false,
        default: false
    },
    cols: {
        type: Array,
        required: false,
        default: ['timer', 'task_id', 'start_at', 'end_at', 'comment', 'author_id']
    }
})

const tasksRows = computed(() => props.tasks?.data || props.tasks)

// const showProject = computed(() => tasksRows.value?.[0]?.project)

const handleTasks = inject('handleTasks');

const updateTask = ({task, data}) => {
    console.log(
        'updateTask', usePage().props.projectsOrder
    )
    router.post(
        route('project.task', {task: task.number, project: task.project_id}),
        {task: data},
        {preserveScroll: true, preserveState: true, headers: {'projects-order': usePage().props.projectsOrder},
            only: ['tasks', 'flash', 'errors'],
            onSuccess: (response) => {
                // console.log('onSuccess', response)
                handleTasks(response);
                // tasksRows.value = response.props.tasks.data
            },
        },
    )
}
</script>

<template>

    {{ JSON.stringify(usePage().props.errors) }}
    <el-table :data="tasksRows" stripe style="width: 100%">
        <template #empty>
            <div v-if="tasks">No Data</div>
            <div v-else>
                <el-icon class="is-loading" size="32" style="margin-top: 22px;">
                    <Loading />
                </el-icon>
            </div>
        </template>
<!--        <el-table-column fixed prop="number" label="#" width="64">-->
<!--            <template #default="{row}">-->
<!--                <b>{{ row.number }}</b>-->
<!--            </template>-->
<!--        </el-table-column>-->
<!--        <el-table-column width="43">-->
<!--            <template #default="{row}">-->
<!--                <Timer :task="row"/>-->
<!--            </template>-->
<!--        </el-table-column>-->
        <el-table-column prop="subject" min-width="300">
            <template #default="{row}">
                <div style="display: flex; align-items: center;">
                    <Timer :task="row" style="margin-right: 5px;"/>

                    <span style="display: contents;">
                        <el-text truncated>
                        <Link :href="route('project.task', {project: row.project_id, task: row.number})">{{ row.subject }}</Link>
                        </el-text>
                        <span style="background: var(--el-bg-color); padding: 0 3px; margin-left: 5px; word-break: keep-all;">#{{ row.number }}</span>
                        <template v-if="row.comments_count">
                        <el-icon size="14" style="margin: 0 2px 0 5px;">
                            <Comment/>
                        </el-icon> <span>{{ row.comments_count }}</span>
                        </template>
                    </span>
                </div>
            </template>
        </el-table-column>
        <el-table-column v-if="showProject" prop="project_id" label="Project" min-width="140">
            <template #default="{row}">
                <div style="display: flex; align-items: center;">
                    <el-avatar
                        :size="24"
                        style="margin-right: 5px;"
                        :src="row.project.avatar"
                    />
                    <Link :href="route('project.tasks', {project: row.project_id})">{{ row.project.name }}</Link>
                </div>
            </template>
        </el-table-column>
        <el-table-column prop="status" label="Status" width="150" align="center">
            <template #default="{row}">
                <el-dropdown v-if="row.status" style="width: 100%;" size="default" trigger="click" @command="updateTask">
                    <el-tag v-if="row.status"
                            class="status-tag"
                            size="small"
                            :color="row.status.color"
                            :style="`border-color: ${row.status.color};`"
                            effect="dark">{{ row.status.name }}</el-tag>
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item v-for="status in usePage().props.statuses"
                                              :disabled="status.id===row.status_id"
                                              :command="{task: row, data: { status_id: status.id}}"
                                              v-text="status.name"></el-dropdown-item>
                        </el-dropdown-menu>
                    </template>
                </el-dropdown>
            </template>
        </el-table-column>
        <el-table-column prop="priority" label="Priority" width="120" align="center">
            <template #default="{row}">
                <el-dropdown v-if="row.priority" style="width: 100%;" size="default" trigger="click" @command="updateTask">
                    <el-tag v-if="row.priority"
                            class="priority-tag"
                            size="small"
                            :color="row.priority.color"
                            :style="`border-color: ${row.priority.color};`"
                            effect="plain">{{ row.priority.name }}</el-tag>
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item v-for="priority in usePage().props.priorities"
                                              :disabled="priority.id===row.priority_id"
                                              :command="{task: row, data: { priority_id: priority.id}}"
                                              v-text="priority.name"></el-dropdown-item>
                        </el-dropdown-menu>
                    </template>
                </el-dropdown>
            </template>
        </el-table-column>
        <el-table-column prop="latest_activity_created_at" label="Last activity" min-width="280">
            <template #default="{row}">
                <template v-if="row.latest_activity">
                    <div v-if="row.latest_activity" style="display: flex; align-items: center;">
                        <User :user="row.latest_activity.user"/> &nbsp;
                        <Activity :activity="row.latest_activity" :task="row" only-icon style="margin: 0 5px;"/>
                        <Time :time="row.latest_activity.created_at"/>
                    </div>
                </template>
                <template v-else>

                </template>
            </template>
        </el-table-column>
    </el-table>
    <br>

    <Pagination :data="tasks" :only="['tasks']"/>
</template>

<style lang="scss" scoped>
</style>
