// @ts-nocheck
import axios from 'axios';
import {defineStore} from 'pinia';
import {useMainStore} from './store';
import {useUserStore} from './userStore';

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
            const {data} = await axios.get('/village/all');
            console.log(data);
            this.villages = data.data;
        },

        async fetchAllCharacters() {
            const {data} = await axios.get('/character/all');
            console.log(data);
            this.characters = data.data;
        },

        async fetchAllRewardInstances() {
            const {data} = await axios.get('/rewards/all');
            console.log(data);
            this.characters = data.rewards.characters;
            this.villages = data.rewards.villages;
        },

        async updateRewardObjName (rewardObj) {
            const {data} = await axios.put('/reward/update', rewardObj);
            console.log(data);
            const mainStore = useMainStore();
            mainStore.addToast(data.message);
        },

        async activateInstance (rewardObj) {
            const {data} = await axios.put('/reward/activate', rewardObj);
            console.log(data);
            const mainStore = useMainStore();
            mainStore.addToast(data.message);
            const userStore = useUserStore();
            userStore.user = data.user;
        },

        async deleteInstance (rewardObj) {
            const {data} = await axios.put('/reward/delete', rewardObj);
            console.log(data);
            const mainStore = useMainStore();
            mainStore.addToast(data.message);
        },
    },
});
