<script setup>
import {Link, router} from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import Page from "@/Pages/Admin/Page.vue";
import Pagination from "@/Components/Pagination.vue";
import useList from "@/Composables/useList.js";

defineOptions({layout: [Layout]})

const {query, handleSortChange} = useList();
</script>

<template>
    <Page>
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <Link :href="$route('admin.priorities.create')" style="margin-right: 15px;">
                <el-button type="success">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Add
                </el-button>
            </Link>

            <el-input :prefix-icon="Search" v-model="query.search" placeholder="Type to search" clearable/>
        </div>

        <el-table :data="$page.props.items.data"
                  :default-sort="{ prop: 'id', order: 'descending' }"
                  @sort-change="handleSortChange"
                  stripe
                  style="width: 100%">
            <el-table-column label="ID" prop="id" sortable="custom" width="70"/>
            <el-table-column label="Name" prop="name" />
            <el-table-column label="Color" prop="color" width="100">
                <template #default="scope">
                    <el-tag :type="scope.row.color" style="border: 0;" effect="dark" :color="scope.row.color">
                        {{ scope.row.color }}
                    </el-tag>
                </template>
            </el-table-column>
            <el-table-column align="right" width="100">
                <template #default="scope">
                    <Link :href="$route('admin.priorities.edit', {priority: scope.row.id})">
                        <el-button :color="$primaryColor()" circle>
                            <i class="fas fa-edit" />
                        </el-button>
                    </Link>
                    <el-popconfirm title="Are you sure to delete this?"
                                   @confirm="router.delete($route('admin.priorities.destroy', {priority: scope.row.id}))">
                        <template #reference>
                            <el-button type="danger" circle style="margin-left: 5px;">
                                <i class="fa-solid fa-trash"></i>
                            </el-button>
                        </template>
                    </el-popconfirm>
                </template>
            </el-table-column>
        </el-table>

        <Pagination :data="$page.props.items"/>
    </Page>
</template>
