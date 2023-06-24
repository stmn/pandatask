<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {computed, ref} from "vue";
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

const tasksRows = computed(() => props.tasks.data || props.tasks)

const showProject = ref(tasksRows.value?.[0]?.project)

const page = usePage()

const auth = computed(() => page.props.auth)
</script>

<template>
    <el-table :data="tasksRows" stripe style="width: 100%">
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
                    <Link :href="route('project.task', {project: row.project_id, task: row.number})">{{ row.subject }}</Link>
                    <span style="background: var(--el-bg-color); padding: 0 3px; margin-left: 5px;">#{{ row.number }}</span>
                    <template v-if="row.comments_count">
                    <el-icon size="14" style="margin: 0 2px 0 5px;">
                        <Comment/>
                    </el-icon> <span>{{ row.comments_count }}</span>
                    </template>
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
        <el-table-column prop="status" label="Status" width="120">
            <template #default="{row}">
                <el-tag size="small">In progress</el-tag>
            </template>
        </el-table-column>
        <el-table-column prop="priority" label="Priority" width="120">
            <template #default="{row}">
                <el-tag size="small">Normal</el-tag>
            </template>
        </el-table-column>
        <el-table-column prop="latest_activity" label="Last activity" min-width="260">
            <template #default="{row}">
                <template v-if="row.latest_activity">
                    <div style="display: flex; align-items: center;">
                        <User :user="row.latest_activity.user"/> &nbsp;
                        <Activity :activity="row.latest_activity" :task="row" only-icon style="margin: 0 5px;"/>
                        <Time :time="row.latest_activity.created_at"/>
                    </div>
                </template>
            </template>
        </el-table-column>
    </el-table>
    <br>
    <Pagination v-if="tasks.data" :data="tasks"/>
</template>

<style lang="scss" scoped>
</style>
