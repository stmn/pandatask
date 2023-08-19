<script setup lang="jsx">
import {Link, router, usePage} from '@inertiajs/vue3'
import {computed, inject, ref, Transition} from "vue";
import Time from "~/js/Components/Common/TimeValue.vue";
import Timer from "~/js/Components/Task/TimerButton.vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import Activity from "~/js/Components/Activity/ActivityType.vue";
import TaskLink from "~/js/Components/Task/TaskLink.vue";
import 'vue3-easy-data-table/dist/style.css';
import {useList} from "@/Composables/useList.js";
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";
import UserName from "@/Components/User/UserName.vue";
import UserAvatar from "@/Components/User/UserAvatar.vue";
import UserPopover from "@/Components/User/UserPopover.vue";

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
    listColumns: {
        type: Array,
        required: false,
        default: () => ['status_id', 'priority_id', 'latest_activity_at']
    },
    selectedColumns: {
        type: Array,
        required: false,
        default: () => ['status_id', 'priority_id', 'latest_activity_at']
    },
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

const columns = ref([
    {
        label: 'Status',
        value: 'status_id',
    },
    {
        label: 'Priority',
        value: 'priority_id',
    },
    {
        label: 'Latest activity',
        value: 'latest_activity_at',
    }
]);

usePage().props.project?.custom_fields?.forEach((field) => {
    columns.value.push({
        label: field.label,
        value: 'custom_fields->' + field.key,
    });
});

const {list, updateColumns} = useList();

// selectedColumns = ['status_id'];//props.selectedColumns;
updateColumns(columns.value);
</script>

<template>
    <!--        {{ JSON.stringify(selectedColumns) }}-->

    <div v-if="1"
         class="el-table--fit el-table--striped el-table--enable-row-hover el-table--enable-row-transition el-table el-table--layout-fixed is-scrolling-none"
         data-prefix="el" style="width: 100%;">
        <div class="el-table__inner-wrapper">
            <el-scrollbar>
                <table class="el-table__body" cellspacing="0" cellpadding="0" border="0"
                       style="table-layout: fixed; width: 100%;">
                    <colgroup>
                        <col class="col-subject">
                        <col v-if="selectedColumns.includes('project_id')" class="col-project">
                        <col v-if="selectedColumns.includes('status_id')" class="col-status">
                        <col v-if="selectedColumns.includes('priority_id')" class="col-priority">
                        <template v-for="field in $page.props.project?.custom_fields">
                            <col v-if="selectedColumns.includes('custom_fields->'+field.key)"
                                 :class="`col-custom col-custom__${field?.type}`">
                        </template>
                        <col v-if="selectedColumns.includes('latest_activity_at')" class="col-activity">
                    </colgroup>
                    <thead>
                    <tr>
                        <th class="subject-col is-leaf el-table__cell"
                            colspan="1" rowspan="1">
                            <div class="cell"></div>
                        </th>
                        <th v-if="selectedColumns.includes('project_id')"
                            class="el-table_1_column_9 is-center is-leaf el-table__cell"
                            colspan="1" rowspan="1">
                            <div class="cell">Project</div>
                        </th>
                        <th v-if="selectedColumns.includes('status_id')"
                            class="el-table_1_column_9 is-center is-leaf el-table__cell"
                            colspan="1" rowspan="1">
                            <div class="cell">Status</div>
                        </th>
                        <th v-if="selectedColumns.includes('priority_id')"
                            class="el-table_1_column_4 is-center is-leaf el-table__cell" colspan="1" rowspan="1">
                            <div class="cell">Priority</div>
                        </th>
                        <template v-for="field in $page.props.project?.custom_fields">
                            <th v-if="selectedColumns.includes('custom_fields->'+field.key)"
                                class="el-table_1_column_4 is-center is-leaf el-table__cell" colspan="1" rowspan="1">
                                {{ field.label }}
                            </th>
                        </template>
                        <th class="el-table_1_column_3 is-leaf el-table__cell"
                            v-if="selectedColumns.includes('latest_activity_at')"
                            colspan="1" rowspan="1">
                            <div class="cell">Latest activity</div>
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="el-table__row" v-for="row in tasksRows">
                        <td class="el-table_1_column_1 subject-col el-table__cell" rowspan="1" colspan="1">
                            <div class="cell">
                                <!--                                <lazy-component>-->
                                <!--                                    <template #placeholder>-->
                                <!--                                        <el-skeleton :rows="0"/>-->
                                <!--                                    </template>-->

                                <!--                                <Transition appear>-->
                                <div class="task-link" style="display: flex; align-items: center;">
                                    <Timer :task="row" style="margin-right: 10px;"/>
                                    <TaskLink :task="row" style="display: contents;"/>
                                </div>
                                <!--                                </Transition>-->
                                <!--                                </lazy-component>-->
                            </div>
                        </td>

                        <td v-if="selectedColumns.includes('project_id')"
                            class="el-table_1_column_9 is-center el-table__cell" rowspan="1" colspan="1">
                            <div flex items-center>
                                <BaseAvatar :avatar="row.project.avatar"
                                            :name="row.project.name"
                                            :size="24" mr-1/>
                                <Link :href="$route('project.tasks', {project: row.project_id})">
                                    {{ row.project.name }}
                                </Link>
                            </div>
                        </td>

                        <td v-if="selectedColumns.includes('status_id')"
                            class="el-table_1_column_9 is-center el-table__cell" rowspan="1" colspan="1">
                            <div class="cell">
                                <lazy-component>
                                    <template #placeholder>
                                        <div v-if="row.status"
                                             class="status-tag"
                                             :style="`border-color: ${row.status.color};`">
                                            {{ row.status.name }}
                                        </div>
                                    </template>

                                    <!--                    <Transition appear>-->
                                    <el-dropdown v-if="row.status" style="width: 100%; max-width: 150px;" size="default" trigger="click"
                                                 @command="updateTask">
                                        <div v-if="row.status"
                                             class="status-tag"
                                             :style="`border-color: ${row.status.color};`">
                                            {{ row.status.name }}
                                        </div>
                                        <template #dropdown>
                                            <el-dropdown-menu>
                                                <el-dropdown-item v-for="status in statuses(row)"
                                                                  :disabled="status.id===row.status_id"
                                                                  :command="{task: row, data: { status_id: status.id}}"
                                                                  v-text="status.name"></el-dropdown-item>
                                            </el-dropdown-menu>
                                        </template>
                                    </el-dropdown>
                                    <!--                    </Transition>-->
                                </lazy-component>
                            </div>
                        </td>

                        <td v-if="selectedColumns.includes('priority_id')"
                            class="el-table_1_column_4 is-center el-table__cell"
                            rowspan="1" colspan="1">
                            <div class="cell">
                                <lazy-component>
                                    <template #placeholder>
                                        <div v-if="row.priority"
                                             class="priority-tag"
                                             :style="`border-color: ${row.priority.color};`">{{ row.priority.name }}
                                        </div>
                                    </template>

                                    <!--                    <Transition appear>-->
                                    <el-dropdown v-if="row.priority" style="width: 100%; max-width: 150px;" size="default" trigger="click"
                                                 @command="updateTask">
                                        <div v-if="row.priority"
                                             class="priority-tag"
                                             :style="`border-color: ${row.priority.color};`">{{ row.priority.name }}
                                        </div>
                                        <template #dropdown>
                                            <el-dropdown-menu>
                                                <el-dropdown-item v-for="priority in priorities(row)"
                                                                  :disabled="priority.id===row.priority_id"
                                                                  :command="{task: row, data: { priority_id: priority.id}}"
                                                                  v-text="priority.name"></el-dropdown-item>
                                            </el-dropdown-menu>
                                        </template>
                                    </el-dropdown>
                                    <!--                    </Transition>-->
                                </lazy-component>
                            </div>
                        </td>

                        <template v-for="field in $page.props.project?.custom_fields">
                            <td class="el-table_1_column_3 el-table__cell"
                                v-if="selectedColumns.includes('custom_fields->'+field.key)">
                                <div text-center>
                                    <template v-if="field.type === 'boolean'">
                                        <i class="fa-solid fa-check" v-if="row.custom_fields?.[field.key]"></i>
                                        <i class="fa-solid fa-times" v-else opacity-25></i>
                                    </template>
                                    <template v-else>
                                        <div style="word-break: keep-all;">{{ row.custom_fields?.[field.key] }}</div>
                                    </template>
                                </div>
                            </td>
                        </template>

                        <td class="el-table_1_column_3 el-table__cell"
                            v-if="selectedColumns.includes('latest_activity_at')"
                            rowspan="1" colspan="1">
                            <div class="cell">
                                <!--                                <lazy-component>-->
                                <!--                                    <template #placeholder>-->
                                <!--                                        <el-skeleton :rows="0"/>-->
                                <!--                                    </template>-->

                                <!--                    <Transition appear>-->
                                <template v-if="row.latest_activity">
                                    <div v-if="row.latest_activity" style="display: flex; align-items: center;">
                                        <UserPopover :user="row.latest_activity.user">
                                            <div flex items-center>
                                                <UserAvatar :user="row.latest_activity.user"/>
                                                <UserName :user="row.latest_activity.user" class="mx-1"/>
                                            </div>
                                        </UserPopover>
                                        <Activity :activity="row.latest_activity" :task="row" only-icon
                                                  style="margin: 0 5px; color: var(--el-color-primary-dark-2);"/>
                                        <Time :show-clock="false" :time="row.latest_activity.created_at"/>
                                    </div>
                                </template>
                                <!--                    </Transition>-->
                                <!--                                </lazy-component>-->
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </el-scrollbar>
        </div>
    </div>

    <el-table :data="tasksRows" v-if="0"
              stripe
              style="width: 100%;"
              key="ab"
              row-key="id"
              flexible
              table-layout="fixed"

              :row-class-name="tableRowClassName">
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
        <el-table-column v-if="showProject"
                         prop="project_id"
                         label="Project"
                         min-width="140">
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
        <el-table-column v-if="columns.includes('status')"
                         column-key="status"
                         prop="status"
                         label="Status"
                         width="150"
                         align="center">
            <template #default="{row}">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="0"/>
                    </template>

                    <Transition appear>
                        <el-dropdown v-if="row.status" style="width: 100%;" size="default" trigger="click"
                                     @command="updateTask">
                            <div v-if="row.status"
                                 class="status-tag"
                                 :style="`border-color: ${row.status.color};`">
                                {{ row.status.name }}
                            </div>
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
        <el-table-column v-if="columns.includes('priority')"
                         column-key="priority"
                         prop="priority"
                         label="Priority"
                         width="120"
                         align="center">
            <template #default="{row}">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="0"/>
                    </template>

                    <Transition appear>
                        <el-dropdown v-if="row.priority" style="width: 100%;" size="default" trigger="click"
                                     @command="updateTask">
                            <div v-if="row.priority"
                                 class="priority-tag"
                                 :style="`border-color: ${row.priority.color};`">{{ row.priority.name }}
                            </div>
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
        <el-table-column
            column-key="latest_activity_created_at"
            prop="latest_activity_created_at"
            :resizable="false"
            label="Last activity"
            min-width="280">
            <template #default="{row}">
                <lazy-component>
                    <template #placeholder>
                        <el-skeleton :rows="0"/>
                    </template>

                    <Transition appear>
                        <template v-if="row.latest_activity">
                            <div v-if="row.latest_activity" style="display: flex; align-items: center;">
                                <UserPopover :user="row.latest_activity.user"/> &nbsp;
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


.col-subject {
    width: max(300px, min(400px, calc(100vw - 680px)));
}

.col-project {
    width: 120px;
}

.col-status {
    width: 150px;
}

.col-priority {
    width: 120px;
}

.col-custom {
    width: 82px;
}

.col-activity {
    width: 280px;
}
</style>
