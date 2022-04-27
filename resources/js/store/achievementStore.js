// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';

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
            this.achievements = data.achievements;
        },
        async editAchievement(achievement) {
            const {data} = await axios.put('/achievements/' + achievement.id, achievement);
            this.achievements = data.achievements;
        },
    },
});