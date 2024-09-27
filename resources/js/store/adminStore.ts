import {defineStore} from 'pinia';
import {useUserStore} from './userStore';

export const useAdminStore = defineStore('admin', {
    getters: {
        // TODO Remove when userStore is fixed
        isAdmin() {
            const userStore = useUserStore();
            return userStore.isAdmin;
        },
    },
});