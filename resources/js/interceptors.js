/* eslint-disable max-lines-per-function */
import axios from 'axios';
import router from './router/router.js';

import {useMainStore} from './store/store';
import {useUserStore} from './store/userStore';

// @ts-ignore
window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = '/api/';

axios.interceptors.response.use(
    
    function (response) {
        if (response.status == 200) {
            if (response.data.message) {
                sendToast(response.data.message, 'success');
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
                // @ts-ignore
                if (router.currentRoute.name !== 'login') {
                    const userStore = useUserStore();
                    userStore.logout();
                    // store.dispatch('user/logout', false);
                }
                sendToast('You are not logged in', 'error');
                return Promise.reject(error);
            /** 
             * User tries to perform an action they are not authorized for, such as
             * an admin action. Redirect to dashboard and reject. Most of the time the 
             * router will already redirect them, this is backup.
             */
            case 403:
                // @ts-ignore
                if (router.currentRoute.name !== 'login') {
                    router.push('/dashboard');
                }
                sendToast('You are not authorized for this action', 'error');
                return Promise.reject(error);
            /**
             * In case of a 400 (Bad Request) the user tried to perform an invalid action 
             * In case of a 422 (Unprocessable Entity) the user made a mistake, such as
             * sending invalid data. This is mostly thrown by the validator
             */
            case 400:
            case 422:
                sendToast(error.response.data.message, 'error');
                // eslint-disable-next-line no-case-declarations
                const mainStore = useMainStore();
                mainStore.errors = error.response.data.errors;
                // store.commit('setErrorMessages', error.response.data.errors);
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
 * Sends a toast with the type of 'danger'
 * @param {String} toastMessage 
 * @param {String} type 
 */
function sendToast(toastMessage, type) {
    const mainStore = useMainStore();
    if (type == 'error')
        mainStore.addToast({'error' : toastMessage});
    else if (type == 'success')
        mainStore.addToast(toastMessage);
}