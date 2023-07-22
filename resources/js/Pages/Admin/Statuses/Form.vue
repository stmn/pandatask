<script setup>
import Modal from "../../../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import InputError from "~/js/Components/Forms/InputError.vue";
import useAdminForm from "@/Composables/useAdminForm.js";

const props = defineProps({
    status: {
        type: Object,
    },
});

const {close, redirect} = useModal()

const {form, save, isEdit} = useAdminForm({
    values: {
        ...{
            name: '',
            color: '#000000',
        },
        ...props.status,
    },
    singular: 'status',
    plural: 'statuses',
    onSuccess: redirect,
});
</script>

<template>
    <Modal width="50%">
        <template #title>{{ isEdit ? 'Edit status' : 'Create a new status' }}</template>

        <el-config-provider>
            <el-form label-width="140px" label-position="top">
                <el-row :gutter="10">
                    <el-col :sm="24" :lg="24">
                        <div style="display: flex; width: 100%;">
                            <el-form-item label="Name" :class="{'is-error':form.errors.name}" style="width: 100%;">
                                <el-input v-model="form.name"/>
                                <InputError :message="form.errors.name"/>
                            </el-form-item>
                            <el-form-item label="Color" :class="{'is-error':form.errors.color}"
                                          style="width: 70px; margin-left: 10px;">
                                <el-color-picker v-model="form.color"/>
                                <InputError :message="form.errors.color"/>
                            </el-form-item>
                        </div>
                    </el-col>
                </el-row>
            </el-form>
        </el-config-provider>

        <template #footer>
          <span class="dialog-footer">
              <el-button @click="close">Cancel</el-button>
              <el-button type="success" @click="save">Save</el-button>
          </span>
        </template>
    </Modal>
</template>
