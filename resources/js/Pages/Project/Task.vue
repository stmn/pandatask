<script setup>
import {Head, router, useForm, Link} from '@inertiajs/vue3';
import {computed, onMounted, ref, watch} from "vue";
import Layout from "@/Layouts/Layout.vue";
import {ElMessage} from "element-plus";
import {useStorage, useTimeAgo} from "@vueuse/core";
import {Check, EditPen, Files, List, Message, Operation, Setting, Upload, UploadFilled} from "@element-plus/icons-vue";
import Timer from "@/Components/Timer.vue";
import User from "@/Components/User.vue";
import Time from "@/Components/Time.vue";
import Timesheets from "@/Components/Timesheets.vue";
import Activities from "@/Components/Activities.vue";

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
});

// const activities = computed(() => props.activities);

const onBack = () => {
    router.visit(route('project.tasks', {project: props.project.id}))
};


const defaultComment = {
    comment: '',
    private: false,
};
const comment = useStorage('comment', defaultComment);
const commentForm = useForm(defaultComment);
commentForm.comment = comment.value.comment;
commentForm.private = comment.value.private;
watch(commentForm, (value) => {
    comment.value = value
}, {deep: true});
const settingsForm = useForm({})

const submit = () => {
    // router.post(route('project.task', {project: props.project, task: props.task}), {...commentForm, ...settingsForm})

    commentForm.post('', {
        preserveScroll: true,
        onSuccess: () => {
            commentForm.reset();
            settingsForm.reset();
            document.querySelector('textarea')?.focus();
            ElMessage({
                message: 'Form submitted successfully!',
                type: 'success',
            })
            activeTab2.value = 'activity'
        },
    })
};

const activeTab = ref('comment');
const activeTab2 = ref('activity');

onMounted(() => {
    document.querySelector('textarea').focus()
    // timeline.value.push(...props.timeline.slice(timeline.value.length, timeline.value.length + 1))
})
</script>

<template>
    <Head :title="task.subject+ ' #'+task.number+' - '+project.name"/>

    <el-page-header @back="onBack">
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
                <Timer :task="task" />

                <el-avatar
                    :size="28"
                    style="margin: 0 10px;"
                    :src="task.project.avatar"
                />
                <span style="margin-right: 5px;">{{ task.subject }} <b>#{{ task.number }}</b></span>
                &nbsp;<el-tag>Private</el-tag>
            </div>
        </template>
<!--        <template #extra>-->
<!--            <div class="flex items-center">-->
<!--                <el-button type="primary" class="ml-2">Edit</el-button>-->
<!--            </div>-->
<!--        </template>-->
    </el-page-header>

<!--    <br>-->
<!--    <el-card>-->
<!--        <el-row>-->
<!--            <el-col :span="6">-->
<!--                <el-statistic title="Type" :value="'Feature'"/>-->
<!--            </el-col>-->
<!--            <el-col :span="6">-->
<!--                <el-statistic title="Status" :value="'New'"/>-->
<!--            </el-col>-->
<!--            <el-col :span="6">-->
<!--                <el-statistic title="Priority" :value="'High'"/>-->
<!--            </el-col>-->
<!--            <el-col :span="6">-->
<!--                <el-statistic title="Assignees" :value="'Nobody'"/>-->
<!--            </el-col>-->
<!--        </el-row>-->
<!--    </el-card>-->


    <el-container>
        <el-container>
            <el-main>
                <el-form
                    ref="ruleFormRef"
                    label-width="120px"
                    class="demo-ruleForm"
                    status-icon
                    label-position="top"
                    @submit.prevent="submit"
                >
                    <div style="display: flex; align-items: center; font-size: 14px;">
                        <span>Created by</span>
                        <span style="margin: 0 10px;"><User :user="task.author" /></span>
                        <Time :time="task.created_at" />
                    </div>
                    <br>

                    <el-form-item label="Description" prop="desc">
                        {{ task.description }}
                    </el-form-item>

                    <el-tabs v-model="activeTab" class="demo-tabs" @tab-click="handleClick">
                        <el-tab-pane name="comment">
                            <template #label>
                                <el-badge :is-dot="commentForm.isDirty">
                                <el-icon>
                                    <EditPen/>
                                </el-icon> &nbsp;
                                Comment
                                </el-badge>
                            </template>
                            <el-form-item>
                                <el-input type="textarea" :autosize="{ minRows: 2, maxRows: 12 }" v-model="commentForm.comment"
                                          placeholder="Add comment..."/>
                                <div v-if="commentForm.errors.comment" class="el-form-item__error"
                                     style="padding: 5px 0; position: relative;">
                                    {{ commentForm.errors.comment }}
                                </div>
                            </el-form-item>
                        </el-tab-pane>
                        <el-tab-pane name="attachments">
                            <template #label>
                                <el-icon>
                                    <Upload />
                                </el-icon> &nbsp;
                                Attachments
                            </template>

                            <el-upload
                                class="upload-demo"
                                drag
                                action="https://run.mocky.io/v3/9d059bf9-4660-45f2-925d-ce80ad6c4d15"
                                multiple
                            >
                                <el-icon class="el-icon--upload"><upload-filled /></el-icon>
                                <div class="el-upload__text">
                                    Drop file here or <em>click to upload</em>
                                </div>
                                <template #tip>
                                    <div class="el-upload__tip">
                                        jpg/png files with a size less than 500kb
                                    </div>
                                </template>
                            </el-upload>
                        </el-tab-pane>
                        <el-tab-pane name="settings">
                            <template #label>
                                <el-badge :is-dot="settingsForm.isDirty">
                                <el-icon>
                                    <Operation/>
                                </el-icon> &nbsp;
                                Details
                                </el-badge>
                            </template>
                            test<br>
                            test<br>
                            test<br>
                        </el-tab-pane>
                    </el-tabs>
                    <el-switch
                        v-model="commentForm.private"
                        size="small"
                        active-text="Is private"
                    />
                <br><br>
                    <el-form-item>
                        <el-button type="success" @click="submit"
                                   :loading="commentForm.processing"
                                   :disabled="commentForm.processing || !commentForm.isDirty || settingsForm.isDirty">
                            <el-icon>
                                <Check/>
                            </el-icon> &nbsp;
                            Submit
                        </el-button>
                    </el-form-item>
                </el-form>

                <el-tabs v-model="activeTab2" class="demo-tabs">
                    <el-tab-pane name="activity">
                        <template #label>
                            <el-icon>
                                <Comment/>
                            </el-icon> &nbsp;
                            Activity
                        </template>

                        <Activities :activities="activities" />
                    </el-tab-pane>
                    <el-tab-pane name="timesheet">
                        <template #label>
                            <el-icon>
                                <Clock/>
                            </el-icon> &nbsp;
                            Timesheets
                        </template>

                        <Timesheets :times="times" :task="task" />
                    </el-tab-pane>
                </el-tabs>
                <!--                <div ref="el">-->

                <!--                </div>-->

                <el-backtop :right="100" :bottom="100"/>
            </el-main>
        </el-container>
        <el-aside width="300px" class="" style="">
            <div style="position: sticky;  top: 0;">
                <div>
<!--                    <span style="font-size: 14px; font-weight: 500;">-->
<!--                        <el-icon>-->
<!--                            <Operation/>-->
<!--                        </el-icon> &nbsp;-->
<!--                        Details-->
<!--                    </span>-->
<!--                    <hr>-->

                    <el-divider content-position="left">Details</el-divider>

                    <el-descriptions column="1" border>
                        <el-descriptions-item label="Project">
                            <Link :href="route('project.tasks', {project: task.project_id})">{{ task.project.name }}</Link>
                        </el-descriptions-item>
                        <el-descriptions-item label="Status">
                            <el-tag size="small">School</el-tag>
                        </el-descriptions-item>
                        <el-descriptions-item label="Priority">
                            <el-tag size="small">School</el-tag>
                        </el-descriptions-item>
                        <el-descriptions-item label="Assignees">
                            <User :user="task.author" only-avatar />
                        </el-descriptions-item>
                        <el-descriptions-item label="Followers">
                            <User :user="task.author" only-avatar />
                        </el-descriptions-item>
                    </el-descriptions>

                </div>
            </div>
        </el-aside>
    </el-container>
</template>

<style>
.affix-container {
//text-align: center; //height: 400px; //border-radius: 4px; background: var(--el-color-primary-light-9);
}

.el-menu {
    border: 0;
}

.el-col {
    text-align: center;
}


.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
