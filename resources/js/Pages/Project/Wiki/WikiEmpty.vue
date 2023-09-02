<script setup>
import useWiki from "@/Composables/useWiki.js";

const {addPage} = useWiki();
</script>
<template>
    <div text-center
         style="max-width: 600px; border-radius: 10px; margin: 50px auto; border: 1px dashed var(--el-border-color); padding: 50px 15px;">
        <i class="fas fa-file-alt fa-5x"></i>

        <br><br>

        <el-text type="danger" size="large" tag="b">Your project pages list is empty.</el-text>

        <br><br>

        <el-text>
            Here you can create pages and write documentation for your project.
        </el-text>

        <template v-if="$can('create pages', $page.props.project)">
        <br>

        <el-button :color="$primaryColor()"
                   mt-6
                   size="large"
                   @click="addPage">
            <i class="fa-solid fa-circle-plus mr-2"></i>Add page
        </el-button>
        </template>
    </div>
</template>
