<script setup>
import {Link} from '@inertiajs/vue3'
import {TinyColor} from "@ctrl/tinycolor";
import {useCssVar} from "@vueuse/core";
import {ref} from "vue";
import ColorPanel from "~/js/Components/Demo/ColorPanel.vue";
import Logo from "~/js/Components/Layout/AppLogo.vue";

const color = ref(new TinyColor(useCssVar('--el-color-primary', document.body).value));
</script>

<template>
    <ColorPanel/>

    <div class="guest">
        <div>
            <h1 class="logo" style="color: var(--brand-text-color);">
                <Link href="/" preserve-state style="color: var(--el-text-color);">
                    <Logo style="max-height:80px;display: inline-block;"/>
                    <p v-if="!$page.props.settings.brand_logo" class="m-0">
                        <b style="font-weight: 600;">{{ $page.props.settings.brand_name }}</b>
                    </p>
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
            font-size: 20px;
        }

        .el-button {
            margin-top: 10px;
            width: 100%;
        }
    }
}
</style>

