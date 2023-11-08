import axios from 'axios';
/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.withCredentials = true;

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';
import pusher from 'pusher-js';

window.Pusher = pusher;

console.log(import.meta.env);

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY || 'motivation',
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER || 'mt1',
    secret: import.meta.env.VITE_PUSHER_APP_SECRET || 'motiv',
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    // @ts-ignore
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    authorizer: (channel, _options) => {
        return {
            // @ts-ignore
            authorize: (socketId, callback) => { 
                
                console.log('Attempting to authorize with socket id: ' + socketId);
                axios.post('/broadcasting/auth', {
                    socket_id: socketId,
                    channel_name: channel.name,
                })
                    .then(response => {
                        console.log(response);
                        callback(false, response.data)
                    })
                    .catch(error => {
                        console.log(error);
                        callback(true, error)
                    })
            },
        }
    },
});

window.Echo.connector.pusher.connection.bind('state_change', function(states: {current: string}) {
    if (states.current === 'disconnected' || states.current === 'failed' || states.current === 'unavailable') {
        window.Echo.connector.pusher.connection.retryIn(1000);
    }
});