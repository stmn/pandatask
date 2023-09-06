<script setup>
import {Link} from "@inertiajs/vue3";
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";
import UserAvatar from "@/Components/User/UserAvatar.vue";
import UserName from "@/Components/User/UserName.vue";

const props = defineProps({
    user: {
        type: Object,
    },
})
</script>

<template>
    <el-popover
        placement="top-start"
        :width="320"
        trigger="click"
        :persistent="false"
        transition="none"
        :hide-after="0"
    >
        <template #reference>
            <span style="cursor: pointer; display: inline-block;">
            <slot>
                <div flex items-center>
                    <span mr-1>
                        <UserAvatar :user="user"/>
                    </span>
                    <UserName :user="user" class="mx-1"/>
                </div>
            </slot>
            </span>
        </template>

        <div flex items-center py-1>
            <BaseAvatar :avatar="user.avatar"
                        :name="user.full_name"
                        :size="50"
                        style="align-self: flex-start; margin-right: 10px;"/>

            <div style="line-height: 22px;">
                <div><b>{{ user?.first_name }} {{ user?.last_name }}</b> <el-tag v-if="user.deleted_at" size="small" type="danger">Deleted</el-tag></div>
                <div><small>{{ user.job_title }}</small></div>
                <div style="margin-top: 0;">

                    <a :href="`mailto:${user.public_email || user.email}`">
                        <i class="fas fa-fw fa-envelope mr-1"></i>{{ user.public_email || user.email }}
                    </a>
                </div>
                <div v-if="user.active_time" style="margin-top: 10px; border: 1px solid #666; padding: 5px 10px;">
                    Working on
                    <Link :href="$route('project', {project: user.active_time.task.project_id})"
                          style="margin-right: 5px;">
                        {{ user.active_time.task.project.name }}
                    </Link>
                    <el-tooltip :content="user.active_time.task.subject">
                        <Link
                            :href="$route('project.task', {project: user.active_time.task.project_id, task: user.active_time.task.number})">
                            #{{ user.active_time.task.number }}
                        </Link>
                    </el-tooltip>
                </div>
            </div>
        </div>
    </el-popover>
</template>
