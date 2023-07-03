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

const handleSelect = ({index}, middleClick = false) => {}

const test = () => {
    console.log('test');
}
</script>

<template>
    <el-header>
        <el-menu
            class="header-menu"
            :default-active="activeIndex"
            mode="horizontal"
            :ellipsis="true"
            :collapse-transition="false"
        >
            <el-menu-item class="brand">
                <Link :href="route('dashboard')" :only="[]">
                    <img style="height: 40px;display: inline-block;" src="/logo.png"/>
                    <span style="font-weight: 400;"><b>PANDA</b>TASK</span>
                </Link>
            </el-menu-item>

            <!--            <div class="flex-grow" style="flex-grow: 1;" />-->

            <el-menu-item index="dashboard" style="border:0;" :class="{'is-active': route().current()==='dashboard'}">
                <Link :href="route('dashboard')" :only="[]">
                    <el-icon>
                        <HomeFilled/>
                    </el-icon>Dashboard
                </Link>
            </el-menu-item>

            <el-menu-item index="projects" style="border:0;" :class="{'is-active': route().current()==='projects'}">
                <Link :href="route('projects')" :only="['projects']">
                    <el-icon>
                        <Menu/>
                    </el-icon>Projects
                </Link>
            </el-menu-item>
            <el-menu-item index="tasks" style="border:0;" :class="{'is-active': route().current()==='tasks'}">
                <Link :href="route('tasks')" :only="['tasks']">
                    <el-icon>
                        <List/>
                    </el-icon>Tasks
                </Link>
            </el-menu-item>

            <!--            <el-sub-menu index="myprofile">-->
            <!--                <template #title>My profile</template>-->
            <!--                <el-menu-item index="myprofile">My profile</el-menu-item>-->
            <!--                <el-menu-item index="logout">-->
            <!--                    <Link :href="route('logout')" method="post">-->
            <!--                        Logout-->
            <!--                    </Link>-->
            <!--                </el-menu-item>-->
            <!--            </el-sub-menu>-->
            <!--            <el-sub-menu index="admin">-->
            <!--                <template #title>Admin</template>-->
            <!--                <el-menu-item index="settings">Settings</el-menu-item>-->
            <!--                <el-menu-item index="users">Users</el-menu-item>-->
            <!--                <el-menu-item index="roles">Roles</el-menu-item>-->
            <!--            </el-sub-menu>-->

<!--            <el-switch-->
<!--                v-model="isDark"-->
<!--                size="small"-->
<!--                active-text="Dark"-->
<!--                inactive-text="Light"-->
<!--                style="&#45;&#45;el-switch-on-color: #13ce66; &#45;&#45;el-switch-off-color: #ff4949"-->
<!--            />-->

<!--            <el-menu-item style="height: 56px;">-->
<!--            <el-divider direction="vertical" :></el-divider>-->
<!--            </el-menu-item>-->

            <el-sub-menu index="2" :show-timeout="0" :teleported="false" style="margin-left: 20px;">
                <template #title>Profile</template>
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
    </el-header>
</template>

<style lang="scss" >
.header-menu {
    //padding: 0 20px;

    .brand {
        img {
            vertical-align: middle;
            margin-right: 10px;
        }

        a {

        }
    }

    .el-icon {
        margin-top: -2px;
    }

    .theme {
        line-height: 56px;
        height: 56px;
        font-size: 13px;
        .el-switch {
            margin-top: -2px;
            margin-right: 5px;
        }
    }

     //.el-menu--horizontal > .el-sub-menu .el-sub-menu__title:hover {
         //background: transparent;
     //}

    a {
        width: 100%;
        padding: 0 20px !important;
        text-decoration: none;
    }
}
.el-menu--horizontal > .el-sub-menu .el-sub-menu__title:hover {
    background: transparent !important;
}
</style>
