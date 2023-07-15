<script setup>
import {Head, Link, router} from "@inertiajs/vue3";
import {Avatar, Setting, UserFilled} from "@element-plus/icons-vue";

const props = defineProps({
    title: {
        required: false,
    },
    back: {
        required: false,
        default: route('dashboard')
    }
})
</script>

<template>
    <Head :title="$page.props.title"/>

    <el-alert type="warning" style="font-size: 18px; margin-bottom: 15px;" :closable="false">
        <b>License Expired.</b><br>
        The license for this application has expired. As a result, updates and support services are no longer available.
        Please contact the application provider to renew your license.
    </el-alert>

    <el-alert type="error" style="font-size: 18px; margin-bottom: 15px;" :closable="false">
        <b>Unauthorized Domain.</b><br>
        This application is licensed for use only on the authorized domain.
        Please contact the application provider for assistance.
    </el-alert>

    <el-alert type="info" style="font-size: 18px; margin-bottom: 15px;" :closable="false">
        <b>New Software Version Available.</b><br>
        A new version of this software is now available. We highly recommend updating to the latest version to benefit from new features, enhancements, and bug fixes.<br>
        Read the <a href="#">release notes</a> for more information.
    </el-alert>

    <el-row :gutter="20">
        <el-col :span="4">
            <el-menu
                :default-active="$route().current()"
                active-text-color="var(--el-color-success)"
                background-color="var(--el-color-primary-light-9)"
                text-color="var(--el-text-color-primary)"
                style="border-radius: 5px; padding: 0 10px;"
            >
                <Link :href="$route('admin.users.index')">
                    <el-menu-item :index="$route().current('admin.users.*') ? $route().current() : ''">
                        <el-icon>
                            <UserFilled/>
                        </el-icon>
                        <span>Users</span>
                    </el-menu-item>
                </Link>
                <Link :href="$route('admin.groups.index')">
                    <el-menu-item :index="$route().current('admin.groups.*') ? $route().current() : ''">
                        <el-icon>
                            <Avatar/>
                        </el-icon>
                        <span>Groups</span>
                    </el-menu-item>
                </Link>
                <Link :href="$route('admin.statuses.index')">
                    <el-menu-item :index="$route().current('admin.statuses.*') ? $route().current() : ''">
                        <el-icon>
                            <Avatar/>
                        </el-icon>
                        <span>Statuses</span>
                    </el-menu-item>
                </Link>
                <Link :href="$route('admin.priorities.index')">
                    <el-menu-item :index="$route().current('admin.priorities.*') ? $route().current() : ''">
                        <el-icon>
                            <Avatar/>
                        </el-icon>
                        <span>Priorities</span>
                    </el-menu-item>
                </Link>
<!--                <Link :href="route('admin.users.index')">-->
<!--                    <el-menu-item index="settings">-->
<!--                        <el-icon>-->
<!--                            <Setting/>-->
<!--                        </el-icon>-->
<!--                        <span>Settings</span>-->
<!--                    </el-menu-item>-->
<!--                </Link>-->
            </el-menu>
        </el-col>
        <el-col :span="20">
            <el-page-header @back="router.visit(back)"
                            :content="$page.props.title"></el-page-header>
            <br>
            <slot></slot>
        </el-col>
    </el-row>
</template>

<style>
.el-menu-item:hover {
    border-radius: 5px;
}
</style>
