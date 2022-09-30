import {Toast} from 'resources/types/toast';
import {ref} from 'vue';

export const toasts = ref<Toast[]>([]);

/**
 * Creates a toast with a green line and 'Success' as title
 */
export function successToast(message: string) {
    sendToast(createToast(message, 'success'));
}

/**
 * Creates a toast with a red line and 'Error' as title
 */
export function errorToast(message: string) {
    sendToast(createToast(message, 'error'));
}

/**
 * Creates the Toast object
 */
function createToast(toastMessage: string, type: string): Toast {
    if (type === 'error') return {'error': toastMessage};
    if (type === 'success') return {'success': toastMessage};
    else return {'info': toastMessage};
}

/**
 * Sends the toast into the toastbucket to be displayed
 */
function sendToast(toast: Toast) {
    toasts.value.push(toast);
}

/**
 * Clears the oldest toast, generally triggered by a timer
 */
export function clearToast() {
    toasts.value.splice(0, 1);
}

/**
 * Stores a toast in local storage in case a reload of the page is necessary. This ensures
 * the toast will still be shown to the user for an appropriate amount of time
 */
export function storeToastInLocalStorage(toastMessage: string | null, type: string) {
    const toast = `{"type": "${type}", "message": "${toastMessage}"}`;
    localStorage.setItem('queuedError', toast);
}

/**
 * Fetches stored toasts from localstorage if there are any
 */
export function fetchStoredToasts() {
    const storedToast = localStorage.getItem('queuedError');
    if (storedToast) {
        toasts.value.push(reconstructToast(storedToast));
        localStorage.removeItem('queuedError');
    }
}

/**
 * Reconstructs a toast from the stored localstorage toast
 */
function reconstructToast(toastString: string): Toast {
    const toastObject = JSON.parse(toastString);
    return createToast(toastObject.message, toastObject.type)
}