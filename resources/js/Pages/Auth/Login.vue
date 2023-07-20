<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import {Head, Link, useForm} from '@inertiajs/vue3';
import {computed, ref, watch} from "vue";
import {useCssVar} from "@vueuse/core";
import {TinyColor} from "@ctrl/tinycolor";

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


const color = useCssVar('--el-color-primary', document.body, {observe:true})
console.log(111,color)

setInterval(() => {
    // color.value = '#'+Math.floor(Math.random()*16777215).toString(16);
    // console.log(color.value)
}, 3000);

setInterval(() => {
    // console.log(123, useCssVar('--el-color-primary', document.documentElement))
    // color.value = useCssVar('--el-color-primary', document.documentElement, {observe: true}).value;
}, 100);

watch(()=>color, () => {
    console.log(123)
});
watch(color, () => {
    console.log(123)
});
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
                        :prefix-icon="Lock"
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
