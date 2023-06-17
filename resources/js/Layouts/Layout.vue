<script setup>
import {Link, router, usePage} from '@inertiajs/vue3'
import {computed, ref, watch} from "vue";
import {HomeFilled, List, Menu} from "@element-plus/icons-vue";
import {Modal} from 'momentum-modal'

import {useDark, useToggle} from "@vueuse/core";
import GlobalTimer from "@/Components/GlobalTimer.vue";
import {ElMessage} from "element-plus";
import Header from "@/Components/Layout/Header.vue";

const isDark = useDark()
const toggleDark = useToggle(isDark)

const props = defineProps({
    activeIndex: {
        type: String,
        required: true,
        default: ''
    },
});

const page = usePage()

const message = computed(() => usePage().props.flash.message);

watch(message, (message) => {
    if (message) {
        ElMessage({
            message: message.message,
            type: message.type
        });
    }
});

const activeIndex = ref(props.activeIndex);

router.on('success', (event) => {
    activeIndex.value = event.detail.page.props.activeIndex;
})

const handleSelect = ({index}, middleClick = false) => {
    // const openInNewTab = middleClick || event.metaKey;
    // const url = route(index);
    //
    //
    // if (openInNewTab) {
    //     const win = window.open(url, '_blank');
    //     win.focus();
    // } else {
    //     router.visit(url);
    // }
}
</script>

<template>
    <el-container style="height: 100%;">
        <Header />
        <el-main
            style="background: var(--el-bg-color); border-radius: 15px; margin: 0 30px;">
            <slot/>
        </el-main>
        <el-footer>
            <br>
            <center>
                <small>Copyright &copy; <a href="#">Pandatask</a> v0.1</small>
            </center>
        </el-footer>
    </el-container>

    <Modal/>
    <GlobalTimer/>
</template>

<style lang="scss">
//.el-menu-item {
//    padding: 0;
//}
//
//.el-menu-item a {
//    padding: 0 var(--el-menu-base-level-padding);
//}
</style>
