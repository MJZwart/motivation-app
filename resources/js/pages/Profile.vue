<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="profile-grid">
            <div class="right-column">
                <h2>{{userProfile.username}}</h2>
                <div v-if="notLoggedUser" class="d-flex">
                    <b-icon-envelope id="message-user" class="icon small" @click="sendMessage" />
                    <b-tooltip target="message-user">{{ $t('message-user') }}</b-tooltip>
                    <b-icon-person-plus-fill id="send-friend-request" class="icon small" @click="sendFriendRequest" />
                    <b-tooltip target="send-friend-request">{{ $t('send-friend-request') }}</b-tooltip>
                    <b-icon-dash-circle id="block-user" class="icon small red" @click="blockUser" />
                    <b-tooltip target="block-user">{{ $t('block-user') }}</b-tooltip>
                    <b-icon-exclamation-circle id="report-user" class="icon small red" @click="reportUser" />
                    <b-tooltip target="report-user">{{ $t('report-user') }}</b-tooltip>
                </div>
                <p class="silent">{{ $t('member-since') }}: {{userProfile.created_at}}</p>
                <Achievements v-if="userProfile.achievements" :achievements="userProfile.achievements" />
            </div>
            <div class="left-column">
                <Reward v-if="userProfile.rewardObj" class="summary-tab" 
                        :reward="userProfile.rewardObj" :userReward="false" 
                        :rewardType="userProfile.rewardObj.rewardType" />
                <Friends :manage="false" :message="false" />
            </div>

            <b-modal id="send-message" hide-footer hide-header>
                <SendMessage :user="userProfile" @close="closeSendMessageModal" />
            </b-modal>
            <b-modal id="report-user" hide-footer hide-header>
                <ReportUser :user="userProfile" @close="closeReportUserModal" />
            </b-modal>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import Achievements from '../components/summary/AchievementsCard.vue';
import Reward from '../components/summary/RewardCard.vue';
import SendMessage from '../components/modals/SendMessage.vue';
import ReportUser from '../components/modals/ReportUser.vue';
import Loading from '../components/Loading.vue';
import Friends from '../components/summary/FriendsCard.vue';

export default {
    components: {Reward, Achievements, ReportUser, Loading, Friends, SendMessage},
    beforeRouteUpdate(to, from, next) {
        this.$store.dispatch('user/getUserProfile', to.params.id);
        next();
    },
    data() {
        return {
            loading: true,
        }
    },
    mounted() {
        this.$store.dispatch('user/getUserProfile', this.$route.params.id).then(() => this.loading = false);
    },
    computed: {
        ...mapGetters({
            userProfile: 'user/getUserProfile',
            user: 'user/getUser',
        }),
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
            this.$bvModal.show('send-message');
        },
        closeSendMessageModal() {
            this.$bvModal.hide('send-message');
        },
        reportUser() {
            this.$bvModal.show('report-user');
        },
        closeReportUserModal() {
            this.$bvModal.hide('report-user');
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