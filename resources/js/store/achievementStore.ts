import axios from 'axios';
import {defineStore} from 'pinia';
import {Achievement, NewAchievement} from 'resources/types/achievement';

export const useAchievementStore = defineStore('achievement', {
    actions: {
        async getAllAchievements() {
            const {data} = await axios.get('/admin/achievements');
            return data.data;
        },
        async newAchievement(achievement: NewAchievement) {
            const {data} = await axios.post('/admin/achievements', achievement);
            return data.data.achievements;
        },
        async editAchievement(achievement: Achievement) {
            const {data} = await axios.put('/admin/achievements/' + achievement.id, achievement);
            return data.data.achievements;
        },
    },
});
