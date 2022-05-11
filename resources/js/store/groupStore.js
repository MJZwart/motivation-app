import axios from 'axios';
import {defineStore} from 'pinia';

export const useGroupStore = defineStore('group', {
    state: () => {
        return {
            /** @type Array<import('resources/types/group').Group> */
            myGroups: [],
            /** @type Array<import('resources/types/group').Group> */
            allGroups: [],
            applications: [],
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
        /**
         * @param {import('resources/types/group').Group} group
         */
        async createGroup(group) {
            await axios.post('/groups', group);
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async deleteGroup(group) {
            await axios.delete(`/groups/${group.id}`);
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async joinGroup(group) {
            await axios.post(`/groups/join/${group.id}`);
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async applyToGroup(group) {
            await axios.post(`/groups/apply/${group.id}`);
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async fetchApplications(group) {
            const {data} = await axios.get(`/groups/applications/show/${group.id}`);
            this.applications = data.applications;
        },
        async rejectApplication(application) {
            await axios.post(`/groups/applications/reject/${application.id}`);
        },
        async acceptApplication(application) {
            await axios.post(`/groups/applications/accept/${application.id}`);
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async leaveGroup(group) {
            await axios.post(`/groups/leave/${group.id}`);
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async updateGroup(group) {
            const {data} = await axios.put(`/groups/edit/${group.id}`, group);
            this.myGroups = data.groups.my;
        },
        /**
         * @param {import('resources/types/user').User} user
         * @param {import('resources/types/group').Group} group
         */
        async removeGroupMember(user, group) {
            const {data} = await axios.post(`/groups/kick/${group.id}`, user);
            this.myGroups = data.groups.my;
        },
    },
});