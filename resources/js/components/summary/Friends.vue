<template>
    <div>
        <Summary :title="$t('friends')">
            <ul v-if="user.friends.length > 0" class="mb-1 no-list-style">
                <li v-for="(friend, index) in user.friends" :key="index">
                    <b-icon-envelope class="icon small" @click="sendMessage(friend)" /> 
                    <router-link :to="{ name: 'profile', params: { id: friend.id}}">
                        {{friend.username}}
                    </router-link>
                </li>
            </ul>
            <p v-else class="mb-1">{{ $t('no-friends') }}</p>
        </Summary>
        <b-modal id="send-message" hide-footer hide-header>
            <send-message :user="friendToMessage" @close="closeSendMessageModal" />
        </b-modal>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import SendMessage from '../modals/SendMessage.vue';
import Summary from './Summary.vue';

export default {
    components: {
        SendMessage, Summary,
    },
    data() {
        return {
            friendToMessage: null,
        }
    },
    methods: {
        sendMessage(friend) {
            this.friendToMessage = friend;
            this.$bvModal.show('send-message');
        },
        closeSendMessageModal() {
            this.$bvModal.hide('send-message');
        },
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
        }),
    },
}
</script>