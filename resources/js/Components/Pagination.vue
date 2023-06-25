<template>
    <div v-if="data.per_page" style="margin-top: 15px;">
        <el-pagination background
                       :page-count="Math.ceil(data.total/data.per_page)"
                       :current-page="data.current_page"
                       :page-sizes="[1, 10, 25, 50, 100]"
                       :hide-on-single-page="false"
                       :page-size="pageSize"
                       :total="data.total"
                       layout="prev, pager, next, sizes, total"
                       @size-change="handleSizeChange"
                       @current-change="change"/>
    </div>
</template>
<script setup>
import {router} from '@inertiajs/vue3'
import {ref} from "vue";

const props = defineProps(['data'])

const pageSize = ref(parseInt(route().params.per_page || 25))

const change = (page) => {
    router.visit(location.href, {
        data: {page}
    })
}

const handleSizeChange = (size) => {
    pageSize.value = size;

    router.visit(location.href, {
        data: {per_page: size, page: 1}
    })
}
</script>
