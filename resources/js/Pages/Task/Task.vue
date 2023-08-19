<script setup>
import {Head, router, useForm} from '@inertiajs/vue3';
import {computed, onMounted, ref} from "vue";
import Layout from "~/js/Layouts/Layout.vue";
import Timer from "@/Components/Task/TimerButton.vue";
import Time from "@/Components/Common/TimeValue.vue";
import Timesheets from "@/Components/Timesheets/TimesheetsTable.vue";
import Activities from "@/Components/Activity/ActivityList.vue";
import TaskForm from "@/Components/Task/TaskForm.vue";
import Editor from "@/Components/Forms/EditorInput.vue";
import {useStorage} from "@vueuse/core";
import EditorContent from "@/Components/Forms/EditorContent.vue";
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";
import UserPopover from "@/Components/User/UserPopover.vue";

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

    <el-page-header @back="() => router.visit($route('project.tasks', {project: props.project.id}))">
        <!--        <template #breadcrumb>-->
        <!--            <el-breadcrumb separator="/">-->
        <!--                <el-breadcrumb-item :to="{ path: './page-header.html' }">-->
        <!--                    homepage-->
        <!--                </el-breadcrumb-item>-->
        <!--                <el-breadcrumb-item-->
        <!--                ><a href="./page-header.html">route 1</a></el-breadcrumb-item-->
        <!--                >-->
        <!--                <el-breadcrumb-item>route 2</el-breadcrumb-item>-->
        <!--            </el-breadcrumb>-->
        <!--        </template>-->
        <template #content>
            <div style="display: flex; align-items: center;">
                <Timer :task="task"/>

                <BaseAvatar
                    :size="26"
                    style="margin: 0 10px;"
                    :name="project.name"
                    :avatar="project.avatar"/>
                <span style="margin-right: 5px;">{{ task.subject }}<span class="task-number">#{{
                        task.number
                    }}</span></span>
                &nbsp;<el-tag v-if="task.private">Private</el-tag>
            </div>
        </template>
    </el-page-header>

    <el-container>
        <el-container>
            <el-main>
                <el-form
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
                    <!--                    {{ JSON.stringify(task.description)}}-->
                    <el-form-item label="Description" prop="desc" v-if="task.description">
                        <el-card shadow="never">
                            <EditorContent :content="task.description"/>
                        </el-card>
                    </el-form-item>
                </el-form>
                <el-form
                    ref="ruleFormRef"
                    label-width="120px"
                    class="demo-ruleForm"
                    status-icon
                    label-position="top"
                    @submit.prevent="submit"
                >
                    <!--                    <div style="display: flex; align-items: center; font-size: 14px;">-->
                    <!--                        <span>Created by</span>-->
                    <!--                        <span style="margin: 0 10px;"></span>-->
                    <!--                        <Time :time="task.created_at"/>-->
                    <!--                    </div>-->
                    <!--                    <br>-->


                    <!--                    <el-form-item label="Description" prop="desc" v-if="task.description">-->
                    <!--                        <el-card class="ProseMirror">-->
                    <!--                            <div v-html="task.description"></div>-->
                    <!--                        </el-card>-->
                    <!--                    </el-form-item>-->

                    <!--                    {{ JSON.stringify(task) }}-->

                    <el-tabs v-model="activeTab">
                        <el-tab-pane name="comment">
                            <template #label>
                                <el-badge :is-dot="!!activityForm.comment.length">
                                    <i class="fa-solid fa-comment-dots mr-2"></i>
                                    Comment
                                </el-badge>
                            </template>

                            <!--                            <editor-content v-model="activityForm.comment"-->
                            <!--                                            :editor="editor"-->
                            <!--                                            style="height: 100px; width: 100%;"-->
                            <!--                                            class="editor-content" />-->

                            <editor v-model="activityForm.comment" placeholder="Write comment..."/>
                            <br>
                            <!--                            <el-form-item>-->
                            <!--                                <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 12 }" v-model="activityForm.comment"-->
                            <!--                                          placeholder="Add comment..."/>-->


                            <!--                                <div v-if="activityForm.errors.comment" class="el-form-item__error"-->
                            <!--                                     style="padding: 5px 0; position: relative;">-->
                            <!--                                    {{ activityForm.errors.comment }}-->
                            <!--                                </div>-->
                            <!--                            </el-form-item>-->

                            <div v-if="activityForm.errors.comment" class="el-form-item__error"
                                 style="padding: 5px 0; position: relative;">
                                {{ activityForm.errors.comment }}
                            </div>
                        </el-tab-pane>
                        <el-tab-pane name="attachments">
                            <template #label>
                                <el-badge :is-dot="!!activityForm.files.length">
                                    <i class="fa-solid fa-upload mr-2"></i>
                                    Attachments
                                </el-badge>
                            </template>

                            <el-upload
                                drag
                                multiple
                                ref="uploadRef"
                                v-model:file-list="activityForm.files"
                                :auto-upload="false"
                            >
                                <i class="el-icon--upload fa-solid fa-cloud-arrow-up"></i>
                                <div class="el-upload__text">
                                    Drop file here or <em>click to upload</em>
                                </div>
                                <!--                                <template #tip>-->
                                <!--                                    <div class="el-upload__tip">-->
                                <!--                                        jpg/png files with a size less than 500kb-->
                                <!--                                    </div>-->
                                <!--                                </template>-->
                            </el-upload>
                        </el-tab-pane>
                        <el-tab-pane name="settings">
                            <template #label>
                                <el-badge :is-dot="taskForm.isDirty">
                                    <i class="fa-solid fa-sliders mr-2"></i>
                                    Details
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
                    <el-switch
                        v-model="activityForm.private"
                        size="small"
                        style="margin-top: 5px;"
                        active-text="Is private"
                    />
                    <br><br>
                    <!--                    {{ JSON.stringify(activityForm.errors) }}-->
                    <el-form-item>
                        <el-button type="success" @click="submit"
                                   :loading="activityForm.processing"
                                   :disabled="activityForm.processing || !canSubmit">
                            <i class="fa-solid fa-check mr-2"></i>Submit
                        </el-button>
                    </el-form-item>
                </el-form>

                <el-tabs v-model="activeTab2" class="demo-tabs">
                    <el-tab-pane name="activity">
                        <template #label>
                            <i class="fa-solid fa-eye mr-2"></i>
                            Activity
                        </template>

                        <el-switch v-model="onlyComments"
                                   active-text="Only comments" size="small" style="margin-bottom: 15px;"/>

                        <Activities :only-comments="onlyComments" :activities="activities"/>
                    </el-tab-pane>
                    <el-tab-pane name="timesheet">
                        <template #label>
                            <i class="fa-solid fa-clock mr-2"></i>
                            Timesheets
                        </template>

                        <Timesheets :times="times" :task="task"/>
                    </el-tab-pane>
                </el-tabs>
                <!--                <div ref="el">-->

                <!--                </div>-->

                <el-backtop :right="100" :bottom="100"/>
            </el-main>
        </el-container>
        <!--        <el-aside width="300px" class="" style="">-->
        <!--            <div style="position: sticky;  top: 0;">-->
        <!--                <div>-->
        <!--                    <el-divider content-position="left">Details</el-divider>-->
        <!--                    <el-descriptions column="1" border>-->
        <!--                        <el-descriptions-item label="Project">-->
        <!--                            <Link :href="$route('project.tasks', {project: task.project_id})">{{-->
        <!--                                    task.project.name-->
        <!--                                }}-->
        <!--                            </Link>-->
        <!--                        </el-descriptions-item>-->
        <!--                        <el-descriptions-item label="Status">-->
        <!--                            <el-tag size="small">School</el-tag>-->
        <!--                        </el-descriptions-item>-->
        <!--                        <el-descriptions-item label="Priority">-->
        <!--                            <el-tag size="small">School</el-tag>-->
        <!--                        </el-descriptions-item>-->
        <!--                        <el-descriptions-item label="Assignees">-->

        <!--                        </el-descriptions-item>-->
        <!--                    </el-descriptions>-->
        <!--                </div>-->
        <!--            </div>-->
        <!--        </el-aside>-->
    </el-container>
</template>
