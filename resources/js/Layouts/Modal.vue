<script setup>
import {useModal} from "momentum-modal"
import {router, usePage} from "@inertiajs/vue3"

const props = defineProps({
    width: {
        type: String,
        default: '80%',
    },
})

const {show, close, redirect} = useModal()

const handleClose = () => {
    const redirectURL = usePage()?.props?.modal?.redirectURL;
    if (redirectURL && location.href !== redirectURL) {
        router.visit(
            redirectURL, {
                preserveScroll: true,
                preserveState: true,
                only: ['times', 'tasks', 'projects'],
            })
    }
}
</script>

<template>
    <el-dialog
        v-model="show"
        :width="width"
        @closed="handleClose"
        draggable
    >
        <template #header>
            <slot name="title"/>
        </template>
        <slot/>
        <template #footer>
            <slot name="footer"/>
        </template>
    </el-dialog>
</template>

<style lang="scss">
.el-dialog__body {
    padding-top: 15px;
    padding-bottom: 15px;
}
</style>
