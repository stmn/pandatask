<script setup>
import {Head} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import WikiNavigation from "~/js/Pages/Project/Wiki/WikiNavigation.vue";
import WikiContent from "~/js/Pages/Project/Wiki/WikiContent.vue";
import useWiki from "@/Composables/useWiki.js";
import WikiEmpty from "~/js/Pages/Project/Wiki/WikiEmpty.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    project: {},
    pages: {},
    page: {},
});

const {editing, adding, form, cancel, addPage, savePage, deletePage} = useWiki();
</script>

<template>
    <Head :title="project.name + ' - Wiki'"/>

    <WikiEmpty v-if="!pages.length && !adding"/>

    <el-row v-else :gutter="30">
        <el-col :lg="6" :xl="6" :md="6" :sm="6" :xs="{span: 24}" style="margin-bottom: 15px;">
            <div style="display: flex; width: 100%; height: 32px; margin-bottom: 15px;">
                <el-button :color="$primaryColor()"
                           @click="addPage">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Add page
                </el-button>
            </div>

            <WikiNavigation :pages="pages" :page="page" :project="project"/>
        </el-col>

        <el-col :lg="18" :xl="18" :md="18" :sm="18" :xs="{span: 24}">
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

            <WikiContent :page="page"/>
        </el-col>
    </el-row>
</template>
