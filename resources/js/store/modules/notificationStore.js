// @ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,

    state: {
        notifications: null,
        hasNotifications: false,
    },
    mutations: {
        setNotifications: (state, notifications) => {
            state.notifications = notifications;
        },
        setHasNotifications: (state, bool) => {
            state.hasNotifications = bool;
        },
    },
    getters: {
        getNotifications: state => {
            return state.notifications;
        },
        getHasNotifications: state => {
            return state.hasNotifications;
        },
    },
    actions: {
        getNotifications: ({commit}) => {
            axios.get('/notifications').then(function(response) {
                commit('setNotifications', response.data.data);
            });
        },
        hasUnreadNotifications: ({commit}) => {
            axios.get('/notifications/unread').then(function(response) {
                commit('setHasNotifications', response.data);
            });
        },
        deleteNotification: ({commit, dispatch}, notificationId) => {
            axios.delete('/notifications/' + notificationId).then(function(response) {
                dispatch('sendToasts', response.data.message, {root:true});
                commit('setNotifications', response.data.data);
            });
        },
    },
}