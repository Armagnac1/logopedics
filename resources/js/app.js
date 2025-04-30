// Import styles
import './bootstrap';
import '../css/app.css';
import 'preline/preline';

// Import Vue and Inertia
import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// Import Ziggy for route handling
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

// Define application name
const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

// Create and configure Inertia app
createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .component('font-awesome-icon', FontAwesomeIcon)
            .use(ZiggyVue)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
