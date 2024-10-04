import type {UserSearch} from 'resources/types/global';
import type {User} from 'resources/types/user';
import axios from 'axios';
import { computed, ref } from 'vue';
import router from '/js/router/router';
import {changeLang} from '../services/languageService';
import { getUnread } from './messageService';
import { Friend } from 'resources/types/friend';

export const user = ref<User | null>();
export const authenticated = computed(() => user.value !== null);
export const isAdmin = computed(() => user.value ? user.value.admin : false);
export const isGuest = computed(() => user.value && user.value.guest);

// * Authentication
export const logout = async() => {
    await axios.post('/logout');
    setUser(null);
    router.push('/').catch(() => {
        router.forward();
    });
}
export const getMe = async() => {
    const {data} = await axios.get('/me');
    setUser(data.user);
    if (data.user) getUnread();
}
export const setUser = async(userToSet: User | null) => {
    user.value = userToSet;
    if (userToSet) setLanguage(userToSet.language);
    // Dynamic import to prevent circular dependencies
    const { fetchDismissedBanners } = await import('/js/components/maintenance/maintenanceBannerLogic');
    fetchDismissedBanners();
}
export const updateUserFriends = (friends: Friend[]) => {
    if(!user.value) return;
    user.value.friends = friends;
}
const setLanguage = (lang: string) => {
    if (lang !== 'en' && lang !== 'nl') return;
    changeLang(lang);
}

export const continueGuestAccount = async() => {
    const localToken = localStorage.getItem('guestToken');
    if (!localToken) return; //TODO Should this throw some kind of error? The button should not appear when there is no token #865
    const {data} = await axios.post('/guest-account/continue', {localToken});
    if (!data.user) return;
    setUser(data.user);
    router.push('/dashboard');
}

export const searchUser = async(searchValue: UserSearch): Promise<User[]> => {
    const {data} = await axios.post('/search', searchValue);
    return data.data;
}