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
        <template #header>
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
                    <Link :href="$route('project.tasks', {project: item.id})">
                        <el-button :color="$primaryColor()">
                            <i class="fas fa-list-check mr-2"/> Tasks
                        </el-button>
                    </Link>
                    &nbsp;
                    <Link preserve-state preserve-scroll :only="['modal']"
                          :href="$route('project.tasks.create', {project: item.id})">
                        <el-button type="success">
                            <i class="fas fa-circle-plus mr-2"/>
                            Add task
                        </el-button>
                    </Link>
                </div>
            </div>
        </template>
        <div class="text item">
            {{ item.description || '&nbsp;' }}
        </div>
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
