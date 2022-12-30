import axios from 'axios';
import {defineStore} from 'pinia';
import {Group, GroupPage, NewGroup, NewGroupMessage, Rank} from 'resources/types/group';

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
        async removeGroupMember(userId: number, groupId: number) {
            const {data} = await axios.post(`/groups/kick/${groupId}`, {id: userId});
            this.group = data.data.group;
        },

        async inviteUser(invitation: {group_id: number, user_id: number}) {
            const {data} = await axios.post(`/groups/invite/${invitation.group_id}`, invitation);
            this.group = data.data.group;
        },

        async suspendGroupMember(userId: number, groupId: number) {
            const {data} = await axios.post(`/groups/suspend/${groupId}`, {id: userId});
            this.group = data.data.group;
        },
        async getBlockedUsers(groupId: number) {
            const {data} = await axios.get(`/groups/blocked/${groupId}`);
            return data.blockedUsers;
        },
        async unblockUser(groupId: number, userId: number) {
            const {data} = await axios.post(`/groups/unblock/${groupId}`, {userId: userId});
            return data.data.blockedUsers;
        },

        /*
        Group roles
        */
        async fetchRoles(groupId: number) {
            const {data} = await axios.get(`/groups/roles/${groupId}`);
            return data.data;
        },
        async updateRoleName(groupId: number, roleId: number, role: {name: string}) {
            const {data} = await axios.put(`groups/roles/${groupId}/update/${roleId}/name`, role);
            this.group = data.data.group;
            return data.data.roles;
        },
        async updateRoles(groupId: number, roles: Rank[]) {
            const {data} = await axios.put(`groups/roles/${groupId}/update/`, roles);
            this.group = data.data.group;
            return data.data.roles;
        },
        async createRole(groupId: number, role: {name: string}) {
            const {data} = await axios.post(`groups/roles/${groupId}`, role);
            this.group = data.data.group;
            return data.data.roles;
        },
        async deleteRole(groupId: number, roleId: number) {
            const {data} = await axios.delete(`groups/roles/${groupId}/delete/${roleId}`);
            this.group = data.data.group;
            return data.data.roles;
        },
        async updateGroupUserRole(groupId: number, groupUserId: number, roleId: number) {
            const {data} = await axios.put(`groups/roles/${groupId}/user/${groupUserId}/role/${roleId}`);
            this.group = data.data.group;
        },
        async rankUp(groupId: number, roleId: number) {
            const {data} = await axios.put(`groups/roles/${groupId}/role/${roleId}/up`);
            return data.data.roles;
        },
        async rankDown(groupId: number, roleId: number) {
            const {data} = await axios.put(`groups/roles/${groupId}/role/${roleId}/down`);
            return data.data.roles;
        },

        /*
        Group messages
        */
        async getMessages(groupId: number) {
            const {data} = await axios.get(`/groups/${groupId}/messages`);
            return data.data;
        },
        async postMessage(groupId: number, groupMessage: NewGroupMessage) {
            const {data} = await axios.post(`/groups/${groupId}/messages`, groupMessage);
            return data.data.messages;
        },
        async deleteMessage(groupId: number, groupMessageId: number) {
            const {data} = await axios.delete(`/groups/${groupId}/messages/${groupMessageId}`);
            return data.data.messages;
        },
        async fetchGroupMessagesByDateRange(groupId: number, dateRange: Date) {
            const {data} = await axios.put(`/admin/groups/${groupId}/messages/daterange`, {date: dateRange});
            return data.data.messages;
        },

        /*
        Transfer ownership
        */
        async transferOwnership(groupId: number, newOwnerId: number) {
            const {data} = await axios.put(`groups/${groupId}/transfer/${newOwnerId}`);
            this.group = data.data.group;
        },
    },
});
