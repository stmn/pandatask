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
            <Link :href="$route('admin.users.create')" style="margin-right: 15px;">
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
            <el-table-column label="First name" prop="first_name"/>
            <el-table-column label="Last name" prop="last_name"/>
            <el-table-column label="Email" prop="email"/>
            <el-table-column label="Group" prop="groups" width="140">
                <template #default="scope">
                    <el-tooltip :content="group.name" v-for="group in scope.row.groups">
                        <el-tag :key="group.id" effect="dark" :color="group.color" style="border: 0; margin: 1px;">
                            {{ group.name[0] }}
                        </el-tag>
                    </el-tooltip>
                </template>
            </el-table-column>
            <el-table-column align="right" width="140">
                <template #default="scope">
                    <Link :href="$route('admin.users.edit', {user: scope.row.id})">
                        <el-button :color="$primaryColor()" circle>
                            <i class="fas fa-edit" />
                        </el-button>
                    </Link>

                    <Link :href="$route('admin.users.impersonate', {user: scope.row.id})" method="post">
                        <el-button :color="$primaryColor()" circle>
                            <i class="fas fa-key" />
                        </el-button>
                    </Link>
                    &nbsp;
                    <el-popconfirm title="Are you sure to delete this?"
                                   @confirm="router.delete($route('admin.users.destroy', {user: scope.row.id}))">
                        <template #reference>
                            <el-button type="danger" circle>
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
