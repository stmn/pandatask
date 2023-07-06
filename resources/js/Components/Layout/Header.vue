<script setup>
import {Link, router, usePage} from '@inertiajs/vue3'
import {ref} from "vue";
import {HomeFilled, List, Menu} from "@element-plus/icons-vue";

import {useDark, useToggle} from "@vueuse/core";

const isDark = useDark()
const toggleDark = useToggle(isDark)

const props = defineProps({
    activeIndex: {
        type: String,
        required: true,
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
                <Link :href="route('dashboard')" :only="[]">
                    <img src="/logo.png" alt=""/>
                    <span style="color: #fff;"><b>PANDA</b>TASK</span>
                </Link>
            </div>
            <el-menu
                class="disable-animations"
                :default-active="activeIndex"
                mode="horizontal"
                :ellipsis="false"
                :collapse-transition="false"
            >
                <el-menu-item index="dashboard" style="border:0;"
                              :class="{'is-active': route().current()==='dashboard'}">
                    <Link :href="route('dashboard')" :only="[]">
                        <el-icon>
                            <HomeFilled/>
                        </el-icon>
                        Dashboard
                    </Link>
                </el-menu-item>

                <el-menu-item index="projects" style="border:0;" :class="{'is-active': route().current()==='projects'}">
                    <Link :href="route('projects')" :only="['projects']">
                        <el-icon>
                            <Menu/>
                        </el-icon>
                        Projects
                    </Link>
                </el-menu-item>

                <el-menu-item index="tasks" style="border:0;" :class="{'is-active': route().current()==='tasks'}">
                    <Link :href="route('tasks')" :only="['tasks']">
                        <el-icon>
                            <List/>
                        </el-icon>
                        Tasks
                    </Link>
                </el-menu-item>

                <el-sub-menu index="2" :show-timeout="0" style="margin-left: 20px;">
                    <template #title>More</template>
                    <el-menu-item index="profile">
                        <Link :href="route('profile.edit')">Edit profile</Link>
                    </el-menu-item>
                    <el-menu-item index="admin" v-if="group('admin')">
                        <Link :href="route('admin.users.index')">Admin</Link>
                    </el-menu-item>
                    <el-menu-item index="logout">
                        <Link method="post" :href="route('logout')">Logout</Link>
                    </el-menu-item>
                    <el-menu-item index="theme">
                        <el-button @click.stop="toggleDark()" style="width: 100%;">
                            <i inline-block align-middle i="dark:carbon-moon carbon-sun"/>

                            <span class="ml-2">{{ isDark ? 'Dark theme' : 'Light theme' }}</span>
                        </el-button>
                    </el-menu-item>
                </el-sub-menu>
            </el-menu>
        </div>
    </el-header>
</template>

<style lang="scss">
.brand {
    color: #fff;
    font-size: 14px;
    line-height: var(--el-header-height);
    padding: 0 20px 0 10px;

    a {
        display: flex;
        justify-content: center;
        align-items: center;

        img {
            margin-right: 10px;
            width: 40px;
        }
    }
}

.el-menu {
    width: 100%;
    border-bottom: 0;
    color: #fff !important;

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

            &:hover {
                background: rgba(0, 0, 0, 0.1) !important;
            }

            a {
                color: #fff;
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
