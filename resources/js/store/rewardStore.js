// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';

export const useRewardStore = defineStore('reward', {
    state: () => {
        return {
            rewardObj: null,
            villages: null,
            characters: null,
        }
    },
    actions: {
        async fetchAllVillages() {
            const data = await axios.get('/village/all')
            this.villages = data.data;
        },

        async fetchAllCharacters() {
            const data = await axios.get('/character/all')
            this.characters = data.data;
        },

        async fetchAllRewardInstances() {
            const data = await axios.get('/rewards/all')
            this.characters = data.rewards.characters;
            this.villages = data.rewards.villages;
        },

        async updateRewardObjName (rewardObj) {
            const data = await axios.put('/reward/update', rewardObj)
            const mainStore = useMainStore();
            mainStore.addToast(data.message);
        },

        async activateInstance (rewardObj) {
            const data = await axios.put('/reward/activate', rewardObj)
            const mainStore = useMainStore();
            mainStore.addToast(data.message);
                commit('user/setUser', response.data.user, {root:true});
        },

        async deleteInstance (rewardObj) {
            const data = await axios.put('/reward/delete', rewardObj)
            const mainStore = useMainStore()
            mainStore.addToast(data.message);
        },
    },
});
