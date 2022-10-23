import axios from 'axios';
import {defineStore} from 'pinia';
import {useAchievementStore} from './achievementStore';
import {useUserStore} from './userStore';

export const useAdminStore = defineStore('admin', {
    state: () => {
        return {
            /** @type Array<import('resources/types/admin').ExperiencePoint> | [] */
            experiencePoints: [],
            /** @type Array<import('resources/types/admin').CharExpGain> | [] */
            charExpGain: [],
            /** @type Array<import('resources/types/admin').VillageExpGain> | [] */
            villageExpGain: [],
            /** @type Array<import('resources/types/admin').ReportedUser> | null */
            reportedUsers: null,
            /** @type Array<import('resources/types/bug').BugReport> | null */
            bugReports: null,
            /** @type Array<import('resources/types/user').BannedUser> | null */
            bannedUsers: null,
        }
    },
    getters: {
        isAdmin() {
            const userStore = useUserStore();
            return userStore.isAdmin();
        },
        getBalancing(state) {
            return {
                'experience_points': state.experiencePoints,
                'character_exp_gain': state.charExpGain,
                'village_exp_gain': state.villageExpGain,
            };
        },
    },
    actions: {
        checkAdmin() {
            axios.get('/isadmin');
        },
        async getAdminDashboard() {
            const {data} = await axios.get('/admin/dashboard');
            const achievementStore = useAchievementStore();
            achievementStore.achievements = data.achievements;
            achievementStore.achievementTriggers = data.achievementTriggers;
            this.bugReports = data.bugReports;
            this.experiencePoints = data.balancing.experience_points;
            this.charExpGain = data.balancing.character_exp_gain;
            this.villageExpGain = data.balancing.village_exp_gain;
        },

        async getReportedUsers() {
            const {data} = await axios.get('/admin/reported_users');
            this.reportedUsers = data.reportedUsers;
        },
        /**
         * @param {import('resources/types/notification').NewNotification} notification
         */
        async sendNotification(notification) {
            await axios.post('/notifications/all', notification);
        },
        /**
         * @param {Number} id
         * @returns {Promise<import('resources/types/message').ReportedConversation | null>}
         */
        async fetchConversation(id) {
            const {data} = await axios.get(`/admin/conversation/${id}`);
            return data.data;
        },
        /**
         * @param {import('resources/types/bug').BugReport} bugReport
         */
        async updateBugReport(bugReport) {
            const {data} = await axios.put('/bugreport/' + bugReport.id, bugReport);
            this.bugReports = data.data.bugReports;
        },

        //Balancing
        /**
         * @param {Array<import('resources/types/admin').ExperiencePoint>} experiencePoints
         */
        async updateExpPoints(experiencePoints) {
            const {data} = await axios.put('/admin/experience_points', experiencePoints);
            this.experiencePoints = data.data.experience_points;
        },
        /**
         * @param {import('resources/types/admin').ExperiencePoint} newLevel
         */
        async addNewLevel(newLevel) {
            const {data} = await axios.post('/admin/experience_points', newLevel);
            this.experiencePoints = data.data.experience_points;
        },
        /**
         * @param {Array<import('resources/types/admin').CharExpGain>} charExpGain
         */
        async updateCharExpGain(charExpGain) {
            const {data} = await axios.put('/admin/character_exp_gain', charExpGain);
            this.charExpGain = data.data;
        },
        /**
         * @param {Array<import('resources/types/admin').VillageExpGain>} villageExpGain
         */
        async updateVillageExpGain(villageExpGain) {
            const {data} = await axios.put('/admin/village_exp_gain', villageExpGain);
            this.villageExpGain = data.data;
        },

        /**
         * Suspends a user account for x amount of days, or indefinite
         * @param {Number} userId 
         * @param {Object} suspension 
         */
        async suspendUser(userId, suspension) {
            const {data} = await axios.post(`/admin/suspend/${userId}`, suspension);
            this.reportedUsers = data.data.reported_users;
            this.bannedUsers = data.data.banned_users;
        },
        /**
         * Gets all banned users
         */
        async getBannedUsers() {
            const {data} = await axios.get('/admin/bannedusers');
            this.bannedUsers = data.banned_users;
        },

        /**
         * Ends the suspension of a given user manually.
         * @param {import('resources/types/user').BannedUser} userBan 
         */
        async editBan(userBan) {
            const {data} = await axios.post(`/admin/editban/${userBan.id}`, userBan);
            this.bannedUsers = data.banned_users;
        },

        /**
         * Closes the UserReport (trashes it in the baclend)
         * @param {hurrdurr add type}
         */
        async closeReport(report) {
            const {data} = await axios.post(`/admin/reported_users/${report.id}`);
            this.reportedUsers = data.reportedUsers;
        },

        /**
         * Fetches all the feedback from back-end
         * @returns Array<import('resources/types/feedback').Feedback>
         */
        async getFeedback() {
            const {data} = await axios.get('/admin/feedback');
            return data.feedback;
        },
        /**
         * Toggles the feedback's archived status
         * @param {Number} id
         * @returns Array<import('resources/types/feedback').Feedback>
         */
        async toggleArchiveFeedback(id) {
            const {data} = await axios.post(`/admin/feedback/archive/${id}`);
            return data.data.feedback;
        },

        /**
         * Fetches an overview of numbers related to the site, like users, new bug reports, etc.
         * @returns Object
         */
        async getOverview() {
            const {data} = await axios.get('/admin/overview');
            return data.overview;
        },
    },
});