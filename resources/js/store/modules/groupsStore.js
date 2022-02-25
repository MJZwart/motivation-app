//@ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        myGroups: [],
        groups: [],
    },
    mutations: {
        setMyGroups(state, myGroups) {
            state.myGroups = myGroups;
        },
        setGroups(state, groups) {
            state.groups = groups;
        },
    },
    getters: {
        getMyGroups: state => {
            return state.myGroups;
        },
        getGroups: state => {
            return state.groups;
        },
    },
    actions: {
        fetchMyGroups: ({commit}) => {
            axios.get('/groups/my').then(response => {
                commit('setMyGroups', response.data.data);
            });
        },
        fetchAllGroups: ({commit}) => {
            axios.get('groups/all').then(response => {
                commit('setGroups', response.data.data);
            });
        },
        createGroup: ({dispatch}, group) => {
            return axios.post('/groups', group).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            });
        },
        deleteGroup: ({dispatch}, group) => {
            return axios.delete(`/groups/${group.id}`).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            })
        },
        joinGroup: ({dispatch}, group) => {
            return axios.post(`/groups/${group.id}`).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            })
        },
        leaveGroup: ({dispatch}, group) => {
            return axios.post(`/groups/${group.id}`).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            })
        },


    },
}