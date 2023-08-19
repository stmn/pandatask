<script setup>
import DashboardSettings from "~/js/Pages/Dashboard/DashboardSettings.vue";
import {ref} from "vue";

const props = defineProps({
    settings: Object
});

const showSettings = ref(false);
</script>

<template>
    <!-- Customization Button -->
    <el-popover :visible="showSettings" trigger="click" placement="left" width="300">
        <template #default>
            <DashboardSettings :settings="settings" @close="showSettings = false"/>
        </template>
        <template #reference>
            <el-button link @click="showSettings = !showSettings">
                <i class="fas fa-gear hover-rotate" style="cursor: pointer; font-size: 24px;"/>
            </el-button>
        </template>
    </el-popover>
</template>
