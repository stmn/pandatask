<script setup>
import {Link} from '@inertiajs/vue3'
import {TinyColor} from "@ctrl/tinycolor";
import {useCssVar} from "@vueuse/core";
import {ref} from "vue";
import ColorPanel from "~/js/Components/demo/ColorPanel.vue";
import Logo from "~/js/Components/Layout/AppLogo.vue";

const color = ref(new TinyColor(useCssVar('--el-color-primary', document.body).value));
</script>

<template>
    <ColorPanel/>

    <div class="guest">
        <div>
            <h1 class="logo" style="color: var(--brand-text-color);">
                <Link href="/" style="color: var(--el-text-color);">
                    <Logo style="height:100px;display: inline-block;"/>
                    <br><span :style="`font-weight: 400;`"><b>PANDA</b>TASK</span>
                </Link>
            </h1>

            <img alt=""
                style="transform: rotate(-45deg);z-index: -1;height: 30vw;right:-40px;bottom:-40px;display:inline-block;opacity: 0.03;position: fixed;"
                src="/logo.png"/>

            <el-config-provider size="large">
                <div>
                    <slot/>
                </div>
            </el-config-provider>
        </div>
    </div>
</template>

<style lang="scss">
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
        text-align: center;

        a {
            text-align: center;
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

