<script setup>
import Pagination from "@/Components/Common/AppPagination.vue";

const props = defineProps({
    projects: {
        required: true
    },
});
</script>

<template>
    <div v-if="projects?.data.length > 0">
        <div v-for="item in projects.data" :key="item.id">
            <slot name="item" :item="item"/>
        </div>

        <pagination :data="projects"/>
    </div>
    <div v-else>
        <slot name="empty"/>
    </div>
</template>
