import axios from 'axios';
import {defineStore} from 'pinia';
import {Achievement, NewAchievement} from 'resources/types/achievement';

export const useAchievementStore = defineStore('achievement', {
    state: () => {
        return {
            achievementsByUser: null as Achievement[] | null,
            achievements: null as Achievement[] | null,
        };
    },
    actions: {
        async newAchievement(achievement: NewAchievement) {
            const {data} = await axios.post('/admin/achievements', achievement);
            this.achievements = data.data.achievements;
        },
        async editAchievement(achievement: Achievement) {
            const {data} = await axios.put('/admin/achievements/' + achievement.id, achievement);
            this.achievements = data.data.achievements;
        },
    },
});
