<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <Summary :title="$t('friends')" :tutorialOff="!manage">
                <ul v-if="friends.length > 0" class="mb-1 no-list-style clear-a primary-text">
                    <li v-for="(friend, index) in friends" :key="index">
                        <span v-if="manage">
                            <Tooltip :text="$t('remove-friend')">
                                <Icon
                                    :icon="CROSS_SQUARE"
                                    class="icon small red"
                                    @click="removeFriend(friend)"
                                />
                            </Tooltip>
                        </span>
                        <span v-if="message">
                            <Tooltip :text="$t('send-message')">
                                <Icon :icon="MAIL" class="message-icon small" @click="sendMessage(friend)" />
                            </Tooltip>
                        </span>
                        <router-link :to="{name: 'profile', params: {id: friend.id}}">
                            {{ friend.username }}
                        </router-link>
                    </li>
                </ul>
                <p v-else class="mb-1">{{ $t('no-friends') }}</p>
            </Summary>
            <Modal :show="showSendMessageModal" :header="false" @close="closeSendMessageModal">
                <SendMessage v-if="friendToMessage" :user="friendToMessage" @close="closeSendMessageModal" />
            </Modal>
        </div>
    </div>
</template>

<script setup lang="ts">
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import Summary from '/js/components/global/Summary.vue';
import {ref, computed, onMounted, PropType} from 'vue';
import {useFriendStore} from '/js/store/friendStore';
import {Friend} from 'resources/types/friend';
import {CROSS_SQUARE, MAIL} from '/js/constants/iconConstants';

const friendStore = useFriendStore();

onMounted(async () => {
    loading.value = true;
    if (!props.friends) await friendStore.getFriends();
    loading.value = false;
});

const loading = ref(true);

const friends = computed(() => (props.friends ? props.friends : friendStore.friends));

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
        type: Array as PropType<Friend[]>,
        required: false,
    },
});

const friendToMessage = ref<Friend | null>(null);
const showSendMessageModal = ref(false);

function sendMessage(friend: Friend) {
    friendToMessage.value = friend;
    showSendMessageModal.value = true;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}

function removeFriend(friend: Friend) {
    if (props.manage && confirm('Are you sure you wish to remove ' + friend.username + ' as friend?')) {
        friendStore.removeFriend(friend.friendship_id);
    }
}
</script>
