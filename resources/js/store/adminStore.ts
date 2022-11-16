import axios from 'axios';
import {defineStore} from 'pinia';
import {CharExpGain, ExperiencePoint, Overview, ReportedUser, VillageExpGain} from 'resources/types/admin';
import {BugReport} from 'resources/types/bug';
import {Feedback} from 'resources/types/feedback';
import {ReportedConversation} from 'resources/types/message';
import {NewNotification} from 'resources/types/notification';
import {BannedUser, NewSuspension} from 'resources/types/user';
import {useAchievementStore} from './achievementStore';
import {useUserStore} from './userStore';

export const useAdminStore = defineStore('admin', {
    state: () => {
        return {
            experiencePoints: [] as ExperiencePoint[],
            charExpGain: [] as CharExpGain[],
            villageExpGain: [] as VillageExpGain[],
            reportedUsers: null as ReportedUser[] | null,
            bugReports: null as BugReport[] | null,
            bannedUsers: null as BannedUser[] | null,
        }
    },
    getters: {
        isAdmin() {
            const userStore = useUserStore();
            return userStore.isAdmin;
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
            this.bugReports = data.bugReports;
            this.experiencePoints = data.balancing.experience_points;
            this.charExpGain = data.balancing.character_exp_gain;
            this.villageExpGain = data.balancing.village_exp_gain;
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
        async updateBugReport(bugReport: BugReport) {
            const {data} = await axios.put('/admin/bugreport/' + bugReport.id, bugReport);
            this.bugReports = data.data.bugReports;
        },

        //Balancing
        async updateExpPoints(experiencePoints: ExperiencePoint[]) {
            const {data} = await axios.put('/admin/experience_points', experiencePoints);
            this.experiencePoints = data.data.experience_points;
        },
        async addNewLevel(newLevel: ExperiencePoint) {
            const {data} = await axios.post('/admin/experience_points', newLevel);
            this.experiencePoints = data.data.experience_points;
        },
        async updateCharExpGain(charExpGain: CharExpGain[]) {
            const {data} = await axios.put('/admin/character_exp_gain', charExpGain);
            this.charExpGain = data.data;
        },
        async updateVillageExpGain(villageExpGain: VillageExpGain[]) {
            const {data} = await axios.put('/admin/village_exp_gain', villageExpGain);
            this.villageExpGain = data.data;
        },

        /**
         * Suspends a user account for x amount of days, or indefinite
         */
        async suspendUser(userId: number, suspension: NewSuspension) {
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
         * Edits or manually ends the suspension of a given user.
         */
        async editBan(userBan: BannedUser) {
            const {data} = await axios.post(`/admin/editban/${userBan.id}`, userBan);
            this.bannedUsers = data.banned_users;
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
    },
});