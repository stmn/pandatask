import laravel from 'laravel-vite-plugin';
import path from 'path'
import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'

import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'

//import ElementPlus from 'unplugin-element-plus/vite'
import {ElementPlusResolver} from 'unplugin-vue-components/resolvers'

import Unocss from 'unocss/vite'
import {presetAttributify, presetUno, transformerDirectives, transformerVariantGroup,} from 'unocss'

const pathSrc = path.resolve(__dirname, 'resources')

// https://vitejs.dev/config/
export default defineConfig({
    resolve: {
        alias: {
            '~/': `${pathSrc}/`,
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData: `@use "resources/css/element/index.scss" as *;`,
            },
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),

        // ElementPlus(),
        AutoImport({
            resolvers: [ElementPlusResolver()],
        }),
        Components({
            //     // allow autoload markdown components under `./src/components/`
            //     extensions: ['vue', 'md'],
            //     // allow auto import and register components used in markdown
            //     include: [/\.vue$/, /\.vue\?vue/, /\.md$/],
            resolvers: [
                ElementPlusResolver({
                    importStyle: 'sass',
                }),
            ],
            //     deep: true,
        }),

        // https://github.com/antfu/unocss
        // see unocss.config.ts for config
        Unocss({
            presets: [
                presetUno(),
                presetAttributify(),
                // presetIcons({
                //     scale: 1.2,
                //     warn: true,
                // }),
            ],
            transformers: [
                transformerDirectives(),
                transformerVariantGroup(),
            ]
        }),
    ],
})
