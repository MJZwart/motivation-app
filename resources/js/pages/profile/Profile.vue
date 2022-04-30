<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="profile-grid">
            <div class="right-column">
                <h2>{{userProfile.username}}</h2>
                <div v-if="notLoggedUser" class="d-flex">
                    <Tooltip :text="$t('message-user')">
                        <FaIcon 
                            icon="envelope"
                            class="icon small"
                            @click="sendMessage" />
                    </Tooltip>
                    <span v-if="!isConnection">
                        <Tooltip :text="$t('send-friend-request')">
                            <FaIcon 
                                icon="user-plus"
                                class="icon small"
                                @click="sendFriendRequest" />
                        </Tooltip>
                    </span>
                    <Tooltip :text="$t('block-user')">
                        <FaIcon 
                            icon="ban"
                            class="icon small red"
                            @click="blockUser" />
                    </Tooltip>
                    <Tooltip :text="$t('report-user')">
                        <FaIcon 
                            :icon="['far', 'flag']"
                            class="icon small red"
                            @click="reportUser" />
                    </Tooltip>
                </div>
                <p class="silent">{{ $t('member-since') }}: {{userProfile.created_at}}</p>
                <AchievementsCard v-if="userProfile.achievements" :achievements="userProfile.achievements" />
            </div>
            <div class="left-column">
                <RewardCard v-if="userProfile.rewardObj" class="summary-tab" 
                            :reward="userProfile.rewardObj" :userReward="false" 
                            :rewardType="userProfile.rewardObj.rewardType" />
                <FriendsCard :manage="false" :message="false" :friends="userProfile.friends" />
            </div>
            <Modal :show="showSendMessageModal" :footer="false" :header="false" @close="closeSendMessageModal">
                <SendMessage :user="userProfile" @close="closeSendMessageModal" />
            </Modal>
            <Modal :show="showReportUserModal" :footer="false" :header="false" @close="closeReportUserModal">
                <ReportUser :user="userProfile" @close="closeReportUserModal" />
            </Modal>
        </div>

    </div>
</template>


<script setup>
import {onMounted, ref, computed, watch} from 'vue';
import AchievementsCard from '/js/pages/overview/components/AchievementsCard.vue';
import RewardCard from '/js/pages/dashboard/components/RewardCard.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import ReportUser from '/js/pages/messages/components/ReportUser.vue';
import FriendsCard from '/js/pages/dashboard/components/FriendsCard.vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useUserStore} from '/js/store/userStore';
import {useFriendStore} from '/js/store/friendStore';

import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope
const route = useRoute();
const userStore = useUserStore();
const friendStore = useFriendStore();

const userProfile = ref({});
onMounted(async() => {
    await getUserProfile();
    loading.value = false;
});
const loading = ref(true);
const showSendMessageModal = ref(false);
const showReportUserModal = ref(false);

const user = computed(() => userStore.user);
const requests = computed(() => friendStore.requests);

// eslint-disable-next-line no-unused-vars
const isConnection = computed(() => {
    const ids = [];
    if (requests.value) {
        if (requests.value.outgoing)
            ids.push(...requests.value.outgoing.map(request => request.id));
        if (requests.value.incoming)
            ids.push(...requests.value.incoming.map(request => request.id));
    }
    if (user.value.friends)
        ids.push(...user.value.friends.map(friend => friend.id));
    return ids.includes(userProfile.value.id);
});
/** Checked if this user profile is not the user currently logged in, so you can't send a request to yourself */
const notLoggedUser = computed(() => {
    return route.params.id != user.value.id;
});

async function getUserProfile() {
    if (!route.params.id) return;
    loading.value = true;
    const {data} = await axios.get('/profile/' + route.params.id);
    userProfile.value = data.data;
    loading.value = false;
}
function sendFriendRequest() {
    friendStore.sendRequest(route.params.id);
}
function sendMessage() {
    showSendMessageModal.value = true;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}
function reportUser() {
    showReportUserModal.value = true;
}
function closeReportUserModal() {
    showReportUserModal.value = false;
}
function blockUser() {
    if (confirm(t('block-user-confirmation', {user: userProfile.value.username}))) {
        userStore.blockUser(route.params.id);
    }
}
watch(
    () => route.params.id,
    () => {
        if (route.params.id) getUserProfile()
    },
);
</script>


<style>
.profile-grid{
    display: grid;
    gap:10px;
}
.left-column{
    grid-row-start: 1;
    grid-column-start: 1;
    display: flex;
    flex-direction: column;
    gap:10px;
}
.right-column{
    grid-column-start: 2;
    grid-column-end: 4;
    display: flex;
    flex-direction: column;
    gap:10px;
}
@media (max-width:767px){
    .profile-grid{
        display: flex;
        flex-direction: column;
    }
}
</style>