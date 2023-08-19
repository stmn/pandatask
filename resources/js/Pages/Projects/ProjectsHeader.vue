<script setup>
import {Link, router} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import {useCreateList} from "@/Composables/useList.js";

defineOptions({layout: [Layout]})

const props = defineProps({
    projects: {
        required: true
    },
    search: {
        type: String,
        required: false,
        default: ''
    },
});

const {list} = useCreateList({only: ['projects']});
</script>

<template>
    <el-page-header @back="() => router.visit($route('dashboard'))">
        <template #content>
            <div style="display: flex; align-items: center;">
                <i class="fa-solid fa-rectangle-list mr-2"></i>
                <span>Projects</span>
            </div>
        </template>
        <template #extra>
            <div class="flex items-center">
                <Link preserve-state preserve-scroll :only="['modal', 'flash']" :href="$route('projects.create')">
                    <el-button type="success">
                        <i class="fa-solid fa-circle-plus mr-2"></i> Create
                    </el-button>
                </Link>
            </div>
        </template>
    </el-page-header>

    <br>

    <el-input
        :clearable="true"
        v-model="list.search"
        placeholder="Type to search"
        size="large"
        autofocus
        class="w-full">
        <template #prefix>
            <i class="fa-solid fa-search"></i>
        </template>
    </el-input>
</template>
