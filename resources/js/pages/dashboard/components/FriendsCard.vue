<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <Summary :title="$t('friends')" :tutorialOff="!manage">
                <ul v-if="friends.length > 0" class="mb-1 no-list-style clear-a primary-text">
                    <li v-for="(friend, index) in friends" :key="index">
                        <span v-if="manage">
                            <Tooltip :text="$t('remove-friend')">
                                <FaIcon 
                                    :icon="['far', 'rectangle-xmark']"
                                    class="icon small red"
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
    </div>
</template>


<script setup>
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import Summary from '/js/components/global/Summary.vue';
import {ref, computed, onMounted} from 'vue';
import {useFriendStore} from '/js/store/friendStore';

const friendStore = useFriendStore();

onMounted(async() => {
    loading.value = true;
    if (!props.profile)
        await friendStore.getFriends();
    loading.value = false;
});

const loading = ref(true);


const friends = computed(() => props.profile ? props.friends : friendStore.friends);

const props = defineProps({
    manage: {
        type: Boolean,
        required: false,
        default: false,
    },
    message: {
        type: Boolean,
        required: true,
    },
    friends: {
        type: Array,
        required: false,
    },
    profile: {
        type: Boolean,
        required: false,
        default: false,
    }
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
    if (props.manage && confirm('Are you sure you wish to remove ' + friend.username + ' as friend?')) {
        friendStore.removeFriend(friend.friendship_id);
    }
}
</script>