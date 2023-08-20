<script setup>
import Editor from "~/js/Components/Forms/EditorInput.vue";
import InputError from "~/js/Components/Forms/InputError.vue";
import {usePage} from "@inertiajs/vue3";
import {computed} from "vue";
import CustomFields from "@/Components/Task/CustomFields.vue";
import UserAvatar from "@/Components/User/UserAvatar.vue";
import UserName from "@/Components/User/UserName.vue";

const props = defineProps({
    modelValue: {
        type: Object,
        required: true
    },
    users: {
        type: Array,
        required: true
    },
    statuses: {
        type: Array,
        required: true
    },
    priorities: {
        type: Array,
        required: true
    },
    custom_fields: {
        type: Array,
        required: false,
        default: () => []
    }
})

const statuses = computed(() => {
    const availableStatuses = props.statuses || usePage().props?.project?.statuses || [];

    return usePage().props.statuses.filter(
        item => availableStatuses.includes(item.id) || item.id === props.modelValue.status_id
    );
})

const priorities = computed(() => {
    const availablePriorities = props.priorities || usePage().props?.project?.priorities || [];

    return usePage().props.priorities.filter(
        item => availablePriorities.includes(item.id) || item.id === props.modelValue.priority_id
    );
})
</script>

<template>
    <el-config-provider size="default">
        <el-row :gutter="10">
            <el-col :lg="8" :xl="8" :md="8" :sm="8" :xs="24">
                <el-form-item label="Subject">
                    <el-input v-model="modelValue.subject" class="focus-me" maxlength="100" show-word-limit/>
                    <InputError :message="modelValue.errors['task.subject']"/>
                </el-form-item>
            </el-col>
            <el-col :lg="8" :xl="8" :md="8" :sm="8" :xs="12">
                <el-form-item label="Tags">
                    <el-select v-model="modelValue.tags"
                               multiple
                               filterable
                               allow-create
                               default-first-option
                               :max-collapse-tags="1"
                               collapse-tags
                               collapse-tags-tooltip
                               placeholder="Select"
                               style="width: 100%;"
                               fit-input-width>
                        <template #prefix>
                            <i class="fas fa-tags"></i>
                        </template>
                    </el-select>
                    <InputError :message="modelValue.errors['task.tags']"/>
                </el-form-item>
            </el-col>
            <el-col :lg="8" :xl="8" :md="8" :sm="8" :xs="12">
                <el-form-item label="Assignees">
                    <el-select v-model="modelValue.assignees"
                               value-key="id"
                               filterable multiple
                               placeholder="Select"
                               :max-collapse-tags="1"
                               collapse-tags
                               collapse-tags-tooltip
                               style="width: 100%;"
                               fit-input-width>
                        <template #prefix>
                            <i class="fas fa-users"></i>
                        </template>
                        <el-option
                            v-for="item in users"
                            :key="item.id"
                            :label="item.full_name"
                            :value="item"
                        >
                            <div flex items-center>
                                <UserAvatar :user="item"/>
                                <UserName :user="item"/>
                            </div>
                        </el-option>
                    </el-select>
                </el-form-item>
            </el-col>
        </el-row>
        <el-row :gutter="10">
            <el-col :lg="24">
                <el-form-item label="Description">
                    <editor v-model="modelValue.description"
                            placeholder="Write description..."
                            prose-style="min-height: 100px; max-height: 200px;"/>
                </el-form-item>
            </el-col>
        </el-row>

        <el-row :gutter="10">
            <el-col :lg="6" :xl="6" :md="6" :sm="6" :xs="24">
                <el-form-item label="Milestone">
                    <el-select v-model="modelValue.milestone_id"
                               filterable
                               clearable
                               default-first-option
                               placeholder="Select"
                               style="width: 100%;"
                               fit-input-width>
                        <el-option
                            v-for="item in $page.props.milestones"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        ></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :lg="12" :xl="12" :md="12" :sm="12" :xs="24">
                <el-row :gutter="10">
                    <el-col :xs="12" :sm="12" :md="12" :lg="12">
                        <el-form-item label="Start date">
                            <el-date-picker v-model="modelValue.start_date" style="width: 100%;"/>
                            <InputError :message="modelValue.errors['task.start_date']"/>
                        </el-form-item>
                    </el-col>
                    <el-col :xs="12" :sm="12" :md="12" :lg="12">
                        <el-form-item label="End date">
                            <el-date-picker v-model="modelValue.end_date" style="width: 100%;"/>
                            <InputError :message="modelValue.errors['task.end_date']"/>
                        </el-form-item>
                    </el-col>
                </el-row>
            </el-col>
        </el-row>

        <el-row :gutter="10">
            <el-col :lg="6" :xl="6" :md="6" :sm="6" :xs="12">
                <el-form-item label="Status">
                    <el-select v-model="modelValue.status_id"
                               placeholder="Select"
                               style="width: 100%;"
                               fit-input-width>
                        <el-option
                            v-for="item in statuses"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        ></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :lg="6" :xl="6" :md="6" :sm="6" :xs="12">
                <el-form-item label="Priority">
                    <el-select v-model="modelValue.priority_id"
                               placeholder="Select"
                               style="width: 100%;"
                               fit-input-width>
                        <el-option
                            v-for="item in priorities"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        ></el-option>
                    </el-select>
                </el-form-item>
            </el-col>
            <el-col :lg="6" :xl="6" :md="6" :sm="6" :xs="12">

            </el-col>
<!--            <el-col :lg="6" :xl="6" :md="6" :sm="6" :xs="12">-->
<!--                <el-form-item label="Private" style="text-align: left;">-->
<!--                    <el-switch v-model="modelValue.private"/>-->
<!--                </el-form-item>-->
<!--            </el-col>-->
        </el-row>

        <CustomFields v-model="modelValue.custom_fields"/>
    </el-config-provider>
</template>

<style lang="scss" scoped>
:deep(.el-form-item__label) {
    margin-bottom: 4px !important;
    font-size: 13px !important;
    font-weight: 600 !important;
}
:deep(.el-select__tags) {
    height: 30px;
}
:deep(.el-form-item--default) {
    margin-bottom: 9px;
}
</style>
