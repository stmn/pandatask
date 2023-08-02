<script setup>
import {Link, router} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import AdminPage from "~/js/Pages/Admin/AdminPage.vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import {useCreateList} from "@/Composables/useList.js";

defineOptions({layout: [Layout]})

const {list, changeSort, changeOrder} = useCreateList();
</script>

<template>
    <AdminPage
        :add="$route('admin.groups.create')"
        :search="list.search" @update:search="list.search = $event">

        <template #title>
            <i class="fas fa-user-group mr-2"></i>
            <span>Groups</span>
        </template>

        <el-table :data="$page.props.items.data"
                  :default-sort="{ prop: 'id', order: 'ascending' }"
                  @sort-change="({order, prop}) => { changeSort({value: prop}); changeOrder(order==='descending'?'desc':'asc'); }"
                  stripe>

            <el-table-column label="ID" prop="id" sortable="custom" width="70"
                             :sort-orders="['descending', 'ascending']"/>

            <el-table-column label="Name" prop="name" width="160"/>

            <el-table-column label="Description" prop="description" min-width="260"/>

            <el-table-column label="Color" prop="color" width="100">
                <template #default="scope">
                    <el-tag :type="scope.row.color" style="border: 0;" effect="dark" :color="scope.row.color">
                        {{ scope.row.color }}
                    </el-tag>
                </template>
            </el-table-column>

            <!-- Actions -->

            <el-table-column align="right" width="100">
                <template #default="scope">
                    <Link :href="$route('admin.groups.edit', {group: scope.row.id})" preserve-state preserve-scroll>
                        <el-button link mx-1>
                            <i class="fas fa-edit"/>
                        </el-button>
                    </Link>
                    <el-popconfirm v-if="scope.row?.can?.delete" title="Are you sure to delete this?"
                                   @confirm="router.delete($route('admin.groups.destroy', {group: scope.row.id}), {preserveScroll: true})">
                        <template #reference>
                            <el-button type="danger" link mx-1>
                                <i class="fa-solid fa-trash"></i>
                            </el-button>
                        </template>
                    </el-popconfirm>
                </template>
            </el-table-column>
        </el-table>

        <Pagination :data="$page.props.items"/>
    </AdminPage>
</template>
