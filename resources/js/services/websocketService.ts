import {computed} from 'vue';

export const socketConnected = computed(() => window.Echo.connector.pusher.connection.state === 'connected');