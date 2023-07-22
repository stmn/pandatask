<script setup>
import {router} from "@inertiajs/vue3";
import {inject} from "vue";

const props = defineProps(['settings']);

const handleTasks = inject('handleTasks');
const handleProjects = inject('handleProjects');

const emit = defineEmits(['close']);

const submit = () => {
    router.post(route('dashboard.save-settings'), props.settings, {
        only: ['tasks', 'projects', 'flash'], onSuccess: (response) => {
            handleProjects(response);
            handleTasks(response);
            emit('close');
        }
    });
}

const options = [3, 5, 7, 10]
</script>

<template>
    <div class="dashboard-settings">
        <div>
            Show
            <el-dropdown trigger="click">
                <u>{{ settings.dashboard_projects }} projects</u>
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item v-for="option in options"
                                          :key="option"
                                          @click="settings.dashboard_projects = option"
                                          :disabled="settings.dashboard_projects===option">
                            {{ option }} projects
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
            with
            <el-dropdown trigger="click">
                <u>{{ settings.dashboard_tasks }} tasks</u>
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item v-for="option in options"
                                          :key="option"
                                          @click="settings.dashboard_tasks = option"
                                          :disabled="settings.dashboard_tasks===option">
                            {{ option }} tasks
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
            each.
        </div>

        <el-button type="success" size="small" @click="submit">
            <i class="fa-solid fa-check"></i>
        </el-button>
    </div>
</template>

<style scoped>
.dashboard-settings {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.el-dropdown {
    line-height: initial;
    font-size: inherit;
    outline: none !important;
    cursor: pointer;
    vertical-align: initial;
}

:focus-visible {
    outline: none !important;
}
</style>
