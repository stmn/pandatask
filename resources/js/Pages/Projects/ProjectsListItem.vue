<script setup>
import {Link} from "@inertiajs/vue3";
import Activity from "@/Components/Activity/ActivityType.vue";
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";
import Time from "@/Components/Common/TimeValue.vue";
import UserPopover from "@/Components/User/UserPopover.vue";

const props = defineProps({
    item: {
        required: true
    }
});
</script>

<template>
    <el-card class="project-card mb-4" shadow="never">
        <!--        <template #header>-->
        <div class="card-header">
            <div flex items-center>
                <BaseAvatar
                    :size="32"
                    :name="item.name"
                    :avatar="item.avatar"
                    style="margin-right: 10px; margin-top: -2px;"/>

                <Link :href="$route('project', {project: item.id})">
                    {{ item.name }}
                </Link>

                <template v-if="item.latest_activity">
                    <el-divider direction="vertical"></el-divider>

                    <div class="last-activity">
                        <UserPopover :user="item.latest_activity.user"/>
                        <Activity :activity="item.latest_activity"/>
                        <Time :time="item.latest_activity.created_at"/>
                    </div>
                </template>
            </div>

            <div>
                <Link preserve-state preserve-scroll :only="['modal', 'project']"
                      :href="$route('project.tasks.create', {project: item.id})">
                    <el-button type="success">
                        <i class="fas fa-circle-plus mr-2"/>
                        Add task
                    </el-button>
                </Link>

                &nbsp;

                <el-dropdown>
                    <template #default>
<!--                                                <el-button type="default" text link>-->
                                                    <el-button type="primary">
                        <i class="fa-solid fa-bars"></i>
<!--                                                    Actions-->
                                                </el-button>
                    </template>
                    <template #dropdown>
                        <Link :href="$route('project.tasks', {project: item.id})" style="text-decoration: none;">
                            <el-dropdown-item>
                                <el-text size="small">
                                    <i class="fas fa-list-check pr-1"/>Tasks
                                </el-text>
                            </el-dropdown-item>
                        </Link>
                        <Link preserve-state preserve-scroll :only="['modal']"
                              v-if="$can('edit project', item)"
                              :href="$route('projects.edit', {project: item.id})">
                            <el-dropdown-item>
                                <el-text size="small">
                                    <i class="fas fa-edit pr-1"/>Settings
                                </el-text>
                            </el-dropdown-item>
                        </Link>
                        <Link preserve-state preserve-scroll :only="['modal', 'project']"
                              v-if="$can('create tasks', item)"
                              :href="$route('project.tasks.create', {project: item.id})">
                            <el-dropdown-item>
                                <el-text size="small">
                                    <i class="fas fa-circle-plus pr-1"/>Add task
                                </el-text>
                            </el-dropdown-item>
                        </Link>
                    </template>
                </el-dropdown>

                &nbsp;

                <!--                <Link :href="$route('project.tasks', {project: item.id})">-->
                <!--                    <el-button :color="$primaryColor()">-->
                <!--                        <i class="fas fa-list-check mr-2"/> Tasks-->
                <!--                    </el-button>-->
                <!--                </Link>-->
                <!--                &nbsp;-->
                <!--                <Link preserve-state preserve-scroll :only="['modal']"-->
                <!--                      :href="$route('projects.edit', {project: item.id})">-->
                <!--                    <el-button type="success">-->
                <!--                        <i class="fas fa-circle-plus mr-2"/>-->
                <!--                        Edit project-->
                <!--                    </el-button>-->
                <!--                </Link>-->
                <!--                &nbsp;-->

            </div>
        </div>
        <!--        </template>-->
        <!--        <div class="text item">-->
        <!--            <el-text>-->
        <!--                Clients:-->
        <!--            </el-text>-->
        <!--            <el-text>-->
        <!--                Members:-->
        <!--            </el-text>-->
        <!--            {{ item.description || '&nbsp;' }}-->

        <!--            <div style="max-width: 200px;height: 10px;display:flex;border-radius: 10px;">-->
        <!--                <template>-->
        <!--                    <el-tooltip>-->
        <!--                        <template #content>-->
        <!--                            1 tasks-->
        <!--                        </template>-->
        <!--                        <template #default>-->
        <!--                            <div style="width: 50%;height: 10px; background: red;border-radius: 10px;">-->

        <!--                            </div>-->
        <!--                        </template>-->
        <!--                    </el-tooltip>-->
        <!--                </template>-->
        <!--            </div>-->
        <!--        </div>-->
    </el-card>
</template>

<style lang="scss" scoped>
.project-card {
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .last-activity {
        display: flex;
        align-items: center;
        font-size: 12px;

        * {
            margin: 0 5px;
        }
    }
}
</style>
