<script setup>
import {Link} from "@inertiajs/vue3";
import UserPopover from "@/Components/User/UserPopover.vue";
import Time from "@/Components/Common/TimeValue.vue";
import ActivityDropdown from "@/Components/Activity/ActivityDropdown.vue";

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

        <el-tag v-if="event?.task?.deleted_at" type="danger" size="small" ml-1>Deleted</el-tag>

        <el-tag v-if="event?.private || event.task?.private" type="info" size="small" ml-1>Private</el-tag>

        <Time class="el-timeline-item__timestamp"
              ml-2
              :time="event.created_at"/>

        <span ml-auto mr-1>
          <ActivityDropdown :activity="event">
          <i class="fas fa-ellipsis-h" style="margin-left: auto;" ></i>
        </ActivityDropdown>
        </span>
    </div>
</template>
