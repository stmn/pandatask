<template>
    <el-config-provider size="small">
        <el-tooltip>
            <template #content>
                Adjust your Panda
            </template>
            <el-card class="color-panel">
                <small style="margin-bottom: 5px; display: block;">
                    <b><i class="fa-solid fa-palette mr-2"></i>Theme:</b>
                </small>
                <el-form label-suffix=":" label-width="100" label-position="left">
                    <el-form-item label="Brand color" style="margin: 0;">
                        <el-color-picker v-model="color" @change="changeColor" @active-change="activeChange"/>
                    </el-form-item>
                    <el-form-item label="Dark mode" style="margin: 0;">
                        <el-switch v-model="isDark" @change="toggleDark"/>
                    </el-form-item>
                </el-form>
            </el-card>
        </el-tooltip>
    </el-config-provider>
</template>

<script setup>
import {useCssVar, useStorage} from "@vueuse/core";
import {ref} from "vue";
import {TinyColor} from "@ctrl/tinycolor";
import {useDark, useToggle} from "@vueuse/core";
import Logo from "@/Components/Logo.vue";

const isDark = useDark()
const toggleDark = useToggle(isDark)

const color = useStorage('theme.primary_color', getComputedStyle(document.body).getPropertyValue('--el-color-primary'))
const originalColor = color.value;

console.log(color.value)
//
// const isDark = () => {
//     return new TinyColor(useCssVar('--el-color-primary', document.body).value).isDark()
// }

const changeColor = (color) => {
    if (!color) {
        color.value = originalColor
    }
    document.body.style.setProperty('--el-color-primary', color)

    const isDark = new TinyColor(useCssVar('--el-color-primary', document.body).value).isDark();
    console.log({isDark})
    if(isDark) {
        document.body.style.setProperty('--text-color', 'var(--el-color-white)')
    } else {
        document.body.style.setProperty('--text-color', 'var(--el-color-black)')
    }
}

const activeChange = (color) => {
    changeColor(color)
}

changeColor(color.value);
</script>

<style lang="scss">
.color-panel {
    opacity: 0.5;
    position: fixed;
    bottom: 15px;
    left: 15px;
    z-index: 100;

    &:hover {
        opacity: 1;
    }

    .el-card__body {
        padding: 10px;
    }
}
</style>
