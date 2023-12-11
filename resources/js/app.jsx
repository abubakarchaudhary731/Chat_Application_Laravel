import './bootstrap';
import '../css/app.css';

import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);

    },
    progress: {
        color: '#4B5563',
    },
}); 

// Echo.private(`message-sent.${props.auth?.user?.id}.${props?.receiver?.id}`)
// .listen('MessageSent', (e) => {
// console.log(e);
// });

/* Presence Channel */
// Echo.join(`group-channel.1`)
//     .here((users) => {
//         console.log(users);
//     })
//     .joining((user) => {
//         console.log(user.name);
//     })
//     .leaving((user) => {
//         console.log(user.name);
//     })
//     .listen('PresenceEventTest', (e) => {
//         console.log(e);
//     })
//     .error((error) => {
//         console.log(error);
//     })