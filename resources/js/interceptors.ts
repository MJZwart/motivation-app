/* eslint-disable max-lines-per-function */
import axios, {AxiosStatic} from 'axios';
import {errorToast, sendToast, storeToastInLocalStorage, successToast} from '/js/services/toastService';
import router from './router/router.js';

import {useMainStore} from './store/store';
import {useUserStore} from './store/userStore';
import {currentLang} from '/js/services/languageService.js';
import {waitingOnResponse} from '/js/services/loadingService.js';
import Echo from 'laravel-echo';

declare global {
    interface Window {
        axios: AxiosStatic;
        Echo: Echo;
        // eslint-disable-next-line @typescript-eslint/no-explicit-any
        Pusher: any;
    }
}

window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = '/api/';

/**
 * Request interceptors
 */
axios.interceptors.request.use(
    function (config) {
        // Do something before request is sent
        if (config && config.headers)
            config.headers.locale = currentLang.value;
        return config;
    }, 
    function (error) {
        // Do something with request error
        return Promise.reject(error);
    },
);

/**
 * Response interceptors
 */
axios.interceptors.response.use(
    
    function (response) {
        waitingOnResponse.value = false;
        if (response.status == 200) {
            if (response.data.message) {
                successToast(response.data.message);
            } else if (response.data.messageObject) {
                sendToast(response.data.messageObject)
            }
        }
        // Any status code that lie within the range of 2xx cause this function to trigger
        // do nothing, return response
        return response;
    },
    // eslint-disable-next-line complexity
    function (error) {
        waitingOnResponse.value = false;
        const userStore = useUserStore();
        const mainStore = useMainStore();
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
             * With a 400 it is most likely that the user performed an illegal action
             * or something changed with their permissions in the meantime. Reload
             * the page to show they 
             */
            case 400:
                router.go(0);
                storeToastInLocalStorage(error.response.data.message, 'error');
                return Promise.reject(error);
            /**
             * If we get a 401 response from the API means that we are Unauthorized to
             * access the requested end point.
             * This means that probably our token has expired and we need to get a new one.
             */
            case 401:
                if (router.currentRoute.value.name !== 'login') {
                    userStore.logout();
                }
                errorToast('You are not logged in');
                return Promise.reject(error);
            /** 
             * User tries to perform an action they are not authorized for, such as
             * an admin action. Redirect to dashboard and reject. Most of the time the 
             * router will already redirect them, this is backup.
             */
            case 403:
                userStore.getMe();
                if (router.currentRoute.value.name !== 'login') {
                    router.back();
                }
                errorToast(error.response.data.message);
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
            case 422:
                errorToast(error.response.data.message);
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
