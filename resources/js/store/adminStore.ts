import axios from 'axios';
import {defineStore} from 'pinia';
import {useUserStore} from './userStore';
import type {CharExpGain, ExperiencePoint, Overview, ReportedUser, VillageExpGain, ActionFilters} from 'resources/types/admin';
import type {BugReport} from 'resources/types/bug';
import type {Feedback} from 'resources/types/feedback';
import type {ReportedConversation} from 'resources/types/message';
import type {NewNotification} from 'resources/types/notification';
import type {SuspendedUser, NewSuspension} from 'resources/types/user';

export const useAdminStore = defineStore('admin', {
    state: () => {
        return {
            reportedUsers: null as ReportedUser[] | null,
            bugReports: null as BugReport[] | null,
            suspendedUsers: null as SuspendedUser[] | null,
        }
    },
    getters: {
        isAdmin() {
            const userStore = useUserStore();
            return userStore.isAdmin;
        },
    },
    actions: {
        checkAdmin() {
            axios.get('/isadmin');
        },

        async getReportedUsers() {
            const {data} = await axios.get('/admin/reported_users');
            this.reportedUsers = data.reportedUsers;
        },
        async sendNotification(notification: NewNotification) {
            await axios.post('/notifications/all', notification);
        },
        async fetchConversation(id: number): Promise<ReportedConversation | null>
        {
            const {data} = await axios.get(`/admin/conversation/${id}`);
            return data.data;
        },

        // * Bug reports
        async getBugReports(): Promise<BugReport[]> {
            const {data} = await axios.get('/admin/bugreport');
            return data.data;
        },
        async updateBugReport(bugReport: BugReport) {
            const {data} = await axios.put('/admin/bugreport/' + bugReport.id, bugReport);
            return data.data.bugReports;
        },

        // * Balancing
        async getExperiencePoints() {
            const {data} = await axios.get('admin/experience_points');
            return data.data;
        },
        async getCharacterExpGain() {
            const {data} = await axios.get('admin/character_exp_gain');
            return data.data;
        },
        async getVillageExpGain() {
            const {data} = await axios.get('admin/village_exp_gain');
            return data.data;
        },
        async updateExpPoints(experiencePoints: ExperiencePoint[]) {
            const {data} = await axios.put('/admin/experience_points', experiencePoints);
            return data.data.experience_points;
        },
        async addNewLevel(newLevel: ExperiencePoint) {
            const {data} = await axios.post('/admin/experience_points', newLevel);
            return data.data.experience_points;
        },
        async updateCharExpGain(charExpGain: CharExpGain[]) {
            const {data} = await axios.put('/admin/character_exp_gain', charExpGain);
            return data.data.balancing;
        },
        async updateVillageExpGain(villageExpGain: VillageExpGain[]) {
            const {data} = await axios.put('/admin/village_exp_gain', villageExpGain);
            return data.data.balancing;
        },

        /**
         * Suspends a user account for x amount of days, or indefinite
         */
        async suspendUser(userId: number, suspension: NewSuspension) {
            const {data} = await axios.post(`/admin/suspend/${userId}`, suspension);
            this.reportedUsers = data.data.reported_users;
            this.suspendedUsers = data.data.suspended_users;
        },
        /**
         * Gets all suspended users
         */
        async getSuspendedUsers() {
            const {data} = await axios.get('/admin/suspendedusers');
            this.suspendedUsers = data.suspended_users;
        },

        /**
         * Ends the suspension of a given user manually.
         */
        async editSuspension(userSuspension: SuspendedUser) {
            const {data} = await axios.post(`/admin/editsuspension/${userSuspension.id}`, userSuspension);
            this.suspendedUsers = data.suspended_users;
        },

        /**
         * Closes the UserReport (trashes it in the backend)
         */
        async closeReport(report: ReportedUser) {
            const {data} = await axios.post(`/admin/reported_users/${report.id}`);
            this.reportedUsers = data.reportedUsers;
        },

        /**
         * Fetches all the feedback from back-end
         */
        async getFeedback(): Promise<Feedback[]>
        {
            const {data} = await axios.get('/admin/feedback');
            return data.feedback;
        },
        /**
         * Toggles the feedback's archived status
         */
        async toggleArchiveFeedback(id: number): Promise<Feedback[]>
        {
            const {data} = await axios.post(`/admin/feedback/archive/${id}`);
            return data.data.feedback;
        },

        /**
         * Fetches an overview of numbers related to the site, like users, new bug reports, etc.
         * @returns Object
         */
        async getOverview(): Promise<Overview>
        {
            const {data} = await axios.get('/admin/overview');
            return data.overview;
        },

        async getActionFilters()//: Promise<>
        {
            const {data} = await axios.get('/admin/action/filters');
            return data;
        },
        async getActionsWithFilters(filters: ActionFilters)//: Promise<>
        {
            const {data} = await axios.post('/admin/action/filters', filters);
            return data.data;
        },
    },
});