<script setup>
import {Link, router, usePage} from '@inertiajs/vue3'
import {computed, ref, watch} from "vue";
import {Modal} from 'momentum-modal';
import GlobalTimer from "~/js/Components/Layout/AppTimer.vue";
import {ElMessage} from "element-plus";
import Header from "~/js/Components/Layout/AppHeader.vue";
import usePageLoading from "@/Composables/usePageLoading.js";
import ColorPanel from "~/js/Components/Demo/ColorPanel.vue";

const props = defineProps({
    activeIndex: {
        type: String,
        default: ''
    },
});

const page = usePage()

const message = computed(() => usePage().props.flash.message);

watch(message, (message) => {
    if (message) {
        ElMessage({
            message: message.message,
            type: message.type,
            position: 'bottom-right',
        });
    }
});

const activeIndex = ref(props.activeIndex);

router.on('success', (event) => {
    activeIndex.value = event.detail.page.props.activeIndex;
})

const {loading} = usePageLoading();

const svg = `<path d="M12,4a8,8,0,0,1,7.89,6.7A1.53,1.53,0,0,0,21.38,12h0a1.5,1.5,0,0,0,1.48-1.75,11,11,0,0,0-21.72,0A1.5,1.5,0,0,0,2.62,12h0a1.53,1.53,0,0,0,1.49-1.3A8,8,0,0,1,12,4Z"><animateTransform attributeName="transform" type="rotate" dur="0.75s" values="0 12 12;360 12 12" repeatCount="indefinite"/></path>`;
</script>

<template>
    <ColorPanel/>

    <Transition appear>
    <el-container style="min-height: 100%;">
        <Header style="color: var(--brand-text-color);"/>

        <el-main
            v-loading="loading"
            :element-loading-svg="svg"
            style="
            background: var(--el-bg-color);
            color: var(--el-text-color-primary);
            border-radius: 15px;
            margin: 0 20px;
            position: relative;
            overflow: hidden;">
            <div style="height: 100%;min-height: calc(100vh - 160px);display:flex;flex-direction:column;">
                <el-alert v-if="$page.props.auth.impersonated"
                          type="warning"
                          show-icon
                          :closable="false"
                          style="margin-bottom: 15px;">
                    <span>
                        Impersonating as <strong>{{ $page.props.auth.user.full_name }}</strong>.
                        <Link :href="$route('leave-impersonation')">Leave impersonation</Link>
                    </span>
                </el-alert>

                <slot/>
            </div>
        </el-main>

        <el-footer style="color: var(--brand-text-color);">
            <br>
            <small>
                &copy; <a href="https://pandatask.app" rel="nofollow" target="_blank"><b>Pandatask</b></a> v1.0
            </small>
        </el-footer>
    </el-container>
    </Transition>

    <Modal/>
    <GlobalTimer/>
</template>

<style lang="scss">
.el-header > ul > .el-menu-item {
    & > a {
        color: var(--el-text-color);
    }
}

.el-footer {
    color: var(--el-text-color);
    text-align: center;

    a {
        font-weight: 400;
        color: var(--el-text-color) !important;
    }
}
</style>
