//@ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';

export const useGroupStore = defineStore('group', {
    state: () => {
        return {
            myGroups: [],
            allGroups: [],
        }
    },
    actions: {
        // fetchMyGroups() {
        //     const data = await axios.get('/groups/my')
        //     this.myGroups = data.groups.my;
        // },
        // fetchAllGroups() {
        //     const data = await axios.get('groups/all')
        //     this.allGroups = data.groups.all;
        // },
        async fetchGroupsDashboard() {
            const {data} = await axios.get('groups/dashboard');
            console.log(data);
            this.allGroups = data.groups.all;
            this.myGroups = data.groups.my;
        },
        async createGroup(group) {
            const {data} = await axios.post('/groups', group);
            console.log(data);
            this.addToast(data.message);
        },
        async deleteGroup(group) {
            const {data} = await axios.delete(`/groups/${group.id}`);
            console.log(data);
            this.addToast(data.message);
        },
        async joinGroup(group) {
            const {data} = await axios.post(`/groups/join/${group.id}`);
            console.log(data);
            this.addToast(data.message);
        },
        async leaveGroup(group) {
            const {data} = await axios.post(`/groups/leave/${group.id}`);
            console.log(data);
            this.addToast(data.message);
        },
        addToast(toast) {
            const mainStore = useMainStore();
            mainStore.addToast(toast);
        },
    },
});