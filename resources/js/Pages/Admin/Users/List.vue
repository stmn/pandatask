<script setup>
import {Head, Link, router} from '@inertiajs/vue3';
import {reactive, ref} from "vue";
import Layout from "@/Layouts/Layout.vue";
import {watchDebounced} from "@vueuse/core";
import {Delete, Edit} from "@element-plus/icons-vue";

defineOptions({layout: [Layout]})

// const props = defineProps({
//     items: {
//         required: true,
//     },
//
// });

const query = reactive({
    search: (new URLSearchParams(window.location.search)).get('search'),
    sort: (new URLSearchParams(window.location.search)).get('sort'),
})

watchDebounced(query, () => {
        router.reload({data: query})
    },
    {debounce: 200, maxWait: 500},
)

const multipleTableRef = ref()
const multipleSelection = ref([])
const toggleSelection = (rows) => {
    if (rows) {
        rows.forEach((row) => {
            multipleTableRef.value.toggleRowSelection(row, undefined)
        })
    } else {
        multipleTableRef.value.clearSelection()
    }
}

const handleSelectionChange = (val) => {
    multipleSelection.value = val
}

const handleSortChange = (orderData) => {
    query.sort = (orderData.order === 'ascending' ? '' : '-') + orderData.prop
    router.reload({data: query})
}
</script>

<template>
    <Head :title="$page.props.title"/>

    <el-row class="tac" :gutter="20">
        <el-col :span="4">
            <el-menu
                default-active="2"
                active-text-color="var(--el-color-success)"
                background-color="var(--el-color-primary-light-9)"
                text-color="var(--el-text-color-primary)"
                style="border-radius: 5px;"
            >
                <Link :href="route('admin.users.index')">
                <el-menu-item index="2">
                    <el-icon><icon-menu /></el-icon>
                    <span>Users</span>
                </el-menu-item>
                </Link>
                <Link :href="route('admin.users.index')">
                <el-menu-item index="3">
                    <el-icon><icon-menu /></el-icon>
                    <span>Groups</span>
                </el-menu-item>
                </Link>
                <Link :href="route('admin.users.index')">
                <el-menu-item index="4">
                    <el-icon><icon-menu /></el-icon>
                    <span>Settings</span>
                </el-menu-item>
                </Link>
            </el-menu>
        </el-col>
        <el-col :span="20">
            <el-page-header @back="router.visit(route('dashboard'))"
                            :content="$page.props.title"></el-page-header>

            <br>

            <el-input v-model="query.search" placeholder="Type to search" clearable/>

            <br><br>

            <div style="display: flex; justify-content: space-between;">
                <el-dropdown>
                    <el-button type="primary">
                        Selected
                        <el-icon class="el-icon--right">
                            <arrow-down/>
                        </el-icon>
                    </el-button>
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item>Remove</el-dropdown-item>
                        </el-dropdown-menu>
                    </template>
                </el-dropdown>
                <Link :href="route('admin.users.create')">
                    <el-button>
                        Add
                    </el-button>
                </Link>
            </div>

            <el-table ref="multipleTableRef"
                      :data="$page.props.items"
                      :default-sort="{ prop: 'id', order: 'descending' }"
                      @selection-change="handleSelectionChange"
                      @sort-change="handleSortChange"
                      stripe
                      row-key="id"
                      key="id"
                      style="width: 100%">
                <el-table-column type="selection" width="60"/>
                <el-table-column label="ID" prop="id" sortable="custom" width="85"/>
                <el-table-column label="First name" prop="first_name"/>
                <el-table-column label="Last name" prop="last_name"/>
                <el-table-column label="Email" prop="email"/>
                <el-table-column label="Group" prop="groups">
                    <template #default="scope">
                        <el-tag v-for="group in scope.row.groups" :key="group.id" effect="dark" :color="group.color" style="border: 0; margin: 1px;">
                            {{ group.name }}
                        </el-tag>
                    </template>
                </el-table-column>
                <!--        <el-table-column label="Phone" prop="phone"/>-->
                <!--        <el-table-column label="Job title" prop="job_title"/>-->
                <!--        <el-table-column label="New passowrd" prop="password"/>-->
                <!--        <el-table-column label="Confirm passowrd" prop="confirm_password"/>-->
                <el-table-column align="right">
                    <template #default="scope">
                        <Link :href="route('admin.users.edit', {user: scope.row.id})">
                            <el-button type="primary" :icon="Edit" circle />
                        </Link>
                        &nbsp;
                        <el-popconfirm title="Are you sure to delete this?"
                                       @confirm="router.delete(route('admin.users.destroy', {user: scope.row.id}))">
                            <template #reference>
                                <el-button type="danger" :icon="Delete" circle />
                            </template>
                        </el-popconfirm>
                    </template>
                </el-table-column>
            </el-table>
        </el-col>
    </el-row>

</template>

<style>
.el-menu-item:hover {
    //background-color: var(--el-color-success-light-5);
    background-color: transparent;
    border-radius: 5px;
}
</style>
