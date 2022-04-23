<template>
    <div>
        <Summary :title="$t('friends')">
            <ul v-if="user.friends.length > 0" class="mb-1 no-list-style clear-a primary-text">
                <li v-for="(friend, index) in user.friends" :key="index">
                    <span v-if="manage">
                        <Tooltip :text="$t('remove-friend')">
                            <FaIcon 
                                icon="user-plus"
                                class="icon small"
                                @click="removeFriend(friend)" />
                        </Tooltip>
                    </span>
                    <span v-if="message">
                        <Tooltip :text="$t('send-message')">
                            <FaIcon 
                                icon="envelope"
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
        <Modal :show="showSendMessageModal" :footer="false" :header="false" @close="closeSendMessageModal">
            <SendMessage :user="friendToMessage" @close="closeSendMessageModal" />
        </Modal>
    </div>
</template>


<script setup>
import Tooltip from '../bootstrap/Tooltip.vue';
import SendMessage from '../modals/SendMessage.vue';
import Summary from './Summary.vue';
import {computed, ref} from 'vue';
import {useUserStore} from '@/store/userStore';

const userStore = useUserStore();

const user = computed(() => userStore.user);

defineProps({
    manage: {
        type: Boolean,
        required: true,
    },
    message: {
        type: Boolean,
        required: true,
    },
});

const friendToMessage = ref(false);
const showSendMessageModal = ref(false);
function sendMessage(friend) {
    friendToMessage.value = friend;
    showSendMessageModal.value = true;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}

function removeFriend(friend) {
    if (confirm('Are you sure you wish to remove ' + friend.username + ' as friend?')) {
        this.$store.dispatch('friend/removeFriend', friend.friendship_id);
    }
}
</script>