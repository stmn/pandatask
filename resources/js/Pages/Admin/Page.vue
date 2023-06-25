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

    <el-row :gutter="20">
        <el-col :span="4">
            <el-menu
                :default-active="route().current()"
                active-text-color="var(--el-color-success)"
                background-color="var(--el-color-primary-light-9)"
                text-color="var(--el-text-color-primary)"
                style="border-radius: 5px; padding: 0 10px;"
            >
                <Link :href="route('admin.users.index')">
                    <el-menu-item :index="route().current('admin.users.*') ? route().current() : ''">
                        <el-icon>
                            <UserFilled/>
                        </el-icon>
                        <span>Users</span>
                    </el-menu-item>
                </Link>
                <Link :href="route('admin.groups.index')">
                    <el-menu-item :index="route().current('admin.groups.*') ? route().current() : ''">
                        <el-icon>
                            <Avatar/>
                        </el-icon>
                        <span>Groups</span>
                    </el-menu-item>
                </Link>
                <Link :href="route('admin.statuses.index')">
                    <el-menu-item :index="route().current('admin.statuses.*') ? route().current() : ''">
                        <el-icon>
                            <Avatar/>
                        </el-icon>
                        <span>Statuses</span>
                    </el-menu-item>
                </Link>
                <Link :href="route('admin.priorities.index')">
                    <el-menu-item :index="route().current('admin.priorities.*') ? route().current() : ''">
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
