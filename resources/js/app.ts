// Import styles
import './bootstrap';
import '../css/app.css';
import 'preline/preline';

// Import Vue and Inertia
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// Import Ziggy for route handling
// @ts-ignore
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

// Import i18n for internationalization
import { i18n } from './i18n';

// Import FontAwesome
import { library } from '@fortawesome/fontawesome-svg-core';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { fas } from '@fortawesome/free-solid-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';

// Add FontAwesome icons to the library
library.add(fas, far);

// @ts-ignore
const appName: string = import.meta.env.VITE_APP_NAME || 'Laravel';

// Create and configure Inertia app
// @ts-ignore
createInertiaApp({
    title: (title: string) => `${title} - ${appName}`,
    // @ts-ignore
    resolve: (name: string) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('font-awesome-icon', FontAwesomeIcon)
            .use(ZiggyVue)
            .use(i18n);
        vueApp.mount(el);
        return vueApp;
    },
    progress: {
        color: '#4B5563',
    },
});
