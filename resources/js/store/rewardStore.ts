import axios from 'axios';
import {defineStore} from 'pinia';
import {successToast} from '/js/services/toastService';
import {Reward} from 'resources/types/reward';
import { setUser } from '../services/userService';

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
            setUser(data.data.user);
        },

        async deleteInstance (rewardObj: Reward) {
            await axios.put('/reward/delete', rewardObj);
        },
    },
});
