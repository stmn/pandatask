<template>
    <div class="admin-navigation">
        <small mb-3 block><b>Menu:</b></small>
        <el-menu :default-active="$page.props.title">

            <el-menu-item index="Users">
                <Link :href="$route('admin.users.index')" flex items-center>
                    <i class="fa-solid fa-users mr-2 fa-fw"></i>
                    <span>Users</span>
                </Link>
            </el-menu-item>
            <el-menu-item index="Groups">
                <Link :href="$route('admin.groups.index')" flex items-center>
                    <i class="fa-solid fa-user-group mr-2 fa-fw"></i>
                    <span>Groups</span>
                </Link>
            </el-menu-item>
            <el-menu-item index="Statuses">
                <Link :href="$route('admin.statuses.index')" flex items-center>
                    <i class="fa-solid fa-bars mr-2 fa-fw"></i>
                    <span>Statuses</span>
                </Link>
            </el-menu-item>
            <el-menu-item index="Priorities">
                <Link :href="$route('admin.priorities.index')" flex items-center>
                    <i class="fa-solid fa-bars mr-2 fa-fw"></i>
                    <span>Priorities</span>
                </Link>
            </el-menu-item>

            <!--                <Link :href="route('admin.users.index')">-->
            <!--                    <el-menu-item index="settings">-->
            <!--                        <span>Settings</span>-->
            <!--                    </el-menu-item>-->
            <!--                </Link>-->
        </el-menu>
    </div>
</template>

<script setup>
import {Link} from "@inertiajs/vue3";
</script>

<style lang="scss" scoped>
.admin-navigation {
    height: 100%;
    border-right: 1px solid var(--el-border-color-lighter);
    padding-right: 15px;

    .el-menu-item {
        height: auto;
        line-height: normal;
        padding-left: 10px !important;

        a {
            padding: 10px 0 !important;
            display: block;
            color: var(--el-text-color);

            &:hover {
                color: var(--el-color-success);
            }
        }

        &.is-active {
            font-weight: 600;
        }
    }
}
</style>
