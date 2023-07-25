<script setup>
import {Link, router, usePage} from '@inertiajs/vue3'
import {computed, inject, Transition} from "vue";
import Time from "~/js/Components/Common/TimeValue.vue";
import Timer from "~/js/Components/Task/TimerButton.vue";
import User from "~/js/Components/User/UserAvatar.vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import Activity from "~/js/Components/Activity/ActivityType.vue";
import TaskLink from "~/js/Components/Task/TaskLink.vue";

const props = defineProps({
    tasks: {},
    statuses: {
        type: Array,
        required: false,
        default: () => []
    },
    priorities: {
        type: Array,
        required: false,
        default: () => []
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

const handleTasks = inject('handleTasks', () => {
});

const updateTask = ({task, data}) => {
    router.post(
        route('project.task', {task: task.number, project: task.project_id}),
        {task: data},
        {
            preserveScroll: true, preserveState: true, headers: {'projects-order': usePage().props.projectsOrder},
            only: ['tasks', 'flash', 'errors'],
            onSuccess: (response) => {
                handleTasks(response);
            },
        },
    )
}

const tableRowClassName = ({row, rowIndex}) => {
    if (usePage().props.auth.user.active_time?.task_id == row.id) {
        return 'timer-active'
    }
    return ''
}

const statuses = (row) => {
    const availableStatuses = row?.project?.statuses || usePage().props?.project?.statuses || [];

    return usePage().props.statuses.filter(
        item => availableStatuses.includes(item.id) || item.id === row.status_id
    )
}

const priorities = (row) => {
    const availablePriorities = row?.project?.priorities || usePage().props?.project?.priorities || [];

    return usePage().props.priorities.filter(
        item => availablePriorities.includes(item.id) || item.id === row.priority_id
    )
}
</script>

<template>
    <el-table v-if="1" :data="tasksRows" stripe style="width: 100%" :row-class-name="tableRowClassName">
        <template #empty>
            <div v-if="tasks">No Data</div>
            <div v-else>
                <i class="fa-solid fa-circle-notch fa-spin fa-xl"></i>
            </div>
        </template>
        <el-table-column prop="subject" min-width="300" class-name="subject-col">
            <template #default="{row}">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="0"/>
                    </template>

                    <Transition appear>
                        <div class="task-link" style="display: flex; align-items: center;">
                            <Timer :task="row" style="margin-right: 10px;"/>
                            <TaskLink :task="row" style="display: contents;"/>
                        </div>
                    </Transition>
                </lazy-component>
            </template>
        </el-table-column>
        <el-table-column v-if="showProject" prop="project_id" label="Project" min-width="140">
            <template #default="{row}">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="0"/>
                    </template>

                    <Transition appear>
                        <div style="display: flex; align-items: center;">
                            <el-avatar
                                :size="24"
                                style="margin-right: 5px;"
                                :src="row.project.avatar"
                            />
                            <Link :href="$route('project.tasks', {project: row.project_id})">{{
                                    row.project.name
                                }}
                            </Link>
                        </div>
                    </Transition>
                </lazy-component>
            </template>
        </el-table-column>
        <el-table-column prop="status" label="Status" width="150" align="center">
            <template #default="{row}">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="0"/>
                    </template>

                    <Transition appear>
                        <el-dropdown v-if="row.status" style="width: 100%;" size="default" trigger="click"
                                     @command="updateTask">
                            <el-tag v-if="row.status"
                                    class="status-tag"
                                    size="small"
                                    :color="row.status.color"
                                    :style="`border-color: ${row.status.color};`"
                                    effect="dark">{{ row.status.name }}
                            </el-tag>
                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item v-for="status in statuses(row)"
                                                      :disabled="status.id===row.status_id"
                                                      :command="{task: row, data: { status_id: status.id}}"
                                                      v-text="status.name"></el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </Transition>
                </lazy-component>
            </template>
        </el-table-column>
        <el-table-column prop="priority" label="Priority" width="120" align="center">
            <template #default="{row}">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="0"/>
                    </template>

                    <Transition appear>
                        <el-dropdown v-if="row.priority" style="width: 100%;" size="default" trigger="click"
                                     @command="updateTask">
                            <el-tag v-if="row.priority"
                                    class="priority-tag"
                                    size="small"
                                    :color="row.priority.color"
                                    :style="`border-color: ${row.priority.color};`"
                                    effect="plain">{{ row.priority.name }}
                            </el-tag>
                            <template #dropdown>
                                <el-dropdown-menu>
                                    <el-dropdown-item v-for="priority in priorities(row)"
                                                      :disabled="priority.id===row.priority_id"
                                                      :command="{task: row, data: { priority_id: priority.id}}"
                                                      v-text="priority.name"></el-dropdown-item>
                                </el-dropdown-menu>
                            </template>
                        </el-dropdown>
                    </Transition>
                </lazy-component>
            </template>
        </el-table-column>
        <el-table-column prop="latest_activity_created_at" label="Last activity" min-width="280">
            <template #default="{row}">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="0"/>
                    </template>

                    <Transition appear>
                        <template v-if="row.latest_activity">
                            <div v-if="row.latest_activity" style="display: flex; align-items: center;">
                                <User :user="row.latest_activity.user"/> &nbsp;
                                <Activity :activity="row.latest_activity" :task="row" only-icon
                                          style="margin: 0 5px; color: var(--el-color-primary-dark-2);"/>
                                <Time :show-clock="false" :time="row.latest_activity.created_at"/>
                            </div>
                        </template>
                    </Transition>
                </lazy-component>
            </template>
        </el-table-column>
    </el-table>
    <br>

    <Pagination :data="tasks" :only="['tasks']"/>
</template>

<style lang="scss">
.el-table__row {
    background: #000;

    .subject-col {
        .timer {
            opacity: 0;
            transition: all 0.3s ease-out;
            transform: translateX(-30px);
        }

        .task-link {
            transition: all 0.3s ease-out;
            transform: translateX(-30px);
        }

        &:hover {
            .timer {
                opacity: 1;
                transform: translateX(0px);
            }

            .task-link {
                transform: translateX(0px);
            }
        }
    }

    &.timer-active {
        .timer {
            opacity: 1;
            transform: translateX(0px);
        }

        .task-link {
            transform: translateX(0px);
        }
    }
}

.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
