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
    getters: {
        isAdmin() {
            const userStore = useUserStore();
            return userStore.isAdmin;
        },
    },
    actions: {
        /**
         * Sends a single call to server to check if the user is admin. Will send an error if not,
         * which the interceptor will pick up
         */
        checkAdmin() {
            axios.get('/isadmin');
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
        /**
         * Get the experience points table
         * @returns {ExperiencePoint[]}
         */
        async getExperiencePoints() {
            const {data} = await axios.get('admin/experience_points');
            return data.data;
        },
        /**
         * Get the experience points table
         * @returns {ExperiencePoint[]}
         */
        async updateExpPoints(experiencePoints: ExperiencePoint[]) {
            const {data} = await axios.put('/admin/experience_points', experiencePoints);
            return data.data.experience_points;
        },
        /**
         * Get the experience points table
         * @returns {ExperiencePoint[]}
         */
        async addNewLevel(newLevel: ExperiencePoint) {
            const {data} = await axios.post('/admin/experience_points', newLevel);
            return data.data.experience_points;
        },
        /**
         * Get the exp gain table for characters
         * @returns {CharExpGain[]}
         */
        async getCharacterExpGain() {
            const {data} = await axios.get('admin/character_exp_gain');
            return data.data;
        },
        /**
         * Updates the exp gain table for characters
         * @param {CharExpGain[]} charExpGain
         * @returns {CharExpGain[]}
         */
        async updateCharExpGain(charExpGain: CharExpGain[]) {
            const {data} = await axios.put('/admin/character_exp_gain', charExpGain);
            return data.data.balancing;
        },
        /**
         * Get the exp gain table for villages
         * @returns {VillageExpGain[]}
         */
        async getVillageExpGain() {
            const {data} = await axios.get('admin/village_exp_gain');
            return data.data;
        },
        /**
         * Updates the exp gain table for villages
         * @param {VillageExpGain[]} villageExpGain
         * @returns {VillageExpGain[]}
         */
        async updateVillageExpGain(villageExpGain: VillageExpGain[]) {
            const {data} = await axios.put('/admin/village_exp_gain', villageExpGain);
            return data.data.balancing;
        },

        // * Reported / suspended users
        /**
         * Gets the reported users
         */
        async getReportedUsers() {
            const {data} = await axios.get('/admin/reported_users');
            return data.reportedUsers;
        },
        /**
         * Suspends a user account for x amount of days, or indefinite
         */
        async suspendUser(suspension: NewSuspension) {
            const {data} = await axios.post(`/admin/suspend/${suspension.user_id}`, suspension);
            return data.data.reported_users;
        },
        /**
         * Gets all suspended users
         */
        async getSuspendedUsers() {
            const {data} = await axios.get('/admin/suspendedusers');
            return data.suspended_users;
        },

        /**
         * Ends the suspension of a given user manually.
         */
        async editSuspension(userSuspension: SuspendedUser) {
            const {data} = await axios.post(`/admin/editsuspension/${userSuspension.id}`, userSuspension);
            return data.suspended_users;
        },

        /**
         * Closes the UserReport (trashes it in the backend)
         */
        async closeReport(report: ReportedUser) {
            const {data} = await axios.post(`/admin/reported_users/${report.id}`);
            return data.reportedUsers;
        },

        // * Feedback
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

        // * Action filters
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