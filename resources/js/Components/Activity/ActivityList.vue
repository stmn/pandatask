<script setup>
import {computed} from "vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import ActivityItem from "@/Components/Activity/ActivityItem.vue";
import HeroCard from "@/Components/Common/HeroCard.vue";

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
    <template v-if="!activitiesData.length">
        <HeroCard title="Activity not found"
                  description="Every action in the project is recorded in the activity log."
                  type="not-found"/>
    </template>
    <template v-else>
        <el-timeline px-1>
            <ActivityItem v-for="event in activitiesData"
                          :event="event"
                          :only-comments="onlyComments"
                          :show-task="showTask"
            />
        </el-timeline>

        <Pagination v-if="activities?.data" :data="activities"/>
    </template>
</template>

<style lang="scss">
    .el-timeline-item:last-child {
        padding-bottom: 0;
    }
</style>
