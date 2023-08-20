<script setup>
import {ref} from "vue";
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
    },
    showClock: {
        type: Boolean,
        required: false,
        default: true
    }
})

const timeType = props.forceType ? ref(props.forceType) : useStorage('timeType', 'ago'); // ago, date

const dateFormat = 'DD.MM.YYYY hh:mm';

const toggleTimeType = () => {
    if (props.forceType) {
        return;
    }

    timeType.value = timeType.value === 'ago' ? 'date' : 'ago'
}
</script>

<template>
    <span :class="`time ${forceType ? 'forced' : 'not-forced'}`"
          :title="timeType !== 'ago' ? useTimeAgo(time).value : useDateFormat(time, dateFormat).value"
          @click="toggleTimeType"><i v-if="showClock" class="far fa-clock mr-1"></i>{{
            timeType === 'ago' ? useTimeAgo(time).value : useDateFormat(time, dateFormat).value
        }}
    </span>
</template>

<style lang="scss" scoped>
.time {
    font-size: 13px;
    white-space: nowrap;
    i {

    }
    &.not-forced {
        cursor: pointer;
        color: var(--el-text-color-secondary);

        &:hover {
            text-decoration: underline;
        }
    }
}
</style>
