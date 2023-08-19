<script setup>
import {useTimeAgo} from "@vueuse/core";
import useWiki from "@/Composables/useWiki.js";
import EditorContent from "@/Components/Forms/EditorContent.vue";
import Editor from "@/Components/Forms/EditorInput.vue";
import InputError from "@/Components/Forms/InputError.vue";

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

const {editing, adding, form} = useWiki();
</script>
<template>
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
</template>

