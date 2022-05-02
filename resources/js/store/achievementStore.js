import axios from 'axios';
import {defineStore} from 'pinia';

export const useAchievementStore = defineStore('achievement', {
    state: () => {
        return {
            /** @type {Array<import('resources/types/achievement.js').Achievement> | null} */
            achievementsByUser: null,
            /** @type {Array<import('resources/types/achievement.js').Achievement> | null} */
            achievements: null,
            achievementTriggers: null,
        }
    },
    actions: {
        /** @param {import('resources/types/achievement.js').Achievement} achievement */
        async newAchievement(achievement) {
            const {data} = await axios.post('/achievements', achievement);
            this.achievements = data.achievements;
        },
        /** @param {import('resources/types/achievement.js').Achievement} achievement */
        async editAchievement(achievement) {
            const {data} = await axios.put('/achievements/' + achievement.id, achievement);
            this.achievements = data.achievements;
        },
    },
});