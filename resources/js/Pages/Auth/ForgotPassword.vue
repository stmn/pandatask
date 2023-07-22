<script setup>
import GuestLayout from '~/js/Layouts//GuestLayout.vue';
import InputError from '~/js/Components/Forms/InputError.vue';
import {Head, useForm} from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password"/>

        <el-card class="auth-card">
            <el-alert type="success" v-if="status" :closable="false">
                {{ status }}
            </el-alert>
            <el-alert v-else :closable="false">
                Forgot your password? No problem. Just let us know your email address and we will email you a password
                reset
                link that will allow you to choose a new one.
            </el-alert>

            <br>
            <el-form @submit.native.prevent="submit">
                <el-form-item prop="email" style="margin-bottom: 5px;">
                    <el-input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="Email address"
                    >
                        <template #prefix>
                            <i class="fas fa-envelope fa-fw"></i>
                        </template>
                    </el-input>
                    <InputError :message="form.errors.email"/>
                </el-form-item>


                <el-button native-type="submit"
                           :color="$primaryColor()"
                           :loading="form.processing" :disabled="form.processing">
                    Email Password Reset Link
                </el-button>
            </el-form>
        </el-card>
    </GuestLayout>
</template>
