import '../css/app.css';
import './bootstrap';
import 'sweetalert2/dist/sweetalert2.min.css';

import { createInertiaApp, router } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { showFlashAlert } from './lib/sweetAlert';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

router.on('success', (event) => {
    showFlashAlert(event.detail.page.props.flash);
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        showFlashAlert(props.initialPage.props.flash);

        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
