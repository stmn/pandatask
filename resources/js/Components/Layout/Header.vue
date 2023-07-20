<script setup>
import {Link, router, usePage} from '@inertiajs/vue3'
import {ref} from "vue";

import {useDark, useToggle} from "@vueuse/core";
import Logo from "@/Components/Logo.vue";

// import $route from mixin methods

const isDark = useDark()
const toggleDark = useToggle(isDark)

const props = defineProps({
    activeIndex: {
        type: String,
        default: ''
    },
});

const page = usePage()

const activeIndex = ref(props.activeIndex);

router.on('success', (event) => {
    activeIndex.value = event.detail.page.props.activeIndex;
})
</script>

<template>
    <el-header>
        <div style="display: flex; max-height: var(--el-header-height);">
            <div class="brand">
                <Link :href="$route('dashboard')" :only="[]">
<!--                    <img src="/logo.png" alt=""/>-->
                    <Logo class="logo" />
                    <span><b>PANDA</b>TASK</span>
                </Link>
            </div>
            <el-menu
                class="disable-animations"
                :default-active="activeIndex"
                mode="horizontal"
                :ellipsis="false"
                :collapse-transition="false"
            >
                <el-menu-item index="dashboard" style="border:0;line-height:1;"
                              :class="{'is-active': $route().current()==='dashboard'}">
                    <Link :href="$route('dashboard')" :only="[]" style="display: flex;">
                        <i class="fa-solid fa-house mr-2"></i>
                        <span>Dashboard</span>
                    </Link>
                </el-menu-item>

                <el-menu-item index="projects" style="border:0;line-height:1" :class="{'is-active': $route().current()==='projects'}">
                    <Link :href="$route('projects')" :only="['projects']" style="display: flex;">
                        <i class="fa-solid fa-rectangle-list mr-2"></i>
                        Projects
                    </Link>
                </el-menu-item>

                <el-menu-item index="tasks" style="border:0;line-height:1" :class="{'is-active': $route().current()==='tasks'}">
                    <Link :href="$route('tasks')" :only="['tasks']" style="display: flex;">
                        <i class="fa-solid fa-list-check mr-2"></i>
                        Tasks
                    </Link>
                </el-menu-item>

                <el-sub-menu index="2" :show-timeout="0" style="margin-left: 20px;">
                    <template #title>More</template>
                    <el-menu-item index="profile" style="line-height: 1;">
                        <Link :href="$route('profile.edit')">
                            <i class="fa-fw fa-solid fa-user-pen mr-2"></i>Edit profile
                        </Link>
                    </el-menu-item>
                    <el-menu-item index="admin" v-if="group('admin')"  style="line-height: 1;">
                        <Link :href="$route('admin.users.index')">
                            <i class="fa-fw fa-solid mr-2"></i>Admin panel
                        </Link>
                    </el-menu-item>
                    <el-menu-item index="logout">
                        <form method="post" :action="$route('logout')" style="width: 100%;">
                            <input type="hidden" name="_token" :value="usePage().props.csrf_token">
                            <el-button style="width: 100%;" type="danger" text bg native-type="submit">Logout</el-button>
                        </form>
                    </el-menu-item>
                    <el-menu-item index="theme" style="margin-top: 5px; text-align: left;">
                        <el-button @click.stop="toggleDark()" text bg style="width: 100%;">
                            <i v-if="isDark" class="fa-fw fa-solid fa-sun mr-2"></i>
                            <i v-else class="fa-fw fa-solid fa-moon mr-2"></i>
                            <span v-if="isDark">Light theme</span>
                            <span v-else>Dark theme</span>
                        </el-button>
<!--                        <el-switch v-model="isDark" @click.stop-->
<!--                                   active-text="Dark"-->
<!--                                   inactive-text="Light"-->
<!--                        />-->
                    </el-menu-item>
                </el-sub-menu>
            </el-menu>
        </div>
    </el-header>
</template>

<style lang="scss">
.brand {
    color: var(--el-text-color);
    font-size: 14px;
    line-height: var(--el-header-height);
    padding: 0 20px 0 10px;

    a {
        color: var(--el-text-color) !important;
        display: flex;
        justify-content: center;
        align-items: center;

        .logo {
            margin-right: 10px;
            width: 36px;
        }
    }
}

.el-menu {
    width: 100%;
    border-bottom: 0;
    color: var(--el-text-color);

    .theme {
        line-height: 56px;
        height: 56px;
        font-size: 13px;

        .el-switch {
            margin-top: -2px;
            margin-right: 5px;
        }
    }

    .el-menu-item {
        &.is-active {
            background: rgba(0, 0, 0, 0.1);
            color: var(--el-text-color) !important;

            &:hover {
                background: rgba(0, 0, 0, 0.1) !important;
            }

            a {
                color: var(--el-text-color) !important;
            }
        }

        .el-icon {
            margin-top: -2px;
        }

        a {
            width: 100%;
            padding: 0 20px 0 10px !important;
            text-decoration: none;
        }
    }

    .el-sub-menu {
        .el-sub-menu__title {
            border: 0;
            &:hover {
                background: transparent !important;
            }
        }
    }
}

</style>
