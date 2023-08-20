<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import {computed, onMounted, ref} from "vue";
import Layout from "~/js/Layouts/Layout.vue";
import TimesheetsTable from "@/Components/Timesheets/TimesheetsTable.vue";
import ActivityList from "@/Components/Activity/ActivityList.vue";
import TaskForm from "@/Components/Task/TaskForm.vue";
import Editor from "@/Components/Forms/EditorInput.vue";
import {useStorage} from "@vueuse/core";
import TaskHeader from "~/js/Pages/Task/TaskHeader.vue";

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
    activities: {
        type: Array,
        required: true
    },
    times: {
        type: Array,
        required: true
    },
    users: {
        type: Array,
        required: true
    },
    priorities: {
        type: Array,
        required: true
    },
    statuses: {
        type: Array,
        required: true
    },
});

const uploadRef = ref(null);

const activityForm = useForm({
    comment: '',
    files: [],
    private: false,
});

const taskForm = useForm({
    subject: props.task.subject,
    description: props.task.description,
    assignees: props.task.assignees,
    private: props.task.private,
    status_id: props.task.status_id,
    priority_id: props.task.priority_id,
    tags: props.task.tags || [],
    start_date: props.task.start_date,
    end_date: props.task.end_date,
    milestone_id: props.task.milestone_id,
    custom_fields: props.task?.custom_fields || {},
});

const submit = () => {
    activityForm.transform((data) => ({
        task: taskForm.data(),
        activity: activityForm.data(),
    }))
        .post('', {
            preserveScroll: true,
            forceFormData: true,
            onSuccess: () => {
                activityForm.reset();
                document.querySelector('textarea')?.focus();
                activeTab2.value = 'activity'
            },
            onError: () => {
                taskForm.setError({
                    ...taskForm.errors,
                    ...activityForm.errors,
                });
            }
        })
};

const activeTab = ref('comment');
const activeTab2 = ref('activity');

onMounted(() => {
    document.querySelector('textarea')?.focus()
})

const canSubmit = computed(() => {
    return activityForm.comment || activityForm.files.length || taskForm.isDirty;
})

const onlyComments = useStorage('onlyComments', true);
</script>

<template>
    <Head :title="task.subject+ ' #'+task.number+' - '+project.name"/>

    <TaskHeader :task="task" :project="project"/>

    <!-- Form -->

    <el-form
        ref="ruleFormRef"
        label-width="120px"
        class="demo-ruleForm"
        status-icon
        label-position="top"
        @submit.prevent="submit"
    >
        <el-tabs v-model="activeTab">
            <!-- Comment -->

            <el-tab-pane name="comment">
                <template #label>
                    <el-badge :is-dot="!!activityForm.comment.length">
                        <i class="fa-solid fa-comment-dots mr-2"></i> Comment
                    </el-badge>
                </template>

                <editor v-model="activityForm.comment" placeholder="Write comment..."/>

                <div v-if="activityForm.errors.comment" class="el-form-item__error">
                    {{ activityForm.errors.comment }}
                </div>
            </el-tab-pane>

            <!-- Attachments -->

            <el-tab-pane name="attachments">
                <template #label>
                    <el-badge :is-dot="!!activityForm.files.length">
                        <i class="fa-solid fa-upload mr-2"></i> Attachments
                    </el-badge>
                </template>

                <el-upload
                    drag
                    multiple
                    ref="uploadRef"
                    v-model:file-list="activityForm.files"
                    :auto-upload="false"
                >
                    <i class="fas fa-cloud-arrow-up fa-2xl mb-6"></i>
                    <div class="el-upload__text">
                        Drop file here or click to upload
                    </div>
                </el-upload>
            </el-tab-pane>

            <!-- Settings -->

            <el-tab-pane name="settings">
                <template #label>
                    <el-badge :is-dot="taskForm.isDirty">
                        <i class="fa-solid fa-sliders mr-2"></i> Details
                    </el-badge>
                </template>
                <el-config-provider size="default">
                    <TaskForm v-model="taskForm"
                              :statuses="statuses"
                              :priorities="priorities"
                              :users="users"/>
                </el-config-provider>
            </el-tab-pane>
        </el-tabs>

        <el-form-item mt-3>
            <div flex items-center>
                <el-button type="success" @click="submit"
                           :loading="activityForm.processing"
                           :disabled="activityForm.processing || !canSubmit">
                    <i class="fa-solid fa-check mr-2"></i>Submit
                </el-button>

                <el-switch
                    ml-5
                    v-model="activityForm.private"
                    size="small"
                    active-text="Private"
                />

                <el-tooltip content="If enabled only your team members will be able to see this activity."
                            placement="left">
                    <i class="fa-solid fa-question-circle ml-2"></i>
                </el-tooltip>
            </div>
        </el-form-item>
    </el-form>

    <!-- Bottom tabs -->

    <el-tabs v-model="activeTab2">
        <!-- Activity -->

        <el-tab-pane name="activity">
            <template #label>
                <i class="fa-solid fa-eye mr-2"></i> Activity
            </template>

            <el-switch v-model="onlyComments"
                       active-text="Only comments" size="small" mb-5/>

            <ActivityList :activities="activities" :only-comments="onlyComments"/>
        </el-tab-pane>

        <!-- Timesheet -->

        <el-tab-pane name="timesheet">
            <template #label>
                <i class="fa-solid fa-clock mr-2"></i> Timesheets
            </template>

            <TimesheetsTable :times="times"/>
        </el-tab-pane>
    </el-tabs>

    <!-- Backtop -->

    <el-backtop :right="100" :bottom="100"/>
</template>
