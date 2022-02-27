//@ts-nocheck
import axios from 'axios';

export default {

    namespaced: true,
    state: {
        myGroups: [],
        allGroups: [],
    },
    mutations: {
        setMyGroups(state, myGroups) {
            state.myGroups = myGroups;
        },
        setAllGroups(state, allGroups) {
            state.allGroups = allGroups;
        },
    },
    getters: {
        getMyGroups: state => {
            return state.myGroups;
        },
        getAllGroups: state => {
            return state.allGroups;
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
                commit('setAllGroups', response.data.data);
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
            return axios.post(`/groups/join/${group.id}`).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            })
        },
        leaveGroup: ({dispatch}, group) => {
            return axios.post(`/groups/leave/${group.id}`).then(response => {
                dispatch('sendToasts', response.data.message, {root:true});
                return Promise.resolve();
            })
        },


    },
}