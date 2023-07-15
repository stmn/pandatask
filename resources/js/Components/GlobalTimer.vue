<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {ref} from "vue";
import Timer from "@/Components/Timer.vue";
import useTaskTimer from "@/Composables/useTaskTimer.js";
import {clearInterval, setInterval} from 'worker-timers';

const page = usePage()

const {auth, activeTime, time, seconds} = useTaskTimer();

// Timer in Title

const originalTitle = ref();
const workerId = ref();
window.addEventListener('blur', function () {
    if (!workerId.value && seconds.value > 0) {
        originalTitle.value = document.title;

        const newSeconds = ref(seconds.value);

        const func = () => {
            newSeconds.value++;

            const s = newSeconds.value;
            const m = Math.floor(s / 60);
            const h = Math.floor(m / 60);

            const time = `${h.toString().padStart(2, '0')}:${(m % 60).toString().padStart(2, '0')}:${(s % 60).toString().padStart(2, '0')}`;
            document.title = `[${time}] ${originalTitle.value}`;
        };

        func();

        workerId.value = setInterval(func, 1000);
    }
});

window.addEventListener('focus', function () {
    if (workerId.value) {
        clearInterval(workerId.value);
        workerId.value = null;
        document.title = originalTitle.value;
    }
});
</script>

<template>
    <Timer :task="activeTime?.task">
        <!--        <template #play>-->
        <!--            <el-button>PLAY</el-button>-->
        <!--        </template>-->
        <template #stop>
            <el-popover
                placement="left"
                :width="260"
                trigger="hover"
                :show-after="600"
            >
                <template #default>
                    <div style="line-height: 20px;">
                        <el-text truncated>
                        <Link
                            :href="activeTime?.task.url">{{
                                activeTime?.task?.subject
                            }}
                        </Link>
                        </el-text>
                        <br>
                        <div style="display: flex; justify-content: space-between;">
                            <div>
                                <b>Time:</b> {{ time }}
                            </div>
                            <Link preserve-state
                                  preserve-scroll :only="['modal', 'tasks']"
                                  :href="$route('project.timesheets.edit', {project: activeTime.task.project_id, time: activeTime.id})">
                                <el-button type="primary" size="small">Edit</el-button>
                            </Link>
                        </div>
                    </div>
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
    transition: all 0.2s ease-in-out;
    display: flex;
    justify-content: center;
    align-items: center;
    border: 3px solid var(--el-bg-color);
    cursor: pointer;
    color: #fff;

    height: 80px;
    width: 80px;
    background: var(--el-color-danger-light-3);
    border-radius: 50%;
    text-align: center;
    position: fixed;
    top: 15px;
    right: 15px;
    z-index: 10;
    font-size: 14px;
    //box-shadow: 0 0 5px rgba(0, 0, 0, 0.5);

    &:hover {
        background: var(--el-color-danger-light-5);
        //border-color: rgba(255, 255, 255, 1);
    }

    &:active {
        //transition: all 0s ease-in-out;
        //background: rgba(255, 0, 0, 0.8);
        //border-color: var(--el-color-primary-dark-2);
        //box-shadow: none;
    }
}
</style>
