<script setup>
import {Head} from '@inertiajs/vue3';
import ProjectLayout from "~/js/Layouts/ProjectLayout.vue";
import WikiNavigation from "~/js/Pages/Project/Wiki/WikiNavigation.vue";
import WikiContent from "~/js/Pages/Project/Wiki/WikiContent.vue";
import useWiki from "@/Composables/useWiki.js";
import HeroCard from "@/Components/Common/HeroCard.vue";
import Layout from "~/js/Layouts/Layout.vue";

defineOptions({layout: [Layout, ProjectLayout]})

const props = defineProps({
    activeTab: String,
    project: {},
    pages: {},
    page: {},
});

const {editing, adding, form, cancel, addPage, savePage, deletePage} = useWiki();
</script>

<template>
    <Head :title="project.name + ' - Wiki'"/>

<!--    <ProjectLayout :project="project" :active-tab="activeTab">-->
        <HeroCard v-if="!pages.length && !adding" title="Pages not found"
                  description="Here you can create pages and write documentation for your project."
                  type="not-found">
            <template #buttons>
                <el-button type="primary"
                           mt-6
                           size="large"
                           @click="addPage">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Add page
                </el-button>
            </template>
        </HeroCard>

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

                    <el-popconfirm v-if="form.title !== 'Home'" title="Are you sure to delete this?"
                                   @confirm="deletePage"
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
<!--    </ProjectLayout>-->
</template>
