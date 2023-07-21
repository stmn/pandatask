import './bootstrap';

import {createApp, h} from 'vue';
import {modal} from "momentum-modal"
import LazyComponent from 'v-lazy-component'
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {TinyColor} from "@ctrl/tinycolor";
import {useCssVar} from "@vueuse/core";

import "~/css/app.scss";
import "uno.css";
import "element-plus/theme-chalk/src/message.scss";

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Pandatask';

// noinspection JSUnusedLocalSymbols
const app = createInertiaApp({
    title: (title) => `${title} - ${appName}`,

    progress: {
        delay: 0,
        color: '#fff',
        includeCSS: true,
        showSpinner: true,
    },

    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),

    setup({el, App, props, plugin}) {
        // noinspection JSCheckFunctionSignatures
        const app = createApp({render: () => h(App, props)})
            .use(modal, {
                resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
            })
            .use(plugin)
            .use(LazyComponent);

        const primaryColor = useCssVar('--el-color-primary', document.body, {observe: true});

        app.mixin({
            methods: {
                group: function (name) {
                    return this.$page.props.auth.groups?.[name];
                },
                $route: route,
                $primaryColor: () => primaryColor.value
            },
        })

        if (new TinyColor(primaryColor.value).isDark()) {
            document.body.style.setProperty('--text-color', 'var(--el-color-white)')
        } else {
            document.body.style.setProperty('--text-color', 'var(--el-color-black)')
        }

        return app.mount(el);
    },
});
