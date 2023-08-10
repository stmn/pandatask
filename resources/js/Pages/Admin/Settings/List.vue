<script setup>
import Layout from "~/js/Layouts/Layout.vue";
import AdminPage from "~/js/Pages/Admin/AdminPage.vue";
import {useForm} from "@inertiajs/vue3";
import ImageUpload from "@/Components/Common/ImageUpload.vue";

defineOptions({layout: [Layout]})

const props = defineProps({
    settings: {
        type: Object,
        default: () => ({})
    }
})

const form = useForm(props.settings);

const handleFaviconChange = (newIcon) => {
    document.querySelector("link[rel*='icon']").href = newIcon;
}

function submit() {
    form.post(route('admin.settings.update'))
}
</script>

<template>
    <AdminPage>
        <template #title>
            <i class="fas fa-cog mr-2"></i>
            <span>Settings</span>
        </template>

        <el-form label-position="left" label-width="200">
            <el-form-item label="Name">
                <el-input v-model="form.brand_name" maxlength="20" show-word-limit/>
                <small>
                    <i class="fas fa-info-circle mr-1"></i> Name will be displayed in the page title or emails.
                </small>
            </el-form-item>

            <el-form-item label="Color">
                <el-color-picker v-model="form.brand_color"/>
            </el-form-item>

            <el-form-item>
                <template #label>
                    <div style="display: block;">
                        <span>Logotype</span>
                        <div style="line-height: 20px; font-size: 11px;">
                            Max size: 256kb, png
                        </div>
                    </div>
                </template>

                <ImageUpload
                    :imageUrl="settings.brand_logo"
                    :uploadUrl="$route('admin.settings.upload-logo')"
                    :deleteUrl="$route('admin.settings.delete-logo')"
                />
            </el-form-item>

            <br>

            <el-form-item>
                <template #label>
                    <div style="display: block;">
                        <span>Favicon</span>
                        <div style="line-height: 20px; font-size: 11px;">
                            Max size: 64kb, png
                        </div>
                    </div>
                </template>

                <ImageUpload
                    :imageUrl="settings.brand_favicon"
                    :uploadUrl="$route('admin.settings.upload-favicon')"
                    :deleteUrl="$route('admin.settings.delete-favicon')"
                    @uploaded="handleFaviconChange"
                />
            </el-form-item>

            <el-button mt-5 type="primary" @click="submit">
                Save
            </el-button>
        </el-form>
    </AdminPage>
</template>
