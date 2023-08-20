<script setup>
import {Link} from "@inertiajs/vue3";
import UserPopover from "@/Components/User/UserPopover.vue";
import Time from "@/Components/Common/TimeValue.vue";

const props = defineProps({
    event: {
        type: Object,
        required: false
    },
    showTask: {
        type: Boolean,
        required: false,
        default: false
    },
})
</script>

<template>
    <div flex items-center>
        <UserPopover :user="event.user"/>

        <span mx-1>{{ event.description }}</span>

        <Link :href="event.task.url"
              v-if="showTask">
            {{ event.task.subject }}
        </Link>

        <el-tag v-if="event.private">Private</el-tag>

        <Time class="el-timeline-item__timestamp"
              ml-2
              :time="event.created_at"/>
    </div>
</template>
