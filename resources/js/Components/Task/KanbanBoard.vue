<script setup>
import {Link, router, usePage} from '@inertiajs/vue3'
import {computed, inject, ref} from "vue";
import {Drag, Drop} from "vue-easy-dnd";
import TaskLink from "@/Components/Task/TaskLink.vue";
import UserAvatar from "@/Components/User/UserAvatar.vue";


const props = defineProps({
    project: {},
    tasks: {},
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

const loading = ref(false);

const insertInto = (status, event) => {
    if (event.data.status_id == status) {
        return;
    }

    loading.value = true;
    try {
        console.log('insertInto', status, event);
        props.tasks[status].data.splice(event.index, 0, event.data);

        router.post(
            route('project.task', {task: event.data.number, project: event.data.project_id}),
            {task: {status_id: status}},
            {
                preserveScroll: true, preserveState: true,
                only: ['tasks', 'flash', 'errors'],
                onSuccess: (response) => {
                    refresh(status);
                    refresh(event.data.status_id);
                },
                onFinish: () => {
                    loading.value = false;
                }
            },
        )
    } catch (e) {
        loading.value = false;
        console.error(e);
    }
};

const remove = (array, value) => {
    let index = array.indexOf(value);
    array.splice(index, 1);
};

const refresh = (status) => {
    const url = route('project.kanban.tasks', {project: usePage().props.project.id, status: status});
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': usePage().props.csrf_token
    };
    axios.get(url, {
        headers: headers,
        params: {
            page: 1
        }
    }).then(response => {
        // console.log(response);
        props.tasks[status].data = response.data.data;
        props.tasks[status].current_page = response.data.current_page;
        props.tasks[status].next_page_url = response.data.next_page_url;
    });
}

const loadMore = (statusId) => {
    const url = route('project.kanban.tasks', {project: usePage().props.project.id, status: statusId});
    const headers = {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': usePage().props.csrf_token
    };
    const page = props.tasks[statusId].current_page + 1;
    axios.get(url, {
        headers: headers,
        params: {
            page: page
        }
    }).then(response => {
        // console.log(response);
        props.tasks[statusId].data.push(...response.data.data);
        props.tasks[statusId].current_page = response.data.current_page;
        props.tasks[statusId].next_page_url = response.data.next_page_url;
    });
};

</script>

<template>
    <!--    {{ JSON.stringify(tasks[2])}}-->
    <!--    <el-scrollbar style="border: 1px solid #333; border-radius: 5px;" height="300">-->
    <div flex v-loading="loading">
        <div v-for="status in $page.props.statuses" style="min-width: 290px; width: 100%; max-width: 350px;">
            <div flex items-center px-1>
                <div :style="`background: ${status.color}; width: 16px; height: 16px; border-radius: 100%;`" mr-2
                     my-3></div>
                <div text-sm>
                    {{ status.name }}
                    <el-divider direction="vertical"></el-divider>
                    <span text-xs>
                        {{ tasks[status.id].data.length }} / {{ tasks[status.id].total }}
                    </span>
                </div>
<!--                <Link preserve-state preserve-scroll-->
<!--                      :href="$route('project.tasks.create', {project: project.id})"-->
<!--                      :only="['modal']" ml-auto mr-2>-->
<!--                    <el-button size="small" type="primary" circle>-->
<!--                        <i class="fa-solid fa-plus"></i>-->
<!--                    </el-button>-->
<!--                </Link>-->
            </div>
            <el-scrollbar height="400">
                <drop @drop="insertInto(status.id, $event)"
                      :key="status.id"
                      mode="copy"
                      class="drop-list"
                      style="padding: 5px;">
                    <!--                    <template v-slot:item="{item, index, reorder}">-->

                    <div v-if="!tasks[status.id].data.length">
                        <el-empty description="Empty" style="margin: 10px 0;" image-size="100"></el-empty>
                    </div>

                    <drag :key="item.subject"
                          v-else
                          v-for="(item, index) in tasks[status.id].data"
                          :data="item"
                          @cut="remove(tasks[status.id].data, item)">
                        <el-card shadow="never" mb-2>
                            <!--                                <div flex align-items>-->
                            <span style="opacity: 0.3; position: absolute; font-size: 11px; margin: -8px 0 0 -7px;">{{
                                    index + 1
                                }}</span>
                            <TaskLink :task="item" flex items-center/>
                            <!--                                </div>-->

                            <div mt-2 v-if="item.assignees.length">
                                <UserAvatar v-for="assignee in item.assignees"
                                            :key="assignee.id"
                                            :user="assignee"
                                            only-avatar
                                            :size="20"/>
                            </div>
                        </el-card>
                    </drag>
                    <!--                    </template>-->
                    <template v-slot:reordering-drag-image/>
                    <template v-slot:feedback="{data}">
                        <!--                        <div class="item feedback" :key="data" style="border: 2px dashed #999; margin-bottom: 15px; min-height: 60px;">-->
                        <!--                            {{ data.title }}-->
                        <!--                        </div>-->
                    </template>
                </drop>
            </el-scrollbar>

            <div v-if="tasks[status.id].next_page_url" text-center>
                <el-button type="primary" @click="loadMore(status.id)">Load more</el-button>
            </div>
        </div>
    </div>
    <!--    </el-scrollbar>-->
</template>

<style lang="scss" scoped>
.DragFeedback {
    display: none;
}

.drop-in {
    background: var(--el-fill-color-dark);
    border-radius: 5px;
}

.el-card {
    cursor: pointer;

    :deep(.el-card__body) {
        --el-card-padding: 10px;
    }

    &:hover {
        //--el-card-bg-color: hsl(0 50% calc(var(--el-card-bg-color) - 25%));
        //background: color-mix(in srgb, var(--el-card-bg-color) 60%, var(--el-bg-color) 40%);
        //border: 1px solid var(--el-border-color);
    }
}

.drop-list {
    height: 100%;
}

.el-scrollbar__view {
    height: 100%;
}

:deep(.task-comments) {
    margin-left: auto !important;
}

:deep(.el-loading-mask) {
    background: color-mix(in srgb, var(--el-bg-color) 80%, transparent)
}
</style>
