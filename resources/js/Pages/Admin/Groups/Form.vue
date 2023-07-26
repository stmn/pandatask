<script setup>
import Modal from "../../../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import InputError from "~/js/Components/Forms/InputError.vue";
import useAdminForm from "@/Composables/useAdminForm.js";

const props = defineProps({
    group: {
        type: Object,
    },
});

const {close, redirect} = useModal()

const {form, save, isEdit} = useAdminForm({
    values: {
        ...{
            name: '',
            description: '',
            color: '#000000',
        },
        ...props.group,
    },
    singular: 'group',
    plural: 'groups',
    onSuccess: redirect,
});
</script>

<template>
    <Modal width="50%">
        <template #title>{{ isEdit ? 'Edit group' : 'Create a new group' }}</template>

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
                <el-col :sm="24" :lg="24">
                    <el-form-item label="Description" :class="{'is-error':form.errors.description}">
                        <el-input type="textarea" v-model="form.description" rows="3"/>
                        <InputError :message="form.errors.description"/>
                    </el-form-item>
                </el-col>
            </el-row>
        </el-form>

        <template #footer>
          <span class="dialog-footer">
              <el-button @click="close">Cancel</el-button>
              <el-button type="success" @click="save">Save</el-button>
          </span>
        </template>
    </Modal>
</template>
