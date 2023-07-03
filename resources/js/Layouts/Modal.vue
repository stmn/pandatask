<script lang="ts" setup>
import {useModal} from "momentum-modal"
import {router, usePage} from "@inertiajs/vue3"

const props = defineProps({
    width: {
        type: String,
        default: '80%',
    },
    times: {
        type: Array,
        required: true
    },
})
const {show, close, redirect} = useModal()
const handleClose = (done: () => void) => {
    // redirect({only: ['times']});

    // close();
    // redirect();
    console.log(usePage().url, usePage()?.props?.modal.redirectURL, location.href, usePage())

    if(location.href !== usePage()?.props?.modal.redirectURL) {
        router.visit(usePage()?.props?.modal.redirectURL, {
            preserveScroll: true,
            preserveState: true,
            only: ['times', 'tasks'],
        })
    }

    // close();
    // ElMessageBox.confirm('Are you sure to close this dialog?')
    //     .then(() => {
    //         done()
    //     })
    //     .catch(() => {
    //         // catch error
    //     })
}
</script>

<template>
    <el-dialog
        v-model="show"
       :width="width"
        @closed="handleClose"
        draggable
    >
        <template #title>
            <slot name="title"/>
        </template>
        <slot/>
        <template #footer>
            <slot name="footer"/>
        </template>
    </el-dialog>
</template>
