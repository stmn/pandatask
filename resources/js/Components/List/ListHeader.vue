<script setup>
import {useList} from "@/Composables/useList.js";
import TasksFilters from "@/Components/Task/TasksFilters.vue";
import {Link} from '@inertiajs/vue3';

const {list, changeSort, changeOrder} = useList();
</script>

<template>
    <div flex>
        <Link preserve-state preserve-scroll :only="['modal']"
              v-if="$can('create tasks', $page.props.project)"
              :href="$route('project.tasks.create', {project: $page.props.project.id})">
            <el-button size="default" type="success" mr-2>
                <i class="fa-solid fa-circle-plus mr-2"></i>
                Add
            </el-button>
        </Link>
        <el-input
            :clearable="true"
            v-model="list.search"
            placeholder="Type to search"
            size="default"
            class="w-full">
            <template #prefix>
                <i class="fa-solid fa-search"></i>
            </template>
        </el-input>
    </div>

    <div float-right mt-3 mb-1>
        <el-text type="info">
            <div flex items-center justify-end>
                <TasksFilters/>

                <el-divider direction="vertical"></el-divider>

                <el-popover v-if="list.columns.length">
                    <template #default>
                        <el-checkbox-group v-model="list.selectedColumns">
                            <div v-for="col in list.columns">
                                <el-checkbox :label="col.value">{{ col?.label }}</el-checkbox>
                            </div>
                        </el-checkbox-group>
                    </template>
                    <template #reference>
                        <span class="cursor-pointer">
                            <i class="fa-solid fa-angle-down mr-2" style="vertical-align: middle;"></i>Columns
                        </span>
                    </template>
                </el-popover>

                <el-divider v-if="list.columns.length" direction="vertical"></el-divider>

                <el-dropdown v-if="list.columns.length" type="info">
                    <template #dropdown>
                        <el-dropdown-menu>
                            <el-dropdown-item
                                v-for="col in list.columns"
                                v-html="col?.label" @click="changeSort(col)">

                            </el-dropdown-item>
                        </el-dropdown-menu>
                    </template>

                    <el-text type="info">
                        <i class="fa-solid fa-sort mr-2"></i>Sort by <u>{{ list.sort?.label }}</u>
                    </el-text>
                </el-dropdown>

                <el-divider v-if="list.columns.length" direction="vertical"></el-divider>

                <!--            {{ list.order }} - -->

                <i @click="changeOrder(list.order==='desc'?'asc':'desc')"
                   :class="`cursor-pointer fa-solid ${list.order==='desc'?'fa-arrow-down-wide-short':'fa-arrow-up-wide-short'} mr-1`"></i>
            </div>
        </el-text>
    </div>
</template>

<style lang="scss" scoped>
.time.not-forced {
    cursor: pointer;

    color: var(--el-text-color-secondary);

    &:hover {
        text-decoration: underline;
    }
}
</style>
