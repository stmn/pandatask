<template>
    <div class="avatar">
        <svg xmlns="http://www.w3.org/2000/svg" :width="width" :height="height" viewBox="0 0 128 128">
            <circle cx="64" cy="64" r="64" :stroke="color" stroke-width="0" :fill="color"/>
            <text font-size="62" fill="#FFFFFF" x="50%" y="50%" dy=".1em" style="line-height:1"
                  alignment-baseline="middle" text-anchor="middle" dominant-baseline="central">{{ initials }}
            </text>
        </svg>
    </div>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
    name: {
        type: String,
        default: 'X',
    },
    letters: {
        type: Number,
        default: 2,
    },
});

const backgrounds = [
    '#f44336',
    '#E91E63',
    '#9C27B0',
    '#673AB7',
    '#3F51B5',
    '#2196F3',
    '#03A9F4',
    '#00BCD4',
    '#009688',
    '#4CAF50',
    '#8BC34A',
    '#CDDC39',
    '#FFC107',
    '#FF9800',
    '#FF5722',
];

const initials = computed(() => {
    const words = props.name.split(' ');
    return words.map(word => word[0]).join('').toUpperCase();
});

const color = computed(() => {
    const colorIndex = initials.value.charCodeAt(0) % backgrounds.length;
    return backgrounds[colorIndex];
});
</script>

<style lang="scss" scoped>
.avatar {
    display: inline-block;;
    -webkit-font-smoothing: antialiased;
    font-weight: 700;
    -webkit-touch-callout: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
}
</style>
