<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="profile-grid">
            <div class="right-column">
                <h2>{{userProfile.username}}</h2>
                <div v-if="notLoggedUser" class="d-flex">
                    <b-icon-envelope id="message-user" class="icon small" @click="sendMessage" />
                    <b-tooltip target="message-user">{{ $t('message-user') }}</b-tooltip>
                    <span v-if="!isConnection">
                        <b-icon-person-plus-fill id="send-friend-request" class="icon small" @click="sendFriendRequest" />
                        <b-tooltip target="send-friend-request">{{ $t('send-friend-request') }}</b-tooltip>
                    </span>
                    <b-icon-dash-circle id="block-user" class="icon small red" @click="blockUser" />
                    <b-tooltip target="block-user">{{ $t('block-user') }}</b-tooltip>
                    <b-icon-exclamation-circle id="report-user" class="icon small red" @click="reportUser" />
                    <b-tooltip target="report-user">{{ $t('report-user') }}</b-tooltip>
                </div>
                <p class="silent">{{ $t('member-since') }}: {{userProfile.created_at}}</p>
                <AchievementsCard v-if="userProfile.achievements" :achievements="userProfile.achievements" />
            </div>
            <div class="left-column">
                <RewardCard v-if="userProfile.rewardObj" class="summary-tab" 
                            :reward="userProfile.rewardObj" :userReward="false" 
                            :rewardType="userProfile.rewardObj.rewardType" />
                <FriendsCard :manage="false" :message="false" />
            </div>
            <BModal :show="showSendMessageModal" :footer="false" :header="false" @close="closeSendMessageModal">
                <SendMessage :user="userProfile" @close="closeSendMessageModal" />
            </BModal>
            <BModal :show="showReportUserModal" :footer="false" :header="false" @close="closeReportUserModal">
                <ReportUser :user="userProfile" @close="closeReportUserModal" />
            </BModal>
        </div>

    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import AchievementsCard from '../components/summary/AchievementsCard.vue';
import RewardCard from '../components/summary/RewardCard.vue';
import SendMessage from '../components/modals/SendMessage.vue';
import ReportUser from '../components/modals/ReportUser.vue';
import Loading from '../components/Loading.vue';
import FriendsCard from '../components/summary/FriendsCard.vue';
import BModal from '../components/bootstrap/BModal.vue';

export default {
    components: {RewardCard, AchievementsCard, ReportUser, Loading, FriendsCard, SendMessage, BModal},
    beforeRouteUpdate(to, from, next) {
        this.$store.dispatch('user/getUserProfile', to.params.id);
        next();
    },
    data() {
        return {
            loading: true,
            showSendMessageModal: false,
            showReportUserModal: false,
        }
    },
    mounted() {
        this.$store.dispatch('user/getUserProfile', this.$route.params.id).then(() => this.loading = false);
    },
    computed: {
        ...mapGetters({
            userProfile: 'user/getUserProfile',
            user: 'user/getUser',
            requests: 'friend/getRequests',
        }),
        isConnection() {
            const ids = [];
            ids.push(...this.requests.outgoing.map(request => request.id));
            ids.push(...this.requests.incoming.map(request => request.id));
            ids.push(...this.user.friends.map(friend => friend.id));
            return ids.includes(this.userProfile.id);
        },
        /** Checked if this user profile is not the user currently logged in, so you can't send a request to yourself */
        notLoggedUser() {
            return this.$route.params.id != this.user.id;
        },
    },
    methods: {
        sendFriendRequest() {
            this.$store.dispatch('friend/sendRequest', this.$route.params.id);
        },
        sendMessage() {
            this.showSendMessageModal = true;
        },
        closeSendMessageModal() {
            this.showSendMessageModal = false;
        },
        reportUser() {
            this.$showReportUserModal = true;
        },
        closeReportUserModal() {
            this.showReportUserModal = false;
        },
        blockUser() {
            if (confirm(this.$t('block-user-confirmation', {user: this.userProfile.username}))) {
                this.$store.dispatch('user/blockUser');
            }
        },
    },
}
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