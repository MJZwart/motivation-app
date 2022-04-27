//@ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';

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
            this.allGroups = data.groups.all;
            this.myGroups = data.groups.my;
        },
        async createGroup(group) {
            await axios.post('/groups', group);
        },
        async deleteGroup(group) {
            await axios.delete(`/groups/${group.id}`);
        },
        async joinGroup(group) {
            await axios.post(`/groups/join/${group.id}`);
        },
        async leaveGroup(group) {
            await axios.post(`/groups/leave/${group.id}`);
        },
    },
});