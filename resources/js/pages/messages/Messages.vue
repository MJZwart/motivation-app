<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <h3>{{ $t('messages') }}</h3>
            <div class="container message-page">
                <div v-if="!hasConversations">
                    {{ $t('no-messages') }}
                </div>
                <div v-else class="row">
                    <div class="conversations col">
                        <h5>{{ $t('conversations') }}</h5>
                        <div v-for="(conversation, index) in conversations" :key="conversation.id" 
                             class="conversation clickable content-block"
                             :class="activeConversation?.id == conversation.id ? 'active': ''"
                             @click="switchActiveConversation(index)">
                            <span class="d-flex">
                                <h6 class="mt-1 ml-2">
                                    {{conversation.recipient.username}}
                                    <Icon v-if="conversation.is_blocked" :icon="LOCK" class="block-icon red" />
                                </h6>
                                <span v-if="hasUnreadMessages(conversation)" class="ml-auto">
                                    <strong>{{ $t('unread') }}</strong>
                                </span>
                            </span>
                            <p><strong>{{getSender(conversation.last_message)}}</strong>
                                {{limitMessage(conversation.last_message.message)}}
                            </p>
                            <p class="silent mb-0">{{ $t('last-message') }}: {{parseDateTime(conversation.updated_at)}}</p>
                        </div>
                    </div>
                    <div v-if="activeConversation" class="col m-1 min-col-8">
                        <h5 class="d-flex">{{ $t('conversation-with') }}&nbsp;
                            <router-link :to="{ name: 'profile', params: { id: activeConversation.recipient.id}}">
                                {{activeConversation.recipient.username}}
                            </router-link>
                            <Icon v-if="activeConversation.is_blocked" :icon="LOCK" class="block-icon red non-clickable" />
                            <span class="ml-auto">
                                <Dropdown color="white">
                                    <section class="option">
                                        <router-link :to="{ name: 'profile', params: { id: activeConversation.recipient.id}}">
                                            {{ $t('go-to-profile') }}
                                        </router-link>
                                    </section>
                                    <section class="option">
                                        <button @click="addFriend(activeConversation!.recipient.id.toString())">
                                            {{ $t('add-friend') }}
                                        </button>
                                    </section>
                                    <section class="option">
                                        <button @click="blockUser(activeConversation!.recipient)">
                                            {{ $t('block-user') }}
                                        </button>
                                    </section>
                                    <section class="option">
                                        <button @click="reportUser(activeConversation!)">
                                            {{ $t('report') }}
                                        </button>
                                    </section>
                                </Dropdown>
                            </span>
                        </h5>
                        <div class="new-message mb-3">
                            <form @submit.prevent="prepareAndSendMessage">
                                <SimpleTextarea 
                                    id="message" 
                                    v-model="message.message"
                                    name="message" 
                                    :rows=3
                                    :placeholder="activeConversation.is_blocked ? $t('user-is-blocked') : $t('type-your-reply')"
                                    :disabled="activeConversation.is_blocked" />
                                <SubmitButton class="block" :disabled="activeConversation.is_blocked">
                                    {{ $t('send-reply') }}
                                </SubmitButton>
                            </form>
                        </div>
                        <div class="messages">
                            <Pagination :items="activeConversation.messages">
                                <template #items="items">
                                    <MessageComponent v-for="(message, index) in items" 
                                                      :key="index"
                                                      :message="message" 
                                                      class="message"
                                                      @deleteMessage="deleteMessage"
                                    />
                                </template>
                            </Pagination>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type {Conversation, Message, NewMessage} from 'resources/types/message';
import type {StrippedUser} from 'resources/types/user';
import {computed, ref, onMounted, watchEffect, onBeforeUnmount} from 'vue';
import {parseDateTime} from '/js/services/dateService';
import {useFriendStore} from '/js/store/friendStore';
import {useI18n} from 'vue-i18n';
import {LOCK} from '/js/constants/iconConstants';
import {formModal, showModal} from '/js/components/modal/modalService';
import {useUserStore} from '/js/store/userStore';
import {socketConnected} from '/js/services/websocketService';
import MessageComponent from './components/Message.vue';
import ReportUser from './components/ReportUser.vue';
import Dropdown from '/js/components/global/Dropdown.vue';
import Pagination from '/js/components/global/Pagination.vue';
import ConfirmBlockModal from './components/ConfirmBlockModal.vue';
import axios from 'axios';
import { getUnread, sendMessage } from '/js/services/messageService';

const {t} = useI18n();

const userStore = useUserStore();
const friendStore = useFriendStore();

const user = computed(() => userStore.user);

const active = ref(false);

onMounted(() => {
    load();
    active.value = true;
});

const activeConversationIndex = ref<number | null>(null);
const activeConversation = computed(() => {
    if (!conversations.value) return null;
    if (!activeConversationIndex.value) return conversations.value[0];
    return conversations.value[activeConversationIndex.value];
});

const message = ref<NewMessage>({
    message: '',
});
const loading = ref(true);

const conversations = ref<Conversation[]>([]);

const hasConversations = computed(() => !!conversations.value && !!conversations.value[0]);

function markAsRead(conversation: Conversation) {
    if (hasUnreadMessages(conversation)) {
        axios.put(`/message/conversation/${conversation.id}/read`);
        getUnread();
        setTimeout(() => {
            conversation.messages.forEach(message => {
                message.read = true;
            })}, 1000);
    }
}

async function getConversations(): Promise<Conversation[]> {
    const {data} = await axios.get('/message/conversations');
    return data.data;
}

async function load() {
    conversations.value = await getConversations();
    resetConversation();
    if (conversations.value && conversations.value[0])
        markAsRead(conversations.value[0]);
    loading.value = false;
}
function resetConversation() {
    if (conversations.value && conversations.value[0])
        activeConversationIndex.value = 0;
}
function switchActiveConversation(key: number) {
    if (!conversations.value) return;
    activeConversationIndex.value = key;
    markAsRead(conversations.value[key]);
}
async function prepareAndSendMessage() {
    if (activeConversation.value == null) return;
    if (activeConversation.value.is_blocked) return;
    message.value.conversation_id = activeConversation.value.conversation_id;
    message.value.recipient_id = activeConversation.value.recipient.id;
    await sendMessage(message.value)
    conversations.value = await getConversations();
    message.value.message = '';
    resetConversation();
}
function getSender(message: Message) {
    return message.sent_by_user ? t('you')+': ' : message.sender?.username + ': ';
}
function hasUnreadMessages(conversation: Conversation) {
    if (conversations.value && conversations.value[0])
        return conversation.messages.some((message: Message) => message.read == false);
    return false;
}
function limitMessage(messageString: string) {
    if (messageString.length > 100) {
        return messageString.slice(0, 100) + '...';
    } else {
        return messageString;
    }
    
}
async function deleteMessage(message: Message) {
    if (confirm(t('confirmation-delete-message'))) {
        await axios.delete(`/message/${message.id}`);
        conversations.value = await getConversations();
    }
}
function addFriend(userId: string) {
    if (userId == null) return;
    friendStore.sendRequest(userId);
}
function blockUser(user: StrippedUser) {
    // @ts-ignore Modal shenanigans
    formModal(user, ConfirmBlockModal, submitBlockUser, 'confirm-block');
}
async function submitBlockUser({user, hideMessages}: {user: StrippedUser, hideMessages: boolean}) {
    await userStore.blockUser(user.id, {'hideMessages': hideMessages});
    load();
}
function reportUser(conversation: Conversation) {
    showModal({user: conversation.recipient, conversation_id: conversation.conversation_id}, 
        ReportUser, 
        'report-user');
}

/*
Websockets
*/
function listenToNewMessages() {
    if (!user.value) return;
    window.Echo.private(`unread.${user.value.id}`)
        .listen('NewMessage', async () => {
            if (active.value) conversations.value = await getConversations();
        });
}

watchEffect(() => {
    if (socketConnected.value) listenToNewMessages()
});

onBeforeUnmount(() => {
    active.value = false;
});
</script>

<style lang="scss">
.conversations {
    overflow-wrap: break-word;
    hyphens: auto;
}
.conversation {
    background-color: var(--background-darker) !important;
    transition: all 0.15s ease-in-out;
}
.conversation:hover {
    margin-left: 0.25rem;
    transition: all 0.15s ease-in-out;
}
.conversation.active {
    margin-left: 0.5rem;
    background-color: var(--background-2) !important;
    transition: all 0.15s ease-in-out;
}
.messages {
    background-color: var(--background-darker);
    border-radius: 0.25rem;
    .message {
        padding: 0.05rem 0.5rem 0.05rem 0.5rem;
        border-radius: 0.25rem;
        p {
            margin: 0.8rem 0.5rem 0.8rem 0.5rem;
        }
    }
}
</style>