import axios from 'axios';
import {defineStore} from 'pinia';
import {Group, GroupPage, GroupUser, NewGroup} from 'resources/types/group';

export const useGroupStore = defineStore('group', {
    state: () => {
        return {
            myGroups: [] as Group[],
            allGroups: [] as Group[],
            group: null as GroupPage | null,
        };
    },
    actions: {
        async fetchGroupsDashboard() {
            const {data} = await axios.get('groups/dashboard');
            this.allGroups = data.groups.all;
            this.myGroups = data.groups.my;
        },
        async fetchGroup(groupId: number) {
            const {data} = await axios.get(`/groups/${groupId}`);
            this.group = data.group;
        },
        async createGroup(group: NewGroup) {
            await axios.post('/groups', group);
        },
        async deleteGroup(groupId: number) {
            await axios.delete(`/groups/${groupId}`);
        },
        async joinGroup(groupId: number) {
            const {data} = await axios.post(`/groups/join/${groupId}`);
            this.group = data.data.group;
        },
        async applyToGroup(groupId: number) {
            const {data} = await axios.post(`/groups/apply/${groupId}`);
            this.group = data.data.group;
        },
        async fetchApplications(groupId: number) {
            const {data} = await axios.get(`/groups/applications/show/${groupId}`);
            return data.applications;
        },
        async rejectApplication(applicationId: number, groupId: number) {
            const {data} = await axios.post(`/groups/applications/${groupId}/reject/${applicationId}`);
            this.group = data.data.group;
        },
        async acceptApplication(applicationId: number, groupId: number) {
            const {data} = await axios.post(`/groups/applications/${groupId}/accept/${applicationId}`);
            this.group = data.data.group;
        },
        async suspendApplication(applicationId: number, groupId: number) {
            const {data} = await axios.post(`/groups/applications/${groupId}/suspend/${applicationId}`);
            this.group = data.data.group;
        },
        async leaveGroup(groupId: number) {
            const {data} = await axios.post(`/groups/leave/${groupId}`);
            this.group = data.data.group;
        },
        async updateGroup(group: Group) {
            const {data} = await axios.put(`/groups/edit/${group.id}`, group);
            this.group = data.data.groups.current;
            this.myGroups = data.data.groups.my;
        },
        async removeGroupMember(user: GroupUser, groupId: number) {
            const {data} = await axios.post(`/groups/kick/${groupId}`, user);
            this.group = data.data.group;
        },

        async inviteUser(invitation: {group_id: number, user_id: number}) {
            const {data} = await axios.post(`/groups/invite/${invitation.group_id}`, invitation);
            this.group = data.data.group;
        },

        async suspendGroupMember(user: GroupUser, groupId: number) {
            const {data} = await axios.post(`/groups/suspend/${groupId}`, user);
            this.group = data.data.group;
        },
    },
});
