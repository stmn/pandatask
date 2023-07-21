<script setup>
const props = defineProps({
    text: {
        // type: Object,
        // required: false
    },
    old: {
        // type: Object,
        // required: false
    },
    new: {
        // type: Boolean,
        // required: false,
        // default: false
    }
})
</script>

<template>
    <div v-html="renderTemplate"></div>
    {{ text }}
    <slot name="old"/>
    <slot name="new"/>
</template>

<style lang="scss">

</style>
