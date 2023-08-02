<script setup>
import ListHeader from "@/Components/List/ListHeader.vue";
import {useCreateList} from "@/Composables/useList.js";
import {ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const props = defineProps(['listKey', 'listColumns', 'selectedColumns', 'sort', 'only']);

// const columns = ref([
//     {
//         label: 'Status',
//         value: 'status_id',
//     },
//     {
//         label: 'Priority',
//         value: 'priority_id',
//     },
//     {
//         label: 'Latest activity',
//         value: 'latest_activity_at',
//     }
// ]);
//
// usePage().props.project?.custom_fields?.forEach((field) => {
//     columns.value.push({
//         label: field.label,
//         value: 'custom_fields->' + field.key,
//     });
// });

const {list} = useCreateList({
    only: props.only,
    // columns: columns.value,
    key: props.listKey,
    selectedColumns: props.selectedColumns,
    sort: props.sort,//{value: 'latest_activity_at', label: 'Latest activity'},
    order: 'desc',
});
</script>

<template>
    <ListHeader searchable sortable columns />

    <slot :list="list"></slot>
</template>
