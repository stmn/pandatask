<script setup>
import {ref} from "vue";
import {useList} from "@/Composables/useList.js";
import {usePage} from "@inertiajs/vue3";


const {list, changeSort, changeOrder} = useList();
</script>

<template>
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
    <br><br>
<!--            {{ JSON.stringify(list.selectedColumns) }}-->
    <div float-right>
    <el-text>
        <div flex items-center style="line-height: 1;">
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
                <i class="fa-solid fa-chevron-down mr-1"></i> Columns
            </span>
                </template>
            </el-popover>

            <el-divider v-if="list.columns.length" direction="vertical"></el-divider>

            <el-dropdown v-if="list.columns.length">
                <template #dropdown>
                    <el-dropdown-menu>
                        <el-dropdown-item
                            v-for="col in list.columns"
                            v-html="col?.label" @click="changeSort(col)">

                        </el-dropdown-item>
                    </el-dropdown-menu>
                </template>

                <span>
                <i class="fa-solid fa-sort mr-1"></i> Sort by <u>{{ list.sort?.label }}</u>
                </span>
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
