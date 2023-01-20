import axios from 'axios';
import {defineStore} from 'pinia';
import {useUserStore} from './userStore';
import type {
    CharExpGain, 
    ExperiencePoint, 
    Overview, ReportedUser, 
    VillageExpGain, 
    ActionFilters,
    Actions} from 'resources/types/admin';
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
            axios.get('/admin/isadmin');
        },

        /**
         * Fetches an overview of numbers related to the site, like users, new bug reports, etc.
         */
        async getOverview(): Promise<Overview> {
            const {data} = await axios.get('/admin/overview');
            return data.overview;
        },
        async sendNotification(notification: NewNotification): Promise<void> {
            await axios.post('/notifications/all', notification);
        },
        async fetchConversation(id: number): Promise<ReportedConversation | null> {
            const {data} = await axios.get(`/admin/conversation/${id}`);
            return data.data;
        },

        // * Bug reports
        async getBugReports(): Promise<BugReport[]> {
            const {data} = await axios.get('/admin/bugreport');
            return data.data;
        },
        async updateBugReport(bugReport: BugReport): Promise<BugReport[]> {
            const {data} = await axios.put('/admin/bugreport/' + bugReport.id, bugReport);
            return data.data.bugReports;
        },

        // * Balancing
        async getExperiencePoints(): Promise<ExperiencePoint[]> {
            const {data} = await axios.get('admin/experience_points');
            return data.data;
        },
        async updateExpPoints(experiencePoints: ExperiencePoint[]): Promise<ExperiencePoint[]> {
            const {data} = await axios.put('/admin/experience_points', experiencePoints);
            return data.data.experience_points;
        },
        async addNewLevel(newLevel: ExperiencePoint): Promise<ExperiencePoint[]> {
            const {data} = await axios.post('/admin/experience_points', newLevel);
            return data.data.experience_points;
        },
        async getCharacterExpGain(): Promise<CharExpGain[]> {
            const {data} = await axios.get('admin/character_exp_gain');
            return data.data;
        },
        async updateCharExpGain(charExpGain: CharExpGain[]): Promise<CharExpGain[]> {
            const {data} = await axios.put('/admin/character_exp_gain', charExpGain);
            return data.data.balancing;
        },
        async getVillageExpGain(): Promise<VillageExpGain[]> {
            const {data} = await axios.get('admin/village_exp_gain');
            return data.data;
        },
        async updateVillageExpGain(villageExpGain: VillageExpGain[]): Promise<VillageExpGain[]> {
            const {data} = await axios.put('/admin/village_exp_gain', villageExpGain);
            return data.data.balancing;
        },

        // * Reported / suspended users
        async getReportedUsers(): Promise<ReportedUser[]> {
            const {data} = await axios.get('/admin/reported_users');
            return data.reportedUsers;
        },
        /**
         * Suspends a user account for x amount of days, or indefinite
         */
        async suspendUser(suspension: NewSuspension): Promise<ReportedUser[]> {
            const {data} = await axios.post(`/admin/suspend/${suspension.user_id}`, suspension);
            return data.data.reported_users;
        },
        async closeReport(report: ReportedUser): Promise<ReportedUser[]> {
            const {data} = await axios.post(`/admin/reported_users/${report.id}`);
            return data.reportedUsers;
        },
        async getSuspendedUsers(): Promise<SuspendedUser[]> {
            const {data} = await axios.get('/admin/suspendedusers');
            return data.suspended_users;
        },
        async editSuspension(userSuspension: SuspendedUser): Promise<SuspendedUser[]> {
            const {data} = await axios.post(`/admin/editsuspension/${userSuspension.id}`, userSuspension);
            return data.suspended_users;
        },

        // * Feedback
        async getFeedback(): Promise<Feedback[]>
        {
            const {data} = await axios.get('/admin/feedback');
            return data.feedback;
        },
        async toggleArchiveFeedback(id: number): Promise<Feedback[]>
        {
            const {data} = await axios.post(`/admin/feedback/archive/${id}`);
            return data.data.feedback;
        },

        // * Action filters
        /**
         * Gets all applicable filters for action tracking
         */
        async getActionFilters(): Promise<ActionFilters>
        {
            const {data} = await axios.get('/admin/action/filters');
            return data;
        },
        /**
         * Gets all actions that fall under the given filters
         */
        async getActionsWithFilters(filters: ActionFilters): Promise<Actions[]>
        {
            const {data} = await axios.post('/admin/action/filters', filters);
            return data.data;
        },
    },
});