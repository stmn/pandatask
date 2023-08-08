<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import AdminNavigation from "@/Components/Admin/AdminNavigation.vue";
import AdminNotifications from "@/Components/Admin/AdminNotifications.vue";
import {ref} from "vue";

const props = defineProps({
    add: String,
    back: {
        default: route('dashboard')
    },
    search: String,
    title: String,
})

const search = ref(props.search)
</script>

<template>
    <Head :title="$page.props.title"/>

    <AdminNotifications/>

    <el-row :gutter="20">
        <el-col :span="4" style="min-width: 200px; width: 200px; max-width: 200px;">
            <AdminNavigation/>
        </el-col>
        <el-col :span="20" style="max-width: calc(100% - 200px);">
            <el-page-header @back="router.visit(back)" mb-2>
                <template #content>
                    <slot name="title">
                        {{ $page.props.title }}
                    </slot>
                </template>
            </el-page-header>

            <slot name="header">
                <div mt-3 style="display: flex; justify-content: space-between; margin-bottom: 15px;">
                    <Link v-if="add"
                          :href="add"
                          :only="[]"
                          preserve-scroll
                          preserve-state
                          mr-3>
                        <el-button type="success">
                            <i class="fa-solid fa-circle-plus mr-2"></i>Add
                        </el-button>
                    </Link>

                    <el-input v-if="search !== undefined"
                              :prefix-icon="Search"
                              v-model="search"
                              @input="$emit('update:search', $event)"
                              placeholder="Type to search"
                              clearable>
                        <template #prefix>
                            <i class="fa-solid fa-search"></i>
                        </template>
                    </el-input>
                </div>
            </slot>

            <slot></slot>
        </el-col>
    </el-row>
</template>

<style lang="scss" scoped>
</style>
