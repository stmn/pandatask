<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {Lock, User} from "@element-plus/icons-vue";

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in"/>

        <el-card class="auth-card">
            <h2>Login</h2>

            <el-alert type="success" v-if="status" :closable="false" style="margin-bottom: 15px;">
                {{ status }}
            </el-alert>

            <el-form @submit.native.prevent="submit">
                <el-form-item prop="email">
                    <el-input
                        v-model="form.email"
                        placeholder="E-mail"
                        autocomplete="email"
                        :prefix-icon="User"></el-input>
                    <InputError class="mt-2" :message="form.errors.email"/>
                </el-form-item>
                <el-form-item prop="password" style="margin-bottom: 5px;">
                    <el-input
                        v-model="form.password"
                        placeholder="Password"
                        autocomplete="password"
                        :prefix-icon="Lock"
                        type="password"
                    ></el-input>
                    <InputError class="mt-2" :message="form.errors.password"/>
                </el-form-item>

                <el-checkbox v-model="form.remember" label="Remember me"/>

                <el-form-item>
                    <el-button
                        :loading="form.processing"
                        type="primary"
                        native-type="submit"
                        block
                        :disabled="form.processing"
                    >Login
                    </el-button>
                </el-form-item>

                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                >
                    <center>Forgot your password?</center>
                </Link>
            </el-form>
        </el-card>
    </GuestLayout>
</template>
