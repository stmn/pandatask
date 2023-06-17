<script setup>
import {usePage, Link} from '@inertiajs/vue3'
import {computed} from "vue";
import Time from "@/Components/Time.vue";
import User from "@/Components/User.vue";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
    task: {
        type: Object,
        required: false
    },
    activities: {
        required: true
    },
    showTask: {
        type: Boolean,
        required: false,
        default: false
    }
})

const activitiesData = computed(() => props.activities?.data || props.activities);

const page = usePage()

const auth = computed(() => page.props.auth)
</script>

<template>
    <transition name="el-fade-in-linear">
        <el-timeline style="padding: 5px 0 0 5px;">
            <el-timeline-item v-for="event in activitiesData" class="animated fadeIn" :key="event.id"
                              :id="`activity_${event.id}`">
                <div style="display: flex; align-items: center;">
                    <User :user="event.activity.author"/>
                    <span style="margin: 0 5px;">{{ event.description }}</span>
                    <Link style="margin-right: 5px;" :href="event.task.url" v-if="showTask">{{event.task.subject }}</Link>
                    <el-tag style="margin-right: 5px;">Private</el-tag>
                    <Time class="el-timeline-item__timestamp" :time="event.created_at"/>
                </div>
                <el-card v-if="event.type==='task_commented'" style="margin-top: 15px;">
                    {{ event.activity.content }}
                </el-card>
            </el-timeline-item>
        </el-timeline>
    </transition>

    <Pagination v-if="activities?.data" :data="activities"/>
</template>

<style lang="scss" scoped>
//.animated {
//    -webkit-animation-duration: 0.5s;
//    animation-duration: 0.5s;
//    -webkit-animation-fill-mode: both;
//    animation-fill-mode: both;
//}
//
//@keyframes fadeIn {
//    from {
//        opacity: 0;
//    }
//    to {
//        opacity: 1;
//    }
//}
//
//.fadeIn {
//    animation-name: fadeIn;
//}
</style>
