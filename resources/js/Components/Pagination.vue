<template>
    <div v-if="data.links.length > 3">
        <el-pagination background layout="prev, pager, next"
                       :page-count="Math.ceil(data.total/data.per_page)"
                       :current-page="data.current_page"
                       @current-change="change"/>
<!--        {{ JSON.stringify(data) }} {{ data.total }}-->
    </div>
</template>
<script setup>
import {Link, router} from '@inertiajs/vue3'

const props = defineProps(['data'])

const change = (page) => {
    router.visit(location.href, {
            data: {page}
        })
}
</script>
