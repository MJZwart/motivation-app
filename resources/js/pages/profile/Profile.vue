<template>
    <div>
        <Loading v-if="loading || userProfile == null" />
        <div v-else class="w-50-flex center">
            <div>
                <h2>{{ userProfile.username }}</h2>
                <div v-if="userProfile.suspended">
                    {{
                        $t('suspended-until-reason', [
                            parseDateTime(userProfile.suspended.until, true),
                            userProfile.suspended.reason,
                        ])
                    }}
                </div>
                <div v-if="notLoggedUser" class="d-flex profile-actions">
                    <Tooltip :text="$t('message-user')">
                        <Icon :icon="MAIL" class="mail-icon" @click="sendMessage" />
                    </Tooltip>
                    <span v-if="!isConnection">
                        <Tooltip :text="$t('send-friend-request')">
                            <Icon :icon="FRIEND" class="friend-icon" @click="sendFriendRequest" />
                        </Tooltip>
                    </span>
                    <Tooltip :text="$t('block-user')">
                        <Icon :icon="LOCK" class="block-icon lock-icon red" @click="blockUser" />
                    </Tooltip>
                    <Tooltip :text="$t('report-user')">
                        <Icon :icon="REPORT" class="report-icon red" @click="reportUser" />
                    </Tooltip>
                    <span v-if="user && user.admin && !userProfile.suspended">
                        | {{ $t('admin-actions') }}:
                        <Tooltip :text="$t('suspend-user')">
                            <Icon :icon="BAN" class="ban-icon red" @click="suspendUser" />
                        </Tooltip>
                    </span>
                </div>
                <p class="silent">{{ $t('member-since') }}: {{ parseDateTime(userProfile.created_at) }}</p>
                <AchievementsCard v-if="userProfile.achievements" :achievements="userProfile.achievements" :tutorial="false" />
            </div>
            <div v-if="userProfile.rewardObj">
                <RewardCard
                    class="summary-tab"
                    :reward="userProfile.rewardObj"
                    :userReward="false"
                    :rewardType="userProfile.rewardObj.rewardType"
                    :tutorial="false"
                />
            </div>
            <div v-if="userProfile.friends">
                <FriendsCard :message="false" :friends="userProfile.friends" />
            </div>
            <div v-if="userProfile.timeline">
                <Timeline :user-id="userProfile.id" />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref, computed, watch} from 'vue';
import AchievementsCard from '/js/pages/overview/components/AchievementsCard.vue';
import RewardCard from '/js/pages/dashboard/components/reward/RewardCard.vue';
import ReportUser from '/js/pages/messages/components/ReportUser.vue';
import FriendsCard from '/js/pages/social/components/FriendsCard.vue';
import SuspendUserModal from '/js/pages/admin/components/SuspendUserModal.vue';
import ConfirmBlockModal from '/js/pages/messages/components/ConfirmBlockModal.vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useUserStore} from '/js/store/userStore';
import {useFriendStore} from '/js/store/friendStore';
import {parseDateTime} from '/js/services/dateService';
import {breadcrumbsVisible} from '/js/services/breadcrumbService';
import type {NewSuspension, StrippedUser, User, UserProfile} from 'resources/types/user';
import type {FriendRequests, Friend} from 'resources/types/friend';
import {MAIL, FRIEND, LOCK, REPORT, BAN} from '/js/constants/iconConstants';
import {useAdminStore} from '/js/store/adminStore';
import Timeline from '/js/pages/overview/components/Timeline.vue';
import {formModal, sendMessageModal, showModal} from '/js/components/modal/modalService';
import {getNewSuspension} from '/js/helpers/newInstance';

const route = useRoute();
const userStore = useUserStore();
const friendStore = useFriendStore();
const adminStore = useAdminStore();

/** Setup the user profile on page load */
const loading = ref(true);
const userProfile = ref<UserProfile | null>(null);

onMounted(async () => {
    await getUserProfile();
    breadcrumbsVisible.value = true;
    loading.value = false;
});

/**
 * Fetches the user profile by route params, toggles the loading
 * animation accordingly.
 */
async function getUserProfile() {
    if (!route.params.id) return;
    loading.value = true;
    const {data} = await axios.get('/profile/' + route.params.id);
    userProfile.value = data.data;
    loading.value = false;
}

const user = computed<User | null>(() => userStore.user);
const requests = computed<FriendRequests | null>(() => friendStore.requests);

// eslint-disable-next-line complexity
const isConnection = computed(() => {
    if (!user.value) return false;
    if (userProfile.value === null) return;
    const ids = [];
    if (requests.value) {
        if (requests.value.outgoing) ids.push(...requests.value.outgoing.map(request => request.id));
        if (requests.value.incoming) ids.push(...requests.value.incoming.map(request => request.id));
    }
    if (user.value.friends) ids.push(...user.value.friends.map((friend: Friend) => friend.id));
    return ids.includes(userProfile.value.id);
});
/** Checked if this user profile is not the user currently logged in, so you can't send a request to yourself */
const notLoggedUser = computed(() => {
    if (!user.value) return false;
    return route.params.id != user.value.id.toString();
});
function sendFriendRequest() {
    friendStore.sendRequest(route.params.id as string);
}
function sendMessage() {
    if (!userProfile.value) return;
    sendMessageModal(userProfile.value?.username, userProfile.value?.id);
}
function reportUser() {
    if (!userProfile.value) return;
    showModal({user: userProfile.value}, ReportUser, 'report-user');
}
async function blockUser() {
    // @ts-ignore Modal shenanigans
    formModal(userProfile.value, ConfirmBlockModal, submitBlockUser, 'confirm-block');
}
async function submitBlockUser({user, hideMessages}: {user: StrippedUser, hideMessages: boolean}) {
    await userStore.blockUser(user.id, {'hideMessages': hideMessages});
    await getUserProfile();
}
function suspendUser() {
    // @ts-ignore Modal shenanigans
    formModal(getNewSuspension(userProfile.value?.id), SuspendUserModal, submitSuspendUser, 'suspend-user');
}
async function submitSuspendUser(userSuspension: NewSuspension) {
    await adminStore.suspendUser(userSuspension);
    await getUserProfile();
}
watch(
    () => route.params.id,
    () => {
        if (route.params.id) getUserProfile();
    },
);
</script>

<style>
.profile-grid {
    display: grid;
    gap: 10px;
}
.left-column {
    grid-row-start: 1;
    grid-column-start: 1;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.right-column {
    grid-column-start: 2;
    grid-column-end: 4;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
@media (max-width: 767px) {
    .profile-grid {
        display: flex;
        flex-direction: column;
    }
}
</style>
