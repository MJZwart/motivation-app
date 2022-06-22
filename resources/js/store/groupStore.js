import axios from 'axios';
import {defineStore} from 'pinia';

export const useGroupStore = defineStore('group', {
    state: () => {
        return {
            /** @type Array<import('resources/types/group').Group> */
            myGroups: [],
            /** @type Array<import('resources/types/group').Group> */
            allGroups: [],
            /** @type import('resources/types/group').Group | null */
            group: null,
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
         * @param {Number} groupId
         */
        async fetchGroup(groupId) {
            const {data} = await axios.get(`/groups/${groupId}`);
            this.group = data.group;
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
            const {data} = await axios.post(`/groups/join/${group.id}`);
            this.group = data.group;
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async applyToGroup(group) {
            const {data} = await axios.post(`/groups/apply/${group.id}`);
            this.group = data.group;
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async fetchApplications(group) {
            const {data} = await axios.get(`/groups/applications/show/${group.id}`);
            return data.applications;
        },
        /**
         * @param {import('resources/types/group').Application} application 
         */
        async rejectApplication(application) {
            const {data} = await axios.post(`/groups/applications/reject/${application.id}`);
            this.group = data.group;
        },
        /**
         * @param {import('resources/types/group').Application} application 
         */
        async acceptApplication(application) {
            const {data} = await axios.post(`/groups/applications/accept/${application.id}`);
            this.group = data.group;
        },
        /**
         * @param {import('resources/types/group').Application} application 
         */
        async banApplication(application) {
            const {data} = await axios.post(`/groups/applications/ban/${application.id}`);
            this.group = data.group;
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async leaveGroup(group) {
            const {data} = await axios.post(`/groups/leave/${group.id}`);
            this.group = data.group;
        },
        /**
         * @param {import('resources/types/group').Group} group
         */
        async updateGroup(group) {
            const {data} = await axios.put(`/groups/edit/${group.id}`, group);
            this.group = data.groups.current;
            this.myGroups = data.groups.my;
        },
        /**
         * @param {import('resources/types/user').User} user
         * @param {import('resources/types/group').Group} group
         */
        async removeGroupMember(user, group) {
            const {data} = await axios.post(`/groups/kick/${group.id}`, user);
            this.group = data.group;
        },
        /**
         * @param {import('resources/types/user').User} user
         * @param {import('resources/types/group').Group} group
         */
        async banGroupMember(user, group) {
            const {data} = await axios.post(`/groups/ban/${group.id}`, user);
            this.group = data.group;
        },
    },
});