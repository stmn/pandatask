<script setup>
import {Link, router, usePage} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";
import AdminPage from "~/js/Pages/Admin/AdminPage.vue";
import Pagination from "~/js/Components/Common/AppPagination.vue";
import {useCreateList} from "@/Composables/useList.js";
import {Drag, DropList} from "vue-easy-dnd";

defineOptions({layout: [Layout]})

const {list} = useCreateList();

const reorder = ($event) => {
    const {from, to} = $event;
    if (from === to) return;

    const priority = usePage().props.items.data[from].id;
    router.post(route('admin.priorities.reorder', {priority}), {position: to}, {
        preserveScroll: true,
        preserveState: true
    });

    $event.apply(usePage().props.items.data)
};
</script>

<template>
    <AdminPage
        :add="$route('admin.priorities.create')"
        :search="list.search" @update:search="list.search = $event">

        <template #title>
            <i class="fas fa-bars mr-2"></i>
            <span>Priorities</span>
        </template>

        <p>
            <el-text>
                <el-tooltip placement="right" content="You can reorder priorities by dragging and dropping them.">
                    <i class="fas fa-info-circle mr-1"></i>
                </el-tooltip>
                Sortable list:
            </el-text>
        </p>

        <drop-list :items="$page.props.items.data" @reorder="reorder" mode="cut" v-loading="loading">
            <template v-slot:item="{item, reorder}">
                <drag :key="item.id" :data="item">
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
                                <Link :href="$route('admin.priorities.edit', {priority: item.id})" preserve-state
                                      preserve-scroll>
                                    <el-button link mx-1>
                                        <i class="fas fa-edit"/>
                                    </el-button>
                                </Link>

                                <el-popconfirm title="Are you sure to delete this?"
                                               @confirm="router.delete($route('admin.priorities.destroy', {priority: item.id}), {preserveScroll: true})">
                                    <template #reference>
                                        <el-button type="danger" link mx-1>
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
    </AdminPage>
</template>
