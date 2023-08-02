<script setup>
import InputError from '~/js/Components/Forms/InputError.vue';
import {useForm, usePage} from '@inertiajs/vue3';
import {ElMessage} from "element-plus";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
    public_email: user.public_email,
    job_title: user.job_title,
    phone: user.phone,
});

const submit = () => {
    form.patch(route('profile.update'), {
        preserveScroll: true,
        onSuccess: () => {
            ElMessage.success('Saved.')
        },
    });
};
</script>

<template>
    <section>
        <h3>Profile Information</h3>

        <el-alert show-icon :closable="false" type="info">
            Update your account's profile information and email address.
        </el-alert>
        <br>
        <el-card shadow="never">
            <el-form @submit.prevent="submit" label-width="180" label-position="left">
                <el-form-item label="First name">
                    <el-input
                        type="text"
                        v-model="form.first_name"
                        required
                        autofocus
                        autocomplete="first_name"
                    >
                        <template #prefix>
                            <i class="fa-solid fa-user"></i>
                        </template>
                    </el-input>

                    <InputError :message="form.errors.first_name"/>
                </el-form-item>

                <el-form-item label="Last name">
                    <el-input
                        type="text"
                        v-model="form.last_name"
                        required
                        autofocus
                        autocomplete="last_name"
                    >
                        <template #prefix>
                            <i class="fa-solid fa-user"></i>
                        </template>
                    </el-input>

                    <InputError :message="form.errors.last_name"/>
                </el-form-item>

                <el-form-item label="Email">
                    <el-input
                        type="email"
                        v-model="form.email"
                        required
                        autocomplete="email"
                    >
                        <template #prefix>
                            <i class="fa-solid fa-envelope"></i>
                        </template>
                    </el-input>

                    <InputError :message="form.errors.email"/>
                </el-form-item>

                <el-form-item label="Public Email">
                    <el-input
                        type="public_email"
                        v-model="form.public_email"
                        required
                        autocomplete="public_email"
                    >
                        <template #prefix>
                            <i class="fa-solid fa-envelope"></i>
                        </template>
                    </el-input>

                    <InputError :message="form.errors.public_email"/>
                </el-form-item>

                <el-form-item label="Phone">
                    <el-input
                        type="phone"
                        v-model="form.phone"
                        required
                        autocomplete="phone"
                    >
                        <template #prefix>
                            <i class="fa-solid fa-phone"></i>
                        </template>
                    </el-input>

                    <InputError :message="form.errors.phone"/>
                </el-form-item>

                <el-form-item label="Job title">
                    <el-input
                        type="job_title"
                        v-model="form.job_title"
                        required
                        autocomplete="job_title"
                    >
                        <template #prefix>
                            <i class="fa-solid fa-briefcase"></i>
                        </template>
                    </el-input>

                    <InputError :message="form.errors.job_title"/>
                </el-form-item>

                <!--            <div v-if="mustVerifyEmail && user.email_verified_at === null">-->
                <!--                <p>-->
                <!--                    Your email address is unverified.-->
                <!--                    <Link-->
                <!--                        :href="$route('verification.send')"-->
                <!--                        method="post"-->
                <!--                        as="button"-->
                <!--                    >-->
                <!--                        Click here to re-send the verification email.-->
                <!--                    </Link>-->
                <!--                </p>-->

                <!--                <div-->
                <!--                    v-show="status === 'verification-link-sent'"-->
                <!--                >-->
                <!--                    A new verification link has been sent to your email address.-->
                <!--                </div>-->
                <!--            </div>-->

                <el-form-item>
                    <el-button @click="submit" :color="$primaryColor()" :disabled="form.processing">Save</el-button>
                </el-form-item>
            </el-form>
        </el-card>
    </section>
</template>
