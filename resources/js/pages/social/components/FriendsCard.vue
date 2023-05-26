<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <ContentBlock title="friends" :tutorial="manage">
                <ul v-if="friends.length > 0" class="mb-1 no-list-style clear-a primary-text">
                    <li v-for="(friend, index) in friends" :key="index">
                        <span v-if="manage">
                            <Tooltip :text="$t('remove-friend')">
                                <Icon
                                    :icon="CROSS_SQUARE"
                                    class="cross-square-icon small red"
                                    @click="removeFriend(friend)"
                                />
                            </Tooltip>
                        </span>
                        <span v-if="message">
                            <Tooltip :text="$t('send-message')">
                                <Icon :icon="MAIL" class="mail-icon small" @click="sendMessage(friend)" />
                            </Tooltip>
                        </span>
                        <router-link :to="{name: 'profile', params: {id: friend.id}}">
                            {{ friend.username }}
                        </router-link>
                    </li>
                </ul>
                <p v-else class="mb-1">{{ $t('no-friends') }}</p>
            </ContentBlock>
        </div>
    </div>
</template>

<script setup lang="ts">
import type {Friend} from 'resources/types/friend';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {ref, computed, onMounted, PropType} from 'vue';
import {useFriendStore} from '/js/store/friendStore';
import {CROSS_SQUARE, MAIL} from '/js/constants/iconConstants';
import {showModal} from '/js/components/modal/modalService';

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

function sendMessage(friend: Friend) {
    showModal({user: friend}, SendMessage, 'send-message');
}

function removeFriend(friend: Friend) {
    if (props.manage && confirm('Are you sure you wish to remove ' + friend.username + ' as friend?')) {
        friendStore.removeFriend(friend.friendship_id);
    }
}
</script>
