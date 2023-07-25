<script setup>
import {Drag, DropList} from "vue-easy-dnd";
import {Link, router, usePage} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import Page from "@/Pages/Admin/Page.vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import useList from "@/Composables/useList.js";

defineOptions({layout: [Layout]})

const {query} = useList();

const reorder = ($event) => {
    const {from, to} = $event;
    if (from === to) return;

    const status = usePage().props.items.data[from].id;
    router.post(route('admin.statuses.reorder', {status}), {position: to}, {
        preserveScroll: true,
        preserveState: true
    });

    $event.apply(usePage().props.items.data)
};
</script>

<template>
    <Page>
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
            <Link :href="$route('admin.statuses.create')" style="margin-right: 15px;">
                <el-button type="success">
                    <i class="fa-solid fa-circle-plus mr-2"></i>Add
                </el-button>
            </Link>

            <el-input :prefix-icon="Search" v-model="query.search" placeholder="Type to search" clearable/>
        </div>

        <p>
            <el-text>Sortable list:</el-text>
        </p>

        <drop-list :items="$page.props.items.data" @reorder="reorder" mode="cut" v-loading="loading">
            <template v-slot:item="{item, reorder}">
                <drag :key="item.name" :data="item" @cut="remove(items1, item)">
                    <el-card mb-2 shadow="never" w-full cursor-pointer>
                        <div flex items-center w-full>
                            <div>
                                <i class="fa-solid fa-grip-lines mr-2"></i>
                                {{ item.name }}

                                <el-tag :type="item.color" style="border: 0;" ml-3 effect="dark"
                                        :color="item.color">
                                    {{ item.color }}
                                </el-tag>
                            </div>

                            <div ml-auto>
                                <Link :href="$route('admin.statuses.edit', {status: item.id})">
                                    <el-button :color="$primaryColor()" circle>
                                        <i class="fas fa-edit"/>
                                    </el-button>
                                </Link>

                                <el-popconfirm title="Are you sure to delete this?"
                                               @confirm="router.delete($route('admin.statuses.destroy', {status: item.id}))">
                                    <template #reference>
                                        <el-button type="danger" circle style="margin-left: 5px;">
                                            <i class="fa-solid fa-trash"></i>
                                        </el-button>
                                    </template>
                                </el-popconfirm>
                            </div>
                        </div>
                    </el-card>
                </drag>
            </template>
            <template v-slot:feedback="{data}"></template>
        </drop-list>

        <Pagination :data="$page.props.items"/>
    </Page>
</template>
