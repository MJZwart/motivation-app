<template>
    <div>
        <ContentBlock title="friends" :tutorial="manage">
            <ul v-if="activeFriends.length > 0" class="mb-1 no-list-style text-decoration-none primary-text">
                <li v-for="(friend, index) in activeFriends" :key="index">
                    <span v-if="manage">
                        <Tooltip :text="$t('remove-friend')">
                            <Icon
                                :icon="CROSS_SQUARE"
                                class="cross-square-icon small red"
                                @click="promptRemoveFriend(friend)"
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
</template>

<script setup lang="ts">
import type {Friend} from 'resources/types/friend';
import {computed, PropType} from 'vue';
import {CROSS_SQUARE, MAIL} from '/js/constants/iconConstants';
import {sendMessageModal} from '/js/components/modal/modalService';
import { friends, removeFriend } from '/js/services/friendService';

const activeFriends = computed(() => (props.friends ? props.friends : friends.value));

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
    sendMessageModal(friend.username, friend.id);
}

function promptRemoveFriend(friend: Friend) {
    if (props.manage && confirm('Are you sure you wish to remove ' + friend.username + ' as friend?')) {
        removeFriend(friend.friendship_id);
    }
}
</script>
