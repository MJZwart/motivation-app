import axios from 'axios';
import type {Blocked, Login, NewUser, Register, User} from 'resources/types/user';
import { computed, ref } from 'vue';
import router from 'resources/js/router/router';
import {changeLang} from '../services/languageService';
import { fetchBanners, fetchDismissedBanners } from 'resources/js/components/maintenance/maintenanceBannerLogic';
import type {EmailSettings, PasswordSettings, ProfileSettings} from 'resources/types/settings';
import type {ChangeReward} from 'resources/types/reward';
import type {UserSearch} from 'resources/types/global';
import { taskLists } from './taskService';
import { activeReward} from './villageService';
import { hasMessages, hasNotifications } from './messageService';

export const user = ref<User | null>();
export const authenticated = computed(() => user.value !== null);
export const isAdmin = computed(() => user.value ? user.value.admin : false);

// * Authentication
/**
 * User authentication. If user login is valid but the account is otherwise invalidated,
 * instead return info the Login screen.
 */

export const login = async(cred: Login) => {
    const {data} = await axios.post('/login', cred);
    if (!data.user) router.push('/error'); //TODO Throw error that user is not set correctly
    setUser(data.user)
    fetchBanners();
    // TODO Get unread
    router.push('/dashboard');
}
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
    // if (data.user) // TODO Get unread
}
const setUser = (userToSet: User | null) => {
    user.value = userToSet;
    if (userToSet) setLanguage(userToSet.language);
    fetchDismissedBanners();
}
const setLanguage = (lang: string) => {
    if (lang !== 'en' && lang !== 'nl') return;
    changeLang(lang);
}

//* Calls
export const getUnread = async() => {
    const {data} = await axios.get('/unread');
    hasMessages.value = data.hasMessages;
    hasNotifications.value = data.hasNotifications;
}
export const getDashboard = async() => {
    const {data} = await axios.get('/user/dashboard');
    taskLists.value = data.taskLists;
    activeReward.value = data.rewardObj; // TODO Make sure the naming is correct
}
export const continueGuestAccount = async() => {
    const localToken = localStorage.getItem('guestToken');
    if (!localToken) return; //TODO Should this throw some kind of error? The button should not appear when there is no token
    const {data} = await axios.post('/guest-account/continue', {localToken});
    if (!data.user) return;
    setUser(data.user);
    router.push('/dashboard');
}

// * New user
export const register = async(newUser: Register) => {
    const {data} = await axios.post('/register', newUser);
    setUser(data.data.user);
    router.push('/dashboard');
}
export const confirmRegister = async(newUser: NewUser) => {
    const {data} = await axios.post('/register/confirm', user);
    setUser(data.data.user);
    router.push('/')
}
export const createGuestAccount = async(rewardType: {reward: string}) => {
    const {data} = await axios.post('/guest-account', rewardType);
    localStorage.setItem('guestToken',
        JSON.stringify(data.data.loginToken));
    setUser(data.data.user);
    router.push('/dashboard')
}
export const upgradeGuestAccount = async(newUser: Register, userId: number) => {
    const {data} = await axios.post('/guest-account/'+userId+'/upgrade', newUser);
    setUser(data.data.user);
    localStorage.removeItem('guestToken');
    router.push('/dashboard');
}

// * Overview
export const getOverview = async() => {
    const {data} = await axios.get('/user/overview');
    activeReward.value = data.rewardObj; //TODO Check if this is necessary.
    return data;
}
export const getTimeline = async(userId: number) => {
    const {data} = await axios.get(`/user/timeline/${userId}`);
    return data;
}

// * Settings
export const updatePassword = async(passwords: PasswordSettings) =>{
    await axios.put('/user/settings/password', passwords);
    logout();
}
export const updateEmail = async(emailSettings: EmailSettings) => {
    const {data} = await axios.put('/user/settings/email', emailSettings);
    setUser(data.data.user);
}
export const toggleTutorial = async(tutorialSettings: {show: boolean}) => {
    const {data} = await axios.put('/user/settings/tutorial', tutorialSettings);
    setUser(data.data.user);
}
export const updateSettings = async(settings: ProfileSettings) => {
    const {data} = await axios.put('/user/settings', settings);
    setUser(data.data.user);
}
export const updateLanguage = async(language: {language: string}) => {
    const {data} = await axios.put('/user/settings/language', language);
    setUser(data.data.user);
}
export const changeRewardType = async(change: ChangeReward) => {
    const {data} = await axios.put('/user/settings/rewards', change);
    setUser(data.data.user);
    activeReward.value = data.data.activeReward;
}
export const searchUser = async(searchValue: UserSearch): Promise<User[]> => {
    const {data} = await axios.post('/search', searchValue);
    return data.data;
}
export const getBlocklist = async(): Promise<Blocked[]> => {
    const {data} = await axios.get('/user/blocklist');
    return data.blockedUsers;
}
export const unblockUser = async(blocklistId: number, restoreMessages: {'restoreMessages': boolean}) => {
    const {data} = await axios.put(`/user/${blocklistId}/unblock`, restoreMessages);
    return data.data.blockedUsers;
}