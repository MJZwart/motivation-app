import axios from 'axios';
import router from './router/router.js';
import {store} from './store/store.js';

import {useMainStore} from './store/store';
import {useUserStore} from './store/userStore';
const mainStore = useMainStore();
const userStore = useUserStore();

// @ts-ignore
window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = '/api/';

axios.interceptors.response.use(
    
    function (response) {
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
                if (router.currentRoute.name !== 'login') {
                    userStore.logout();
                    store.dispatch('user/logout', false);
                }
                sendErrorToast('You are not logged in');
                return Promise.reject(error);
            /** 
             * User tries to perform an action they are not authorized for, such as
             * an admin action. Redirect to dashboard and reject. Most of the time the 
             * router will already redirect them, this is backup.
             */
            case 403:
                if (router.currentRoute.name !== 'login') {
                    router.push('/dashboard');
                }
                sendErrorToast('You are not authorized for this action');
                return Promise.reject(error);
            /**
             * In case of a 400 (Bad Request) the user tried to perform an invalid action 
             * In case of a 422 (Unprocessable Entity) the user made a mistake, such as
             * sending invalid data. This is mostly thrown by the validator
             */
            case 400:
            case 422:
                sendErrorToast(error.response.data.message);
                store.commit('setErrorMessages', error.response.data.errors);
                return Promise.reject(error);
            default:
                return Promise.reject(error);
        }
    },
);

/**
 * Sends a toast with the type of 'danger'
 * @param {String} toastMessage 
 */
function sendErrorToast(toastMessage) {
    let toastObject = {'error' : toastMessage};
    store.commit('addToast', toastObject);
}