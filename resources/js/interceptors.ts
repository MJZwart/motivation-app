/* eslint-disable max-lines-per-function */
import axios, {AxiosStatic} from 'axios';
import {Toast} from 'resources/types/toast.js';
import router from './router/router.js';

import {useMainStore} from './store/store';
import {useUserStore} from './store/userStore';

declare global {
    interface Window {
        axios: AxiosStatic;
    }
}

window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = '/api/';

axios.interceptors.response.use(
    
    function (response) {
        if (response.status == 200) {
            if (response.data.message) {
                sendToast(null, response.data.message, 'success');
            }
        }
        // Any status code that lie within the range of 2xx cause this function to trigger
        // do nothing, return response
        return response;
    },
    // eslint-disable-next-line complexity
    function (error) {
        if (!error.response) {
            return Promise.reject(error);
        }
        // Any status codes that falls outside the range of 2xx cause this function to trigger
        // Do something with response error
        
        // refresh token reply should stay silent
        if (error.request.responseURL.indexOf('get_user_by_token') > -1) {
            return Promise.reject(error);
        }

        switch (error.response.status) {
            /**
             * If we get a 401 response from the API means that we are Unauthorized to
             * access the requested end point.
             * This means that probably our token has expired and we need to get a new one.
             */
            case 401:
                if (router.currentRoute.value.name !== 'login') {
                    const userStore = useUserStore();
                    userStore.logout();
                    // store.dispatch('user/logout', false);
                }
                sendToast('You are not logged in', null, 'error');
                return Promise.reject(error);
            /** 
             * User tries to perform an action they are not authorized for, such as
             * an admin action. Redirect to dashboard and reject. Most of the time the 
             * router will already redirect them, this is backup.
             */
            case 403:
                if (router.currentRoute.value.name !== 'login') {
                    router.push('/dashboard');
                }
                sendToast('You are not authorized for this action', null, 'error');
                return Promise.reject(error);
            /**
             * In the case of a 404, the user tried to find a user, group or other that no
             * longer exists (or never did). Redirect to the error page. For specific cases,
             * try to design specific errors (such as user not found, group not found, etc)
             * and catch those errors through the store. This is a catch-all.
             */
            case 404:
                router.push({name: 'Error'});
                return Promise.reject(error);
            /**
             * In case of a 400 (Bad Request) the user tried to perform an invalid action 
             * In case of a 422 (Unprocessable Entity) the user made a mistake, such as
             * sending invalid data. This is mostly thrown by the validator
             */
            case 400:
            case 422:
                sendToast(error.response.data.message, null, 'error');
                // eslint-disable-next-line no-case-declarations
                const mainStore = useMainStore();
                mainStore.setErrorMessages(error.response.data.errors);
                return Promise.reject(error);
            /**
             * In case of a 419 the CSRF token has expired. Force the page to reload to
             * refresh the token and try again.
             */
            // case 419:
                // location.reload();
                // return Promise.reject(error);
            default:
                return Promise.reject(error);
        }
    },
);

/**
 * Sends a toast
 */
function sendToast(toastMessage: string | null, toast: Toast | null, type: string) {
    const mainStore = useMainStore();
    if (type == 'error' && toastMessage)
        mainStore.addToast({'error' : toastMessage});
    else if (type == 'success' && toast)
        mainStore.addToast(toast);
}