<script setup>
import {router} from "@inertiajs/vue3";
import useWiki from "@/Composables/useWiki.js";

const props = defineProps({
    project: {
        type: Object,
        required: true
    },
    pages: {
        type: Array,
        required: true
    },
    page: {
        type: Object,
        required: false
    },
});

const {cancel, hash} = useWiki();

const goToPage = (url, _hash = null) => {
    cancel();
    router.visit(url, {preserveState: true});
    hash.value = _hash;
}
</script>
<template>
    <el-card shadow="never" class="menu">
        <template #header>
            <el-text :color="$primaryColor()" size="large" tag="b">Pages</el-text>
            <el-tag round style="position: absolute; margin-left: 10px; margin-top: 1px;" size="small">
                {{ pages.length }}
            </el-tag>
        </template>

        <ul class="tree">
            <li v-for="(_page,index) in pages">
                <el-divider v-if="index > 0" direction="horizontal"/>

                <el-link @click="goToPage($route('project.pages.show', {project: project, page: _page}))"
                         :type="page.slug_title === _page.slug_title ? 'success' : 'primary'"
                         :underline="false"
                         class="page-title">
                    <el-text truncated :type="page.slug_title === _page.slug_title ? 'success' : 'primary'">
                        {{ _page.title }}
                    </el-text>
                </el-link>

                <ul>
                    <li v-for="header in _page.headers"
                        :style="`padding-left: ${header.level === 1 ? 0 : header.level*10}px;`">
                        <el-link
                            :underline="false"
                            :type="hash === header.id ? 'success' : 'primary'"
                            @click="goToPage($route('project.pages.show', {project: project, page: _page}) + `#${header.id}`, header.id)"
                            class="page-header">
                            <el-text truncated :type="hash === header.id ? 'success' : 'primary'">
                                <i class="fa-solid fa-arrow-right mr-2"></i>
                                {{ header.title }}
                            </el-text>
                        </el-link>
                    </li>
                </ul>
            </li>
        </ul>
    </el-card>
</template>
<style lang="scss" scoped>
.menu {
    ul {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .page-title {
        .el-text {
            font-size: 14px;
            font-weight: 600;
        }
    }

    .page-header {
        .el-text {
            font-size: 12px;
        }
    }

    .tree {
        .el-divider {
            margin: 10px 0;
        }

        > li > ul {
            margin-top: 5px;
        }

        .el-link {
            padding: 3px 0 !important;
            font-weight: 400;

            :deep(.el-icon) {
                margin-right: 5px;
            }
        }

    }
}
</style>
