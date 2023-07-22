<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {ref} from "vue";
import Timer from "~/js/Components/Task/TimerButton.vue";
import useTaskTimer from "@/Composables/useTaskTimer.js";
import {clearInterval, setInterval} from 'worker-timers';
import TaskLink from "@/Components/Task/TaskLink.vue";

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
    <div class="global-timer">
        <Transition name="slide-fade">
            <template v-if="activeTime?.task">
                <Timer :task="activeTime?.task">
                    <template #stop>
                        <el-popover
                            placement="left"
                            :width="260"
                            trigger="contextmenu"
                            :show-after="600"
                        >
                            <template #default>
                                <div style="">
                                    <TaskLink :task="activeTime?.task" flex items-center mb-2 />
                                    <div style="display: flex; align-items: center;">
                                        <div style="min-width: 90px;">
                                            <i class="fa-solid fa-clock mr-1"></i> {{ time }}
                                        </div>
                                        <Link preserve-state
                                              preserve-scroll :only="['modal', 'tasks']"
                                              :href="$route('project.timesheets.edit', {project: activeTime.task.project_id, time: activeTime.id})">
                                            <el-button :color="$primaryColor()" size="small">
                                                <i class="fa-solid fa-edit mr-1"></i>Edit
                                            </el-button>
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
        </Transition>
    </div>
</template>

<style lang="scss">
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}

.global-timer {
    position: fixed;
    z-index: 10;
    top: 15px;
    right: 15px;
}

.timer-stop {
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
    font-size: 14px;

    &:hover {
        background: var(--el-color-danger-light-5);
    }

}
</style>
