<script setup>
import {Link} from '@inertiajs/vue3'
import {computed} from "vue";
import DefaultAvatar from "@/Components/Common/DefaultAvatar.vue";
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";

const props = defineProps({
    user: {
        type: Object,
    },
    size: {
        type: Number,
        default: 24
    },
    onlyAvatar: {
        type: Boolean,
        default: false
    },
    disablePopover: {
        type: Boolean,
        default: false
    }
})

const fullName = computed(() => {
    return props.user?.first_name + ' ' + props.user?.last_name
})
</script>

<template>
    <el-popover
        placement="top-start"
        :width="300"
        trigger="click"
        :persistent="false"
        transition="none"
        :hide-after="0"
        :disabled="disablePopover"
    >
        <template #reference>
            <span class="user">
                <el-tooltip :content="fullName" placement="top" :disabled="!onlyAvatar">
                <BaseAvatar :avatar="user.avatar"
                            :name="user.full_name"
                            :size="props.size"
                            style="vertical-align: sub; margin-right: 5px;"/>
                </el-tooltip>
            </span>
        </template>

        <div>
            <BaseAvatar :avatar="user.avatar"
                        :name="user.full_name"
                        :size="50"
                        style="vertical-align: sub; float: left; margin-right: 10px;"/>

            <div><b>{{ user?.first_name }} {{ user?.last_name }}</b></div>
            <div><i class="fas fa-fw fa-briefcase mr-1"></i><small>{{ user.job_title }}</small></div>
            <div style="margin-top: 0;">

                <a :href="`mailto:${user.public_email || user.email}`">
                    <i class="fas fa-fw fa-envelope mr-1"></i>{{ user.public_email || user.email }}
                </a>
            </div>
            <div v-if="user.active_time" style="margin-top: 10px; border: 1px solid #666; padding: 5px 10px;">
                Working on
                <Link :href="$route('project', {project: user.active_time.task.project_id})" style="margin-right: 5px;">
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
    </el-popover>
</template>

<style lang="scss" scoped>
.user {
    cursor: pointer;
    display: flex;
    align-items: center;
    padding: 2px 0;

    .el-avatar {
        margin-top: -2px;
    }

    b {
        font-weight: 400;
        margin-top: -1px;
        color: var(--el-color-primary-dark-2);
    }

    &:hover {
        b {
            text-decoration: underline;
        }
    }
}
</style>
