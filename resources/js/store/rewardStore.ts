import axios from 'axios';
import {defineStore} from 'pinia';
import {successToast} from '/js/services/toastService';
import {useUserStore} from './userStore';
import {Reward} from 'resources/types/reward';

export const useRewardStore = defineStore('reward', {
    state: () => {
        return {
            rewardObj: null as Reward | null,
            villages: [] as Reward[],
            characters: [] as Reward[],
        }
    },
    actions: {
        async fetchAllVillages() {
            const {data} = await axios.get('/village/all');
            this.villages = data.data;
        },

        async fetchAllCharacters() {
            const {data} = await axios.get('/character/all');
            this.characters = data.data;
        },

        async fetchAllRewardInstances() {
            const {data} = await axios.get('/reward/all');
            this.characters = data.rewards.characters;
            this.villages = data.rewards.villages;
        },

        async updateRewardObjName (rewardObj: Reward) {
            await axios.put('/reward/update', rewardObj);
        },

        async activateInstance (rewardObj: Reward) {
            const {data} = await axios.put('/reward/activate', rewardObj);
            successToast(data.message);
            const userStore = useUserStore();
            userStore.user = data.data.user;
        },

        async deleteInstance (rewardObj: Reward) {
            await axios.put('/reward/delete', rewardObj);
        },
    },
});
