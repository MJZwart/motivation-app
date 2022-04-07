<template>
    <div>
        <Summary :title="$t('friends')">
            <ul v-if="user.friends.length > 0" class="mb-1 no-list-style">
                <li v-for="(friend, index) in user.friends" :key="index">
                    <span v-if="manage">
                        <Tooltip :text="$t('remove-friend')">
                            <FaIcon 
                                icon="fa-solid fa-user-plus"
                                class="icon small"
                                @click="removeFriend(friend)" />
                        </Tooltip>
                    </span>
                    <span v-if="message">
                        <Tooltip :text="$t('send-message')">
                            <FaIcon 
                                icon="fa-solid fa-envelope"
                                class="icon small"
                                @click="sendMessage(friend)" />
                        </Tooltip>
                    </span>
                    <router-link :to="{ name: 'profile', params: { id: friend.id}}">
                        {{friend.username}}
                    </router-link>
                </li>
            </ul>
            <p v-else class="mb-1">{{ $t('no-friends') }}</p>
        </Summary>
        <BModal :show="showSendMessageModal" :footer="false" :header="false" @close="closeSendMessageModal">
            <SendMessage :user="friendToMessage" @close="closeSendMessageModal" />
        </BModal>
    </div>
</template>


<script>
import Tooltip from '../bootstrap/Tooltip.vue';
import {mapGetters} from 'vuex';
import SendMessage from '../modals/SendMessage.vue';
import Summary from './Summary.vue';
import BModal from '../bootstrap/BModal.vue';

export default {
    components: {
        SendMessage, Summary, BModal, Tooltip,
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
            showSendMessageModal: false,
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
            this.showSendMessageModal = true;
        },
        closeSendMessageModal() {
            this.showSendMessageModal = false;
        },
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
        }),
    },
}
</script>