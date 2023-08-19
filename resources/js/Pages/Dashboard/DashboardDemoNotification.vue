<template>
    <el-alert
        type="info"
        show-icon
        style="margin: 10px 0 0 0; text-align:left;">
        <template #title>
            <b>Attention!</b>
        </template>
        <template #default>
            This is a demonstration version of the <b>Pandatask</b> early build. Database is reset <b>every 4 hours</b>.<br>
            <b>Feel free</b> to create new users, projects, tasks, timesheets, etc.<br>
            Some features may be disabled or limited.
        </template>
    </el-alert>
</template>
