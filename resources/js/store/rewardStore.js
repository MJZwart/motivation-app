import axios from 'axios';
import {defineStore} from 'pinia';
import {successToast} from '/js/services/toastService';
import {useUserStore} from './userStore';

export const useRewardStore = defineStore('reward', {
    state: () => {
        return {
            /** @type import('resources/types/reward').Reward | null */
            rewardObj: null,
            /** @type Array<import('resources/types/reward').Reward> | null */
            villages: null,
            /** @type Array<import('resources/types/reward').Reward> | null */
            characters: null,
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
            const {data} = await axios.get('/rewards/all');
            this.characters = data.rewards.characters;
            this.villages = data.rewards.villages;
        },

        /**
         * @param {import('resources/types/reward').Reward} rewardObj
         */
        async updateRewardObjName (rewardObj) {
            await axios.put('/reward/update', rewardObj);
        },

        /**
         * @param {import('resources/types/reward').Reward} rewardObj
         */
        async activateInstance (rewardObj) {
            const {data} = await axios.put('/reward/activate', rewardObj);
            successToast(data.message);
            const userStore = useUserStore();
            userStore.user = data.user;
        },

        /**
         * @param {import('resources/types/reward').Reward} rewardObj
         */
        async deleteInstance (rewardObj) {
            await axios.put('/reward/delete', rewardObj);
        },
    },
});
