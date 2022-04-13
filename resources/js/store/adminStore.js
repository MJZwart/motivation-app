// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useAchievementStore} from './achievementStore';
import {useMainStore} from './store';
import {useUserStore} from './userStore';

export const useAdminStore = defineStore('admin', {
    state: () => {
        return {
            experiencePoints: null,
            charExpGain: null,
            villageExpGain: null,
            reportedUsers: null,
            conversation: null,
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
            const data = await axios.get('/admin/dashboard');
            const achievementStore = useAchievementStore();
            achievementStore.achievements = data.achievements;
            achievementStore.achievementTriggers = data.achievementTriggers;
            this.bugReports = data.bugReports;
            this.experiencePoints = data.balancing.experience_points;
            this.charExpGain = data.balancing.character_exp_gain;
            this.villageExpGain = data.balancing.village_exp_gain;
            this.reportedUsers = data.reportedUsers;
        },
        async sendNotification(notification) {
            const data = await axios.post('/notifications/all', notification);
            this.addToast(data.message);
        },
        async fetchConversation(id) {
            const data = await axios.get(`/admin/conversation/${id}`);
            this.conversation = data.data;
        },
        async updateBugReport(bugReport) {
            const data = await axios.put('/bugreport/' + bugReport.id, bugReport);
            this.bugReports = data.data;
            this.addToast(data.message);
        },

        //Balancing
        async updateExpPoints(experiencePoints) {
            const data = await axios.put('/admin/experience_points', experiencePoints);
            this.experiencePoints = data.data;
            this.addToast(data.message);
        },
        async addNewLevel(newLevel) {
            const data = await axios.post('/admin/experience_points', newLevel);
            this.experiencePoints = data.data;
            this.addToast(data.message);
        },
        async updateCharExpGain(charExpGain) {
            const data = await axios.put('/admin/character_exp_gain', charExpGain);
            this.charExpGain = data.data;
            this.addToast(data.message);
        },
        async updateVillageExpGain(villageExpGain) {
            const data = await axios.put('/admin/village_exp_gain', villageExpGain);
            this.villageExpGain = data.data;
            this.addToast(data.message);
        },
        addToast(toast) {
            const mainStore = useMainStore();
            mainStore.addToast(toast);
        },
    },
});