<template>
    <div>
        <Summary :title="$t('friends')">
            <ul v-if="user.friends.length > 0" class="mb-1 no-list-style">
                <li v-for="(friend, index) in user.friends" :key="index">
                    <span v-if="manage">
                        <b-icon-person-x-fill :id="'remove-friend-' + index" class="icon small" @click="removeFriend(friend)" />
                        <b-tooltip :target="'remove-friend-' + index">{{ $t('remove-friend') }}</b-tooltip>
                    </span>
                    <span v-if="message">
                        <b-icon-envelope 
                            :id="'send-message-to-friend-' + index" 
                            class="icon small" 
                            @click="sendMessage(friend)" /> 
                        <b-tooltip :target="'send-message-to-friend-' + index">{{ $t('send-message') }}</b-tooltip>
                    </span>
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
    props: {
        manage: {
            type: Boolean,
            required: true,
        },
        message: {
            type: Boolean,
            required: true,
        },
    },
    data() {
        return {
            friendToMessage: null,
        }
    },
    methods: {
        removeFriend(friend) {
            if (confirm('Are you sure you wish to remove ' + friend.username + ' as friend?')) {
                this.$store.dispatch('friend/removeFriend', friend.friendship_id);
            }
        },
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