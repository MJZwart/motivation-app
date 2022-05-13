import axios from 'axios';
import {defineStore} from 'pinia';
import {useAchievementStore} from './achievementStore';
import {useUserStore} from './userStore';

export const useAdminStore = defineStore('admin', {
    state: () => {
        return {
            /** @type Array<import('resources/types/admin').ExperiencePoint> | null */
            experiencePoints: null,
            /** @type Array<import('resources/types/admin').CharExpGain> | null */
            charExpGain: null,
            /** @type Array<import('resources/types/admin').VillageExpGain> | null */
            villageExpGain: null,
            /** @type Array<import('resources/types/admin').ReportedUser> | null */
            reportedUsers: null,
            /** @type Array<import('resources/types/bug').BugReport> | null */
            bugReports: null,
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
            this.reportedUsers = data.reportedUsers;
        },
        /**
         * @param {import('resources/types/notification').Notification} notification
         */
        async sendNotification(notification) {
            await axios.post('/notifications/all', notification);
        },
        /**
         * @param {Number} id
         * @returns import('resources/types/message').Conversation | null
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
            this.bugReports = data.data;
        },

        //Balancing
        /**
         * @param {Array<import('resources/types/admin').ExperiencePoint>} experiencePoints
         */
        async updateExpPoints(experiencePoints) {
            const {data} = await axios.put('/admin/experience_points', experiencePoints);
            this.experiencePoints = data.data;
        },
        /**
         * @param {import('resources/types/admin').ExperiencePoint} newLevel
         */
        async addNewLevel(newLevel) {
            const {data} = await axios.post('/admin/experience_points', newLevel);
            this.experiencePoints = data.data;
        },
        /**
         * @param {import('resources/types/admin').CharExpGain} charExpGain
         */
        async updateCharExpGain(charExpGain) {
            const {data} = await axios.put('/admin/character_exp_gain', charExpGain);
            this.charExpGain = data.data;
        },
        /**
         * @param {import('resources/types/admin').VillageExpGain} villageExpGain
         */
        async updateVillageExpGain(villageExpGain) {
            const {data} = await axios.put('/admin/village_exp_gain', villageExpGain);
            this.villageExpGain = data.data;
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
            return data.feedback;
        },
    },
});