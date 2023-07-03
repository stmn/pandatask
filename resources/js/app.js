import './bootstrap';
import '../css/app.scss';

import {createApp, h} from 'vue';
import ElementPlus from 'element-plus'
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
import {modal} from "momentum-modal"
import LazyComponent from 'v-lazy-component'

// import 'element-plus/dist/index.css'
// import 'element-plus/theme-chalk/dark/css-vars.css'
import "./../css/element/index.scss";
import "./../css/app.scss";

import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

const app = createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    progress: {
        // The delay after which the progress bar will appear, in milliseconds...
        delay: 0,
        // The color of the progress bar...
        color: '#fff',
        // Whether to include the default NProgress styles...
        includeCSS: true,
        // Whether the NProgress spinner will be shown...
        showSpinner: true,
    },
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {


        const app = createApp({render: () => h(App, props)})
            .use(modal, {
                resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
            })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(LazyComponent)
            .use(ElementPlus);

        for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
            app.component(key, component)
        }

        app.mixin({
            methods: {
                group: function (name) {
                    return this.$page.props.auth.groups?.[name];
                },
            },
        })


        return app.mount(el);
    },
    // progress: {
    //     color: '#4B5563',
    // },
});
