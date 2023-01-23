import axios from 'axios';
import {defineStore} from 'pinia';
import {successToast} from '/js/services/toastService';
import {useUserStore} from './userStore';
import {Reward} from 'resources/types/reward';

export const useRewardStore = defineStore('reward', {
    state: () => {
        return {
            rewardObj: null as Reward | null,
        }
    },
    actions: {
        async fetchAllRewardInstances() {
            const {data} = await axios.get('/reward/all');
            return data.rewards;
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
