<template>
    <div>
        <div v-if="userProfile" class="profile-grid">
            <div class="right-column">
                <h2>{{userProfile.username}}</h2>
                <b-icon-envelope v-if="notLoggedUser" class="icon-small" @click="sendMessage" />
                <p class="silent">{{ $t('member-since') }}: {{userProfile.created_at}}</p>
                <achievements-summary v-if="userProfile.achievements" :achievements="userProfile.achievements" />
            </div>
            <div class="left-column">
                <reward-summary v-if="userProfile.rewardObj" class="summary-tab" 
                                :reward="userProfile.rewardObj" :userReward="false" 
                                :rewardType="userProfile.rewardObj.rewardType" />
                <div v-if="userProfile.friends" class="summary-tab">
                    <span class="card-title">Friends 
                        <b-icon-person-plus-fill 
                            v-if="notLoggedUser" 
                            class="icon-small" 
                            @click="sendFriendRequest" />
                    </span>
                    <div class="side-border bottom-border">
                        <ul class="summary-list">
                            <li v-for="(friend, index) in userProfile.friends" :key="index">
                                <router-link :to="{ name: 'profile', params: { id: friend.friend_id}}">
                                    {{friend.friend}}
                                </router-link>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <b-modal id="send-message" hide-footer hide-header>
            <send-message :user="userProfile" @close="closeSendMessageModal" />
        </b-modal>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import AchievementsSummary from '../components/summary/AchievementsSummary.vue';
import RewardSummary from '../components/summary/RewardSummary.vue';
import SendMessage from '../components/modals/SendMessage.vue';

export default {
    components: {RewardSummary, AchievementsSummary, SendMessage},
    beforeRouteUpdate(to, from, next) {
        this.$store.dispatch('user/getUserProfile', to.params.id);
        next();
    },
    mounted() {
        this.$store.dispatch('user/getUserProfile', this.$route.params.id);
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