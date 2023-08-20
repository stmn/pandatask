<script setup>
import {computed} from "vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import ActivityItem from "@/Components/Activity/ActivityItem.vue";

const props = defineProps({
    activities: {
        required: true
    },
    showTask: {
        type: Boolean,
        required: false,
        default: false
    },
    onlyComments: {
        type: Boolean,
        required: false,
        default: false
    },
})

const activitiesData = computed(() => props.activities?.data || props.activities);
</script>

<template>
    <el-timeline px-1>
        <ActivityItem v-for="event in activitiesData"
                      :event="event"
                      :only-comments="onlyComments"
                      :show-task="showTask"
        />
    </el-timeline>

    <Pagination v-if="activities?.data" :data="activities"/>
</template>
