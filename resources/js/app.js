import './bootstrap';
import '../css/app.css';
import '@quasar/extras/material-icons/material-icons.css'
import 'quasar/src/css/index.sass'

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import { Quasar } from 'quasar';
import VueTheMask from 'vue-the-mask'
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
            .use(VueTheMask)
            .use(Quasar, {
                autoImportComponentCase: 'pascal',
                plugins: {
                    Notify: true,
                    Loading: true,
                    Dialog: true,
                },
                config: {
                    loadingBar: {
                        color: 'primary',
                        size: '4px',
                        position: 'top',
                    },
                    notify: {
                        position: 'top',
                        timeout: 2500,
                        textColor: 'white',
                        actions: [{ icon: 'close', color: 'white' }],
                    },
                },
            })
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
