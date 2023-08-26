<script setup>
const props = defineProps({
    title: {
        type: String,
        required: false,
        default: ''
    },
    description: {
        type: String,
        required: false,
        default: ''
    },
    type: {
        type: String,
        required: false,
        default: 'default'
    },
})


const images = {
    'default': '/img/panda.png',
    'not-found': '/img/panda2.png',
};
</script>

<template>
    <div style="height: 100%; display: flex; flex-direction: column; margin: auto;" :class="`hero hero-${type}`">
        <div>
            <img :src="images[type]" alt="" class="img"/>
            <div>
                <slot name="title">
                    <p class="title" v-if="title">{{ title }}</p>
                </slot>

                <slot name="description">
                    <p class="description" v-if="description">{{ description }}</p>
                </slot>

                <p v-if="$slots.buttons" style=" margin-bottom: 0;">
                    <slot name="buttons">
                        &nbsp;
                    </slot>
                </p>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.hero {
    text-align: center;

    img {
        width: 100%;
    }

    &-default {
        > div {
            padding-bottom: 0;
        }
        img {
            max-width: 500px;
        }
    }

    &-not-found {
        > div {
            padding-bottom: 0px;
        }
        img {
            max-width: 200px;
        }
    }
}

.title {
    font-size: 32px;
    font-weight: 800;
}

.description {
    font-size: 18px;
    font-weight: 600;
    opacity: 0.6;
}
</style>
