<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import {Head, useForm} from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password"/>

        <el-card class="auth-card">
            <el-form @submit.native.prevent="submit" label-position="top">
                <el-form-item label="Email">
                    <el-input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError :message="form.errors.email"/>
                </el-form-item>

                <el-form-item label="Password">
                    <el-input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />

                    <InputError :message="form.errors.password"/>
                </el-form-item>

                <el-form-item label="Confirm Password">
                    <el-input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />

                    <InputError :message="form.errors.password_confirmation"/>
                </el-form-item>

                <el-button native-type="submit" type="primary" :loading="form.processing" :disabled="form.processing">
                    Reset Password
                </el-button>
            </el-form>
        </el-card>
    </GuestLayout>
</template>
