<script setup>
import ActivityDetails from "@/Components/Activity/ActivityDetails.vue";
import ActivityComment from "@/Components/Activity/ActivityComment.vue";
import ActivityAttachments from "@/Components/Activity/ActivityAttachments.vue";
import ActivityHeader from "@/Components/Activity/ActivityHeader.vue";

const props = defineProps({
    event: {
        type: Object,
        required: false
    },
    onlyComments: {
        type: Boolean,
        required: false,
        default: false
    },
    showTask: {
        type: Boolean,
        required: false,
        default: false
    },
})
</script>

<template>
    <el-timeline-item
        v-if="!onlyComments || event?.type === 'task_commented'"
        :key="event.id"
        :id="`activity_${event.id}`">

        <ActivityHeader :event="event" :show-task="showTask" />

        <ActivityComment v-if="event?.comment"
                         :comment="event?.comment">
            <template #after>
                <ActivityAttachments
                    v-if="event.media?.length"
                    :media="event.media"
                />
            </template>
        </ActivityComment>

        <ActivityDetails v-if="!onlyComments"
                         :details="event.details"/>
    </el-timeline-item>
</template>
