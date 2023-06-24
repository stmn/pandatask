<script lang="ts" setup>
import {useModal} from "momentum-modal"

// const dialogVisible = ref(true)

const handleClose = (done: () => void) => {
    redirect();
    // close();
    // ElMessageBox.confirm('Are you sure to close this dialog?')
    //     .then(() => {
    //         done()
    //     })
    //     .catch(() => {
    //         // catch error
    //     })
}

// import { TransitionRoot, TransitionChild, Dialog, DialogPanel, DialogTitle } from "@headlessui/vue"
const {show, close, redirect} = useModal()


</script>

<template>
    <el-dialog
        v-model="show"
        width="80%"
        @close="handleClose"
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
    <!--    <TransitionRoot appear as="template" :show="show">-->
    <!--        <Dialog as="div" class="relative z-10" @close="close">-->
    <!--            <TransitionChild-->
    <!--                @after-leave="redirect"-->
    <!--                as="template"-->
    <!--            </TransitionChild>-->

    <!--            <div class="fixed inset-0 overflow-y-auto">-->
    <!--                <div class="flex min-h-full items-center justify-center p-4 text-center">-->
    <!--                    <TransitionChild-->
    <!--                        as="template"-->
    <!--                        <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">-->
    <!--                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900">-->
    <!--                                <slot name="title" />-->
    <!--                            </DialogTitle>-->
    <!--                            <slot />-->
    <!--                        </DialogPanel>-->
    <!--                    </TransitionChild>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </Dialog>-->
    <!--    </TransitionRoot>-->
</template>
