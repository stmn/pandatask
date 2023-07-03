<script setup>
import {Link, usePage} from '@inertiajs/vue3'
import {computed, ref} from "vue";
import Timer from "@/Components/Timer.vue";
import {useNow} from "@vueuse/core";

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
    {{ text }} <slot name="old" /> <slot name="new" />
</template>

<style lang="scss">

</style>
