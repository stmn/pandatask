<script setup>
// import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import {Head} from '@inertiajs/vue3';
import Layout from "~/js/Layouts/Layout.vue";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <Head title="Profile"/>

    <Layout>
        <h2 style="margin-top: 0;">Profile</h2>

        <UpdateProfileInformationForm
            :must-verify-email="mustVerifyEmail"
            :status="status"
        />

        <UpdatePasswordForm/>

        <!-- <DeleteUserForm />-->
    </Layout>
</template>
