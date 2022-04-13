// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';

export const useAchievementStore = defineStore('achievement', {
    state: () => {
        return {
            achievementsByUser: null,
            achievements: null,
            achievementTriggers: null,
        }
    },
    actions: {
        async newAchievement(achievement) {
            const {data} = await axios.post('/achievements', achievement);
            console.log(data);
            this.addToast(data.message);
            this.achievements = data.achievements;
        },
        async editAchievement(achievement) {
            const {data} = await axios.put('/achievements/' + achievement.id, achievement);
            console.log(data);
            this.addToast(data.message);
            this.achievements = data.achievements;
        },
        addToast(toast) {
            const mainStore = useMainStore();
            mainStore.addToast(toast);
        },
    },
});