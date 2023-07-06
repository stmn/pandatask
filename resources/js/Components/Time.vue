<script setup>
import {usePage} from '@inertiajs/vue3'
import {computed, ref} from "vue";
import {useDateFormat, useStorage, useTimeAgo} from "@vueuse/core";

const props = defineProps({
    time: {
        type: String,
        required: true
    },
    forceType: {
        type: String,
        required: false,
        default: ''
    }
})

const page = usePage()

const auth = computed(() => page.props.auth)

const timeType = props.forceType ? ref(props.forceType) : useStorage('timeType', 'ago'); // ago, date

const dateFormat = 'DD.MM.YYYY hh:mm';

const toggleTimeType = () => {
    if(props.forceType){
        return;
    }

    timeType.value = timeType.value === 'ago' ? 'date' : 'ago'
}
</script>

<template>
    <span :class="`time ${forceType ? 'forced' : 'not-forced'}`"
          :title="timeType !== 'ago' ? useTimeAgo(time).value : useDateFormat(time, dateFormat).value"
          @click="toggleTimeType">{{
            timeType === 'ago' ? useTimeAgo(time).value : useDateFormat(time, dateFormat).value
        }}
    </span>
</template>

<style lang="scss" scoped>
.time.not-forced {
    cursor: pointer;

    color: var(--el-text-color-secondary);

    &:hover {
        text-decoration: underline;
    }
}
</style>
