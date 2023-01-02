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
                        <Icon :icon="LOCK" class="block-icon red" @click="blockUser" />
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
            <Modal :show="showSendMessageModal" :header="false" @close="closeSendMessageModal">
                <SendMessage :user="userProfile" @close="closeSendMessageModal" />
            </Modal>
            <Modal :show="showReportUserModal" :header="false" @close="closeReportUserModal">
                <ReportUser :user="userProfile" @close="closeReportUserModal" />
            </Modal>
            <Modal 
                :show="showSuspendUserModal" 
                
                :title="`Suspend user ${userProfile.username}`"
                @close="closeSuspendUserModal">
                <SuspendUserModal :userId="userProfile.id" @close="closeSuspendUserModal" />
            </Modal>
        </div>
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref, computed, watch} from 'vue';
import AchievementsCard from '/js/pages/overview/components/AchievementsCard.vue';
import RewardCard from '/js/pages/dashboard/components/reward/RewardCard.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import ReportUser from '/js/pages/messages/components/ReportUser.vue';
import FriendsCard from '/js/pages/dashboard/components/FriendsCard.vue';
import SuspendUserModal from '/js/pages/admin/components/SuspendUserModal.vue';
import axios from 'axios';
import {useRoute} from 'vue-router';
import {useUserStore} from '/js/store/userStore';
import {useFriendStore} from '/js/store/friendStore';
import {parseDateTime} from '/js/services/dateService';
import {breadcrumbsVisible} from '/js/services/breadcrumbService';
import {useI18n} from 'vue-i18n';
import type {User, UserProfile} from 'resources/types/user';
import type {FriendRequests, Friend} from 'resources/types/friend';
import {MAIL, FRIEND, LOCK, REPORT, BAN} from '/js/constants/iconConstants';

const {t} = useI18n();
const route = useRoute();
const userStore = useUserStore();
const friendStore = useFriendStore();

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

const showSendMessageModal = ref(false);
const showReportUserModal = ref(false);
const showSuspendUserModal = ref(false);

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
    if (confirm(t('block-user-confirmation', {user: userProfile.value?.username}))) {
        userStore.blockUser(route.params.id as string);
    }
}
function suspendUser() {
    showSuspendUserModal.value = true;
}
function closeSuspendUserModal(reload: boolean) {
    showSuspendUserModal.value = false;
    if (reload) getUserProfile();
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
