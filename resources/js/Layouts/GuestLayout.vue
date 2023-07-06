<script setup>
import {Link} from '@inertiajs/vue3'
</script>

<template>
    <div class="guest">
        <div>
            <h1 class="logo">
                <Link href="/">
                    <img style="height:100px;display: inline-block;" src="/logo.png"/>
                    <br><span style="font-weight: 400;"><b>PANDA</b>TASK</span>
                </Link>
            </h1>

            <img
                style="transform: rotate(-45deg);z-index: -1;height: 512px;right:-40px;bottom:-40px;display:inline-block;opacity: 0.03;position: absolute;"
                src="/logo.png"/>

            <el-config-provider size="large">
                <slot/>
            </el-config-provider>
        </div>
    </div>
</template>

<style lang="scss">
body {
    overflow: hidden;
}

.guest {
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    min-height: 100%;

    a {
        color: var(--el-text-color);
    }

    h1 {
        //margin-top: -20%;
        //margin-bottom: 20%;
        text-align: center;

        a {
            text-align: center;
            color: #fff;
            text-decoration: none;
        }
    }

    .auth-card {
        width: 335px;
        padding: 5px 5px 5px 5px;
        margin-bottom: 60%;

        :deep(.el-card__body) {
            padding-top: 0;
        }

        h2 {
            margin: 0 0 20px 0;
        }

        .el-button {
            margin-top: 10px;
            width: 100%;
        }
    }
}
</style>

