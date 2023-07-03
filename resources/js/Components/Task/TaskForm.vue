<script setup>
import User from "@/Components/User.vue";

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
})

console.log(111, props.modelValue)

</script>

<template>
    <el-row :gutter="15">
        <el-col :lg="12" :xl="12" :md="12" :sm="12" :xs="24">
            <el-form-item label="Subject">
                <el-input v-model="modelValue.subject" />
            </el-form-item>
        </el-col>
        <el-col :lg="12" :xl="12" :md="12" :sm="12" :xs="24">
            <el-form-item label="Tags">
                <el-select v-model="modelValue.tags"
                           multiple
                           filterable
                           allow-create
                           default-first-option
                           placeholder="Select"
                           style="width: 100%;"
                           fit-input-width>
                    <!--                                                <el-option-->
                    <!--                                                    v-for="item in value.tags"-->
                    <!--                                                    :key="item.id"-->
                    <!--                                                    :label="item"-->
                    <!--                                                    :value="item"-->
                    <!--                                                ></el-option>-->
                </el-select>
            </el-form-item>
        </el-col>
    </el-row>
    <el-row :gutter="15">
        <el-col :lg="24">
            <el-form-item label="Description">
                <el-input v-model="modelValue.description" type="textarea" rows="1" :autosize="{ minRows: 1, maxRows: 4 }" />
            </el-form-item>
        </el-col>
    </el-row>
    <el-row :gutter="15">
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
                    <el-option
                        v-for="item in users"
                        :key="item.id"
                        :label="item.full_name"
                        :value="item"
                    >
                        <User :user="item" disable-popover/>
                    </el-option>
                </el-select>
            </el-form-item>
        </el-col>
        <el-col :lg="6" :xl="6" :md="6" :sm="6" :xs="12">
            <el-form-item label="Private" style="text-align: left;">
                <el-switch v-model="modelValue.private" />
            </el-form-item>
        </el-col>
    </el-row>
</template>
