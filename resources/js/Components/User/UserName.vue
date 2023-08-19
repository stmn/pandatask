<script setup>
const props = defineProps({
    user: {
        type: Object,
    },
})
</script>

<template>
    <span>
        {{ user?.first_name }} {{ user?.last_name?.[0] }}
    </span>
</template>
