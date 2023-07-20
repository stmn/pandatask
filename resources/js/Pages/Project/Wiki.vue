<script setup>
import {Head, router, useForm} from '@inertiajs/vue3';
import {nextTick, ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import ProjectLayout from "@/Layouts/ProjectLayout.vue";
import EditorContent from "@/Components/EditorContent.vue";
import Editor from "@/Components/Editor.vue";
import InputError from "@/Components/InputError.vue";
import {useTimeAgo} from "@vueuse/core";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    project: {},
    pages: {},
    page: {},
});

const adding = ref(!props.page);
const editing = ref(false);

const hash = ref(location.hash.replace('#', ''));

const form = useForm({
    title: props.page?.title ?? '',
    content: props.page?.content ?? '',
});

const cancel = () => {
    editing.value = false;
    adding.value = false;
    const style = document.querySelector('style[data-tiptap-style]');
    if (style) {
        style.remove();
    }
}

const savePage = () => {
    form.post(route('project.pages.save', {
        project: props.project.id,
        page: adding.value ? null : props.page?.slug_title
    }), {
        preserveScroll: true,
        onSuccess: () => {
            cancel();
        }
    })
}

const deletePage = () => {
    form.delete(route('project.pages.delete', {project: props.project.id, page: props.page?.slug_title}), {
        preserveScroll: true,
        onSuccess: () => {
            cancel();
        }
    })
}

const addPage = () => {
    adding.value = true;
    editing.value = false;
    form.title = '';
    form.content = '';
    nextTick(() => {
        document.getElementById('title')?.focus();
    })
}

const goToPage = (url, _hash) => {
    cancel();
    router.visit(url, {preserveState: true});
    hash.value = _hash;
}
</script>

<template>
    <Head :title="project.name + ' - Wiki'"/>

    <el-alert show-icon style="margin-bottom: 15px;">
        This page allows you to define a list of custom subpages with important information for your project. You can
        divide the subpages into sections using headers, and the side menu will display all your subpages and headers as
        a single, cohesive table of contents.
    </el-alert>

    <el-row :gutter="30">
        <!-- Col -->

        <el-col :lg="6" :xl="6" :md="6" :sm="6" :xs="{span: 24}" style="margin-bottom: 15px;">
            <!-- Buttons -->

            <div style="display: flex; width: 100%; height: 32px; margin-bottom: 15px;">
                <el-button :color="$primaryColor()"
                           @click="addPage">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Add page
                </el-button>
            </div>

            <!-- Sidebar -->

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
        </el-col>

        <!-- Col -->

        <el-col :lg="18" :xl="18" :md="18" :sm="18" :xs="{span: 24}">
            <!-- Buttons -->

            <div style="display: flex; width: 100%; height: 32px; margin-bottom: 15px;">
                <el-button v-if="editing || adding"
                           :color="$primaryColor()"
                           @click="cancel">
                    <i class="fa-solid fa-arrow-left mr-2"></i>Cancel
                </el-button>
                <el-button v-if="!editing && !adding"
                           :color="$primaryColor()"
                           @click="editing = true; form.title = page.title; form.content = page.content;">
                    <i class="fa-solid fa-edit mr-2"></i>Edit
                </el-button>
                <el-button v-if="editing || adding"
                           type="success"
                           @click="savePage"
                >
                    <i class="fa-solid fa-check mr-2"></i>Save
                </el-button>
                <el-popconfirm v-if="form.title !== 'Home'" title="Are you sure to delete this?" @confirm="deletePage"
                               width="222">
                    <template #reference>
                        <el-button v-if="!adding"
                                   plain type="danger"
                                   style="margin-left: auto;"
                        >
                            <i class="fa-solid fa-trash mr-2"></i>Delete
                        </el-button>
                    </template>
                </el-popconfirm>
            </div>

            <!-- Content -->

            <el-card shadow="never">
                <template v-if="editing || adding">
                    <el-form>
                    <el-form-item>
                        <el-tooltip :disabled="form.title!=='Home'" content="Home page title cannot be changed."
                                    placement="top">
                            <el-input v-model="form.title" :disabled="form.title==='Home'" placeholder="Title"
                                      id="title"/>
                        </el-tooltip>
                        <InputError :message="form.errors.title"/>
                    </el-form-item>
                    <InputError :message="form.errors.content"/>
                    <Editor v-model="form.content" :min-height="400" :max-height="400"
                            placeholder="Enter page content..."/>
                    </el-form>
                </template>

                <EditorContent v-else-if="page" :content="page.converted_content"/>

                <template #header>
                    {{ page?.title }}
                    <template v-if="page && page.updated_by?.id">
                        <br>
                        <el-text size="small">
                            Last update: {{ useTimeAgo(page.updated_at).value }} by <b>{{
                                page.updated_by.full_name
                            }}</b>
                        </el-text>
                    </template>
                </template>
            </el-card>
        </el-col>
    </el-row>
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
