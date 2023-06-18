<script setup>
import {usePage, Link} from '@inertiajs/vue3'
import {computed} from "vue";
import Timer from "@/Components/Timer.vue";
import {useNow} from "@vueuse/core";
import {Edit} from "@element-plus/icons-vue";


const page = usePage()

const auth = computed(() => page.props.auth)
const activeTime = computed(() => page.props.auth.user.active_time)

const time = computed(() => {
    if (!activeTime.value) {
        return 0;
    }

    const now = useNow();

    const seconds = Math.floor((now.value - Date.parse(activeTime.value?.start_at)) / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);

    return `${hours.toString().padStart(2, '0')}:${(minutes % 60).toString().padStart(2, '0')}:${(seconds % 60).toString().padStart(2, '0')}`;
});
</script>

<template>
    <Timer :task="activeTime?.task">
        <template #play>
            <el-button>PLAY</el-button>
        </template>
        <template #stop>
            <el-popover
                placement="top-end"
                :width="200"
                trigger="hover"
            >
                <template #default>
                    <b>Task:</b> <Link
                    :href="activeTime?.task.url">{{
                        activeTime?.task?.subject
                    }}</Link>
                    <br>
                    <b>Time:</b> {{ time }}

                    <Link preserve-state :href="route('project.timesheets.edit', {project: activeTime.task.project_id, time: activeTime.id})">
                        <el-button type="success" size="small">Edit</el-button>
                    </Link>
                </template>
                <template #reference>
                    <div class="timer-stop">
                        <div>
                            <b>STOP</b>
                            <div>{{ time }}</div>
                        </div>
                    </div>
                </template>
            </el-popover>
        </template>
    </Timer>
</template>

<style lang="scss">
.timer-stop {
    transition: all 0.5s ease-in-out;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 3px solid rgba(255, 255, 255, 0.8);
    cursor: pointer;
    color: #fff;

    height: 80px;
    width: 80px;
    background: rgba(200, 40, 40, 1);
    border-radius: 50%;
    text-align: center;
    position: fixed;
    top: 15px;
    right: 15px;
    z-index: 10;
    font-size: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);

    &:hover {
        background: rgba(255, 0, 0, 0.8);
        border-color: rgba(255, 255, 255, 1);
    }

    &:active {
        //transition: all 0s ease-in-out;
        //background: rgba(255, 0, 0, 0.8);
        border-color: rgba(200, 0, 0, 0.8);
        //box-shadow: none;
    }
}
</style>
