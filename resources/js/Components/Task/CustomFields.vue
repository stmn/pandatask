<template>
<!--    {{ JSON.stringify(usePage().props.project.custom_fields) }}-->
<!--    <hr>-->
<!--    {{ JSON.stringify(modelValue) }}-->
<!--    <hr>-->

    <el-row :gutter="10">
        <el-col v-for="field in projectCustomFields"
                :key="key"
                :lg="4" :md="6" :sm="6" :xs="12">
            <el-form-item :label="field.label">
                <template v-if="field.type === 'text'">
                    <el-input v-model="modelValue[field.key]" placeholder="Enter"></el-input>
                </template>
                <template v-else-if="field.type === 'select'">
                    <el-select v-model="modelValue[field.key]"
                               placeholder="Select"
                               style="width: 100%;"
                               fit-input-width>
                        <el-option
                            v-for="item in $page.props.project?.custom_fields?.find(item => item.key === field.key).options"
                            :key="item"
                            :label="item"
                            :value="item"
                        ></el-option>
                    </el-select>
                </template>
                <template v-else-if="field.type === 'boolean'">
                    <el-switch v-model="modelValue[field.key]"/>
                </template>
                <template v-else-if="field.type === 'number'">
                    <el-input-number v-model="modelValue[field.key]" style="width: 100%;" />
                </template>
                <template v-else-if="field.type === 'date'">
                    <el-date-picker v-model="modelValue[field.key]" style="width: 100%;" />
                </template>
            </el-form-item>
        </el-col>
    </el-row>
</template>

<script setup>
import {usePage} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    modelValue: {
        type: Object,
        default: () => ({})
    }
});

const projectCustomFields = usePage().props.project?.custom_fields;

// const customFields = ref({});
// projectCustomFields.forEach(item => {
//     if (item.type === 'select') {
//         item.options = item.options.split(',');
//     }
// });

// const determineType = (key) => {
//     const field = projectCustomFields.find(item => item.key === key);
//
//     if (!field) {
//         return false;
//     }
//
//     return field.type;
// }
</script>
