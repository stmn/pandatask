<script setup>
import GuestLayout from '~/js/Layouts/GuestLayout.vue';
import InputError from '~/js/Components/Forms/InputError.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: 'admin@demo.com',
    password: 'demo',
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
                        autocomplete="email">
                        <template #prefix>
                            <i class="fas fa-envelope fa-fw"></i>
                        </template>
                    </el-input>
                    <InputError class="mt-2" :message="form.errors.email"/>
                </el-form-item>
                <el-form-item prop="password" style="margin-bottom: 5px;">
                    <el-input
                        v-model="form.password"
                        placeholder="Password"
                        autocomplete="password"
                        type="password"
                    >
                        <template #prefix>
                            <i class="fas fa-lock fa-fw"></i>
                        </template>
                    </el-input>
                    <InputError class="mt-2" :message="form.errors.password"/>
                </el-form-item>

                <el-checkbox v-model="form.remember" label="Remember me"/>

                <el-form-item>
                    <el-button
                        :loading="form.processing"
                        native-type="submit"
                        :color="$primaryColor()"
                        block
                        :disabled="form.processing"
                    >Login
                    </el-button>
                </el-form-item>

                <Link
                    v-if="canResetPassword"
                    :href="$route('password.request')"
                >
                    <div style="text-align: center;">Forgot your password?</div>
                </Link>
            </el-form>
        </el-card>
    </GuestLayout>
</template>
