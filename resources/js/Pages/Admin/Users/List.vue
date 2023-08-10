<script setup>
import {Link, router} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import AdminPage from "~/js/Pages/Admin/AdminPage.vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import {useCreateList} from "@/Composables/useList.js";
import BaseAvatar from "@/Components/Common/BaseAvatar.vue";

defineOptions({layout: [Layout]})

const {list, changeSort, changeOrder} = useCreateList();
</script>

<template>
    <AdminPage
        :add="$route('admin.users.create')"
        :search="list.search" @update:search="list.search = $event">

        <template #title>
            <i class="fas fa-users mr-2"></i>
            <span>Users</span>
        </template>

        <el-table :data="$page.props.items.data"
                  :default-sort="{ prop: 'id', order: 'descending' }"
                  @sort-change="({order, prop}) => { changeSort({value: prop}); changeOrder(order==='descending'?'desc':'asc'); }"
                  stripe>

            <el-table-column label="ID" prop="id" sortable="custom" width="70"
                             :sort-orders="['descending', 'ascending']"/>

            <el-table-column width="48" align="center">
                <template #default="scope">
                    <div flex items-center>
                        <BaseAvatar :avatar="scope.row.avatar"
                                    :name="scope.row.full_name"
                                    :size="24" />
                    </div>
                </template>
            </el-table-column>

            <el-table-column label="First name" prop="first_name" width="120"/>

            <el-table-column label="Last name" prop="last_name" width="120"/>

            <el-table-column label="Email" prop="email" min-width="260">
                <template #default="scope">
                    <i class="fas fa-envelope mr-1"></i>
                    {{ scope.row.email }}
                </template>
            </el-table-column>

            <el-table-column label="Group" prop="groups" width="80">
                <template #default="scope">
                    <el-tooltip :content="group.name" v-for="group in scope.row.groups">
                        <el-tag :key="group.id" effect="dark" :color="group.color" style="border: 0; margin: 1px;">
                            {{ group.name[0] }}
                        </el-tag>
                    </el-tooltip>
                </template>
            </el-table-column>

            <!-- Actions -->

            <el-table-column align="right" width="140">
                <template #default="scope">
                    <Link :href="$route('admin.users.edit', {user: scope.row.id})" preserve-state preserve-scroll>
                        <el-button link circle mx-1>
                            <i class="fas fa-edit"/>
                        </el-button>
                    </Link>

                    <el-tooltip content="Login as user">
                        <Link :href="$route('admin.users.impersonate', {user: scope.row.id})" method="post">
                            <el-button link mx-1>
                                <i class="fas fa-key"/>
                            </el-button>
                        </Link>
                    </el-tooltip>

                    <el-popconfirm title="Are you sure to delete this?"
                                   @confirm="router.delete($route('admin.users.destroy', {user: scope.row.id}), {preserveScroll: true})">
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
