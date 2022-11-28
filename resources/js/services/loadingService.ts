import {ref} from 'vue';

/**
 * Used to signify that the site is waiting on back-end response.
 * Turned on manually (automatically through SubmitButton) and turned
 * off as soon as a response is in.
 */
export const waitingOnResponse = ref(false);