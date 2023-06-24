<script setup>
import {useForm} from '@inertiajs/vue3';
import {ref} from 'vue';
import InputError from "@/Components/InputError.vue";
import {ElMessage} from "element-plus";
import {InfoFilled} from "@element-plus/icons-vue";

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => {
            ElMessage.success('Password updated.')
        },
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <h3>Update Password</h3>

        <el-alert :show-icon="InfoFilled" type="warning" :closable="false">
            Ensure your account is using a long, random password to stay secure.
        </el-alert>

        <br>

        <el-card shadow="never">
            <el-form @submit.prevent="updatePassword" label-position="left" label-width="180">
                <el-form-item label="Current password">
                    <el-input
                        ref="currentPasswordInput"
                        v-model="form.current_password"
                        type="password"
                        autocomplete="current-password"
                    />
                    <InputError :message="form.errors.current_password"/>
                </el-form-item>

                <el-form-item label="New password">
                    <el-input
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        autocomplete="new-password"
                    />
                    <InputError :message="form.errors.password"/>
                </el-form-item>

                <el-form-item label="Password confirmation">
                    <el-input
                        v-model="form.password_confirmation"
                        type="password"
                        autocomplete="new-password"
                    />
                    <InputError :message="form.errors.password_confirmation"/>
                </el-form-item>

                <el-form-item>
                    <el-button type="primary" @click="updatePassword" :disabled="form.processing">Save</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </section>
</template>
