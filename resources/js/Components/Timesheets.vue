<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {computed} from "vue";
import Time from "@/Components/Time.vue";
import Timer from "@/Components/Timer.vue";
import User from "@/Components/User.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    task: {
        type: Object,
        required: false
    },
    times: {
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
        default: ['timer', 'task_id', 'start_at', 'end_at', 'time', 'comment', 'author_id', 'actions']
    }
})

const page = usePage()

const auth = computed(() => page.props.auth)

const timeBetweenTwoDates = (date1, date2) => {
    const seconds = Math.floor((Date.parse(date2) - Date.parse(date1)) / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);

    let parts = [];
    if (hours) {
        parts.push(`${hours.toString().padStart(1, '0')}<span style="opacity: 0.5;">h</span>`)
    }
    if ((minutes % 60)) {
        parts.push(`${(minutes % 60).toString().padStart(2, '0')}<span style="opacity: 0.5;">m</span>`)
    }
    parts.push(`${(seconds % 60).toString().padStart(2, '0')}<span style="opacity: 0.5;">s</span>`)

    return parts.join(' ');
}
</script>

<template>
    <el-table :data="times?.data || times" stripe style="width: 100%">
        <template #empty>
            <div v-if="times">No Data</div>
            <div v-else>
                <i class="fa-solid fa-circle-notch fa-spin fa-xl"></i>
            </div>
        </template>

        <el-table-column v-if="cols.includes('task_id') && !task" prop="task_id" min-width="200">
            <template #default="{row}">
                <div style="display: flex; align-items: center;">
                    <Timer :task="row.task" style="margin-right: 5px;"/>
                    <el-text truncated>
                        <Link :href="row.task.url">{{ row.task.subject }}</Link>
                    </el-text>
                </div>
            </template>
        </el-table-column>
        <el-table-column v-if="cols.includes('author_id')" prop="author_id" label="User" min-width="140">
            <template #default="{row}">
                <User :user="row.author" :only-avatar="onlyAvatar"/>
            </template>
        </el-table-column>
        <el-table-column v-if="cols.includes('start_at')" prop="start_at" label="Start" width="150">
            <template #default="{row}">
                <Time :show-clock="false" :time="row.start_at" force-type="date"/>
            </template>
        </el-table-column>
        <el-table-column v-if="cols.includes('end_at')" prop="end_at" label="End" width="150">
            <template #default="{row}">
                <Time :show-clock="false" v-if="row.end_at" :time="row.end_at" force-type="date"/>
                <span v-else>
                    <b>Pending</b>
                </span>
            </template>
        </el-table-column>
        <el-table-column v-if="cols.includes('time')" prop="time" label="Time" width="105">
            <template #default="{row}">
                <template v-if="row.end_at">
                    <div style="width: 90px; text-align: right;"
                         v-html="timeBetweenTwoDates(row.start_at, row.end_at)">
                    </div>
                </template>
            </template>
        </el-table-column>
        <el-table-column v-if="cols.includes('comment')" prop="comment" label="Comment" min-width="60">
            <template #default="{row}">
                <el-popover
                    v-if="row.comment"
                    placement="left"
                    :width="200"
                    trigger="click"
                >
                    <template #reference>
                        <el-button circle>
                            <i class="fa-solid fa-comment-dots"></i>
                        </el-button>
                    </template>
                    <template #default>
                        <small>{{ row.comment }}</small>
                    </template>
                </el-popover>
            </template>
        </el-table-column>
        <el-table-column v-if="cols.includes('actions')" fixed="right" prop="actions" label="Actions" width="90">
            <template #default="{row}">
                <Link preserve-state preserve-scroll :only="['modal', 'times']"
                      :href="$route('project.timesheets.edit', {project: row.project_id, time: row.id})">
                    <el-button :color="$primaryColor()" circle><i class="fa-solid fa-pen-to-square"></i></el-button>
                </Link>
            </template>
        </el-table-column>
    </el-table>

    <br>

    <Pagination v-if="times?.data" :data="times"/>
</template>
