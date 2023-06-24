<script lang="ts" setup>
import {useForm} from "@inertiajs/vue3"
import Modal from "../../../Layouts/Modal.vue"
import {useModal} from "momentum-modal";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    user: {
        type: Object,
    },
    groups: {
        type: Array,
    },
});

const {close, redirect} = useModal()

const isEdit = props.user?.id;

const initialValues = {
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    job_title: '',
    password: '',
    confirm_password: '',
    groups: [],
};

const form = useForm({
    ...initialValues,
    ...(props.user || {}),
});

const save = () => {
    const routeName = isEdit ? 'update' : 'store';
    const routeParams = { user: props.user?.id };
    const onSuccess = () => redirect();

    form.submit(isEdit ? 'patch' : 'post', route(`admin.users.${routeName}`, routeParams), { onSuccess });
};

console.log(props.groups)
</script>

<template>
    <Modal>
        <template #title>{{ isEdit ? 'Edit user' : 'Create a new user' }}</template>

        <el-config-provider>
            <el-form label-width="140px" label-position="top">
                <el-row :gutter="10">
                    <el-col :sm="12" :lg="6">
                        <el-form-item label="First name" :class="{'is-error':form.errors.first_name}">
                            <el-input v-model="form.first_name"/>
                            <InputError :message="form.errors.first_name"/>
                        </el-form-item>
                    </el-col>
                    <el-col :sm="12" :lg="6">
                        <el-form-item label="Last name" :class="{'is-error':form.errors.last_name}">
                            <el-input v-model="form.last_name"/>
                            <InputError :message="form.errors.last_name"/>
                        </el-form-item>
                    </el-col>
                    <el-col :sm="12" :lg="6">
                        <el-form-item label="Email" :class="{'is-error':form.errors.email}">
                            <el-input v-model="form.email"/>
                            <InputError :message="form.errors.email"/>
                        </el-form-item>
                    </el-col>
                    <el-col :sm="12" :lg="6">
                        <el-form-item label="Phone" :class="{'is-error':form.errors.phone}">
                            <el-input v-model="form.phone"/>
                            <InputError :message="form.errors.phone"/>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-row :gutter="10">
                    <el-col :sm="12" :lg="12">
                        <el-form-item label="Groups" :class="{'is-error':form.errors.groups}">
                            {{ JSON.stringify(form.groups) }}
<hr>
                            {{ JSON.stringify(groups) }}

                            <el-select v-model="form.groups"
                                       value-key="id"
                                       filterable multiple
                                       placeholder="Select"
                                       style="width: 100%;"
                                       fit-input-width>
                                <el-option
                                    v-for="item in groups"
                                    :key="item"
                                    :label="item.name"
                                    :value="item"
                                >
                                </el-option>
                            </el-select>
                        </el-form-item>
                    </el-col>
                    <el-col :sm="12" :lg="12">
                        <el-form-item label="Job title" :class="{'is-error':form.errors.job_title}">
                            <el-input v-model="form.job_title"/>
                            <InputError :message="form.errors.job_title"/>
                        </el-form-item>
                    </el-col>
                </el-row>

                <el-row :gutter="10">
                    <el-col :sm="12" :lg="12">
                        <el-form-item label="Password" :class="{'is-error':form.errors.password}">
                            <el-input v-model="form.password"/>
                            <InputError :message="form.errors.password"/>
                        </el-form-item>
                    </el-col>
                    <el-col :sm="12" :lg="12">
                        <el-form-item label="Confirm password" :class="{'is-error':form.errors.confirm_password}">
                            <el-input v-model="form.confirm_password"/>
                            <InputError :message="form.errors.confirm_password"/>
                        </el-form-item>
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
