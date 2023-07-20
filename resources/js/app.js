import './bootstrap';

import {createApp, h} from 'vue';
// import ElementPlus from 'element-plus'
// import * as ElementPlusIconsVue from '@element-plus/icons-vue'
import {modal} from "momentum-modal"
import LazyComponent from 'v-lazy-component'
import Vue3Lottie from 'vue3-lottie'

import "~/css/app.scss";
import "uno.css";

// import "element-plus/theme-chalk/src/page-header.scss";
// import "element-plus/theme-chalk/src/menu.scss";
import "element-plus/theme-chalk/src/message.scss";

import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {TinyColor} from "@ctrl/tinycolor";
import {useCssVar} from "@vueuse/core";

// import { library } from '@fortawesome/fontawesome-svg-core'
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
// import { faCirclePlay, faCircleStop, faComment, faComments, faMessage } from '@fortawesome/free-solid-svg-icons';
// library.add(faCirclePlay, faCircleStop, faComment, faComments, faMessage)

// import ElMessage
// import { ElMessage } from 'element-plus';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

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
        const app = createApp({render: () => h(App, props)})
            .use(modal, {
                resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
            })
            .use(plugin)
            .use(Vue3Lottie)
            .use(LazyComponent);
            // .use(ElementPlus);
        // .component('font-awesome-icon', FontAwesomeIcon)

        // for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
        //     app.component(key, component)
        // }

        const primaryColor = useCssVar('--el-color-primary', document.body, {observe: true});

        app.mixin({
            methods: {
                group: function (name) {
                    return this.$page.props.auth.groups?.[name];
                },
                $route: route,
                $primaryColor: function(){
                    return primaryColor.value;
                    // return getComputedStyle(document.body).getPropertyValue('--el-color-primary');
                }
            },
        })

        // const primaryColor = getComputedStyle(document.body).getPropertyValue('--el-color-primary');
        const isDark = new TinyColor(primaryColor.value).isDark();
        // console.log({isDark})
        if(isDark) {
            document.body.style.setProperty('--text-color', 'var(--el-color-white)')
        } else {
            document.body.style.setProperty('--text-color', 'var(--el-color-black)')
        }

        return app.mount(el);
    },
});
