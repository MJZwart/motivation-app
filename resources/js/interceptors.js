import axios from 'axios';
import router from './router/router.js';
import store from './store/store.js';
import toastService from '../js/services/toastService';

window.axios = axios;

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.baseURL = '/api/';

axios.interceptors.response.use(
    
    function (response) {
        // Any status code that lie within the range of 2xx cause this function to trigger
        // do nothing, return response
        return response;
    },
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

        let errors;

        switch (error.response.status) {
            /**
             * If we get a 401 response from the API means that we are Unauthorized to
             * access the requested end point.
             * This means that probably our token has expired and we need to get a new one.
             */
            case 401:
                if (router.currentRoute.name !== 'login') {
                    store.dispatch('user/logout', false);
                }
                errors = error.response.data.errors || [];
                store.commit('setErrorMessages', errors);
                toastService.$emit('message', {message: 'You are not logged in', variant: 'danger'});
                // break promise and return error
                return Promise.reject(error, false);
            // user tried to access unauthorized resource
            case 403:
                //store.dispatch('logout', false);
                errors = error.response.data.errors || [];
                store.commit('setErrorMessages', errors);
                toastService.$emit('message', {message: 'You are not authorized for this action', variant: 'danger'});
                return Promise.reject(error);
            case 422:
                errors = error.response.data.errors || [];
                store.commit('setErrorMessages', errors);
                toastService.$emit('message', {message: 'There were errors in the form', variant: 'danger'});
                return Promise.reject(error);
            default:
                return Promise.reject(error);
        }
    }
);
