<script setup>
import {router} from '@inertiajs/vue3';
import {useCreateList} from "@/Composables/useList.js";

const {list} = useCreateList({only: ['tasks']});
</script>

<template>
    <el-page-header @back="() => router.visit($route('dashboard'))">
        <template #content>
            <div flex items-center>
                <i class="fas fa-list-check mr-2"></i>
                <span>Tasks</span>
            </div>
        </template>
    </el-page-header>

    <br>

    <el-input
        :clearable="true"
        v-model="list.search"
        placeholder="Type to search"
        size="large"
        class="w-full">
        <template #prefix>
            <i class="fa-solid fa-search"></i>
        </template>
    </el-input>
</template>
