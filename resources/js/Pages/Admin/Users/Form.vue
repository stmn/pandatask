<script setup>
import Modal from "../../../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import InputError from "~/js/Components/Forms/InputError.vue";
import useAdminForm from "@/Composables/useAdminForm.js";

const props = defineProps({
    user: Object,
    groups: Array,
});

const {close, redirect} = useModal()

const {form, save, isEdit} = useAdminForm({
    values: {
        ...{
            first_name: '',
            last_name: '',
            email: '',
            phone: '',
            job_title: '',
            password: '',
            confirm_password: '',
            groups: [],
            send_welcome_email: true,
        },
        ...props.user
    },
    singular: 'user',
    plural: 'users',
    onSuccess: redirect,
});

const generateRandomPassword = () => {
    const length = 8;
    const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    let retVal = "";
    for (let i = 0, n = charset.length; i < length; ++i) {
        retVal += charset.charAt(Math.floor(Math.random() * n));
    }
    form.password = retVal;
    form.confirm_password = retVal;
}
</script>

<template>
    <Modal>
        <template #title>{{ isEdit ? 'Edit user' : 'Create a new user' }}</template>

        <el-form label-width="140px" label-position="top">
            <el-row :gutter="10">
                <el-col :sm="12" :lg="6">
                    <el-form-item label="First name" :class="{'is-error':form.errors.first_name}">
                        <el-input v-model="form.first_name">
                            <template #prefix>
                                <i class="fa-solid fa-user"></i>
                            </template>
                        </el-input>
                        <InputError :message="form.errors.first_name"/>
                    </el-form-item>
                </el-col>
                <el-col :sm="12" :lg="6">
                    <el-form-item label="Last name" :class="{'is-error':form.errors.last_name}">
                        <el-input v-model="form.last_name">
                            <template #prefix>
                                <i class="fa-solid fa-user"></i>
                            </template>
                        </el-input>
                        <InputError :message="form.errors.last_name"/>
                    </el-form-item>
                </el-col>
                <el-col :sm="12" :lg="6">
                    <el-form-item label="Email" :class="{'is-error':form.errors.email}">
                        <el-input v-model="form.email">
                            <template #prefix>
                                <i class="fa-solid fa-envelope"></i>
                            </template>
                        </el-input>
                        <InputError :message="form.errors.email"/>
                    </el-form-item>
                </el-col>
                <el-col :sm="12" :lg="6">
                    <el-form-item label="Phone" :class="{'is-error':form.errors.phone}">
                        <el-input v-model="form.phone">
                            <template #prefix>
                                <i class="fa-solid fa-phone"></i>
                            </template>
                        </el-input>
                        <InputError :message="form.errors.phone"/>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row :gutter="10">
                <el-col :sm="12" :lg="12">
                    <el-form-item label="Groups" :class="{'is-error':form.errors.groups}">
                        <el-select v-model="form.groups"
                                   value-key="id"
                                   filterable multiple
                                   placeholder="Select"
                                   style="width: 100%;"
                                   fit-input-width>
                            <template #prefix>
                                <i class="fa-solid fa-users"></i>
                            </template>
                            <el-option
                                v-for="item in groups"
                                :key="item"
                                :label="item.name"
                                :value="item"
                            ></el-option>
                        </el-select>
                    </el-form-item>
                </el-col>
                <el-col :sm="12" :lg="12">
                    <el-form-item label="Job title" :class="{'is-error':form.errors.job_title}">
                        <el-input v-model="form.job_title">
                            <template #prefix>
                                <i class="fa-solid fa-briefcase"></i>
                            </template>
                        </el-input>
                        <InputError :message="form.errors.job_title"/>
                    </el-form-item>
                </el-col>
            </el-row>

            <el-row :gutter="10">
                <el-col :sm="12" :lg="12">
                    <el-form-item label="Password" :class="{'is-error':form.errors.password}">
                        <template #label>
                            Password &nbsp;
                            <el-button type="link" size="small" @click="generateRandomPassword">
                                <i class="fa-solid fa-random mr-2"></i> Generate password
                        </el-button>
                        </template>
                        <el-input v-model="form.password" type="password">
                            <template #prefix>
                                <i class="fa-solid fa-lock"></i>
                            </template>
                        </el-input>
                        <InputError :message="form.errors.password"/>
                    </el-form-item>
                </el-col>
                <el-col :sm="12" :lg="12">
                    <el-form-item label="Confirm password" :class="{'is-error':form.errors.confirm_password}">
                        <el-input v-model="form.confirm_password" type="password">
                            <template #prefix>
                                <i class="fa-solid fa-lock"></i>
                            </template>
                        </el-input>
                        <InputError :message="form.errors.confirm_password"/>
                    </el-form-item>
                </el-col>
            </el-row>

            <template v-if="!form.id">
            <el-checkbox v-model="form.send_welcome_email"
                         label="Send email to user with login details"></el-checkbox>
            </template>
        </el-form>

        <template #footer>
          <span class="dialog-footer">
              <el-button @click="close">Cancel</el-button>
              <el-button type="success" @click="save">Save</el-button>
          </span>
        </template>
    </Modal>
</template>
