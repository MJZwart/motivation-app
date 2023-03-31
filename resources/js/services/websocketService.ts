import {ref} from 'vue';

export const socketConnected = ref(window.Echo.connector.pusher.connection.state === 'connected');