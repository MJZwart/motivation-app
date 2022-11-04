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
                                <h6 class="mt-1 ml-2">{{conversation.recipient.username}}</h6>
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
                            <form @submit.prevent="sendMessage">
                                <SimpleTextarea 
                                    id="message" 
                                    v-model="message.message"
                                    name="message" 
                                    :rows=3
                                    :placeholder="$t('type-your-reply')" />
                                <button type="submit" class="block">{{ $t('send-reply') }}</button>
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
            
            <Modal :show="showReportUserModal" :footer="false" :header="false" @close="closeReportUserModal">
                <ReportUser 
                    v-if="conversationToReport && userToReport"
                    :user="userToReport" 
                    :conversation_id="conversationToReport" 
                    @close="closeReportUserModal" />
            </Modal>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, ref, onMounted} from 'vue';
import MessageComponent from './components/Message.vue';
import ReportUser from './components/ReportUser.vue';
import Dropdown from '/js/components/global/Dropdown.vue';
import Pagination from '/js/components/global/Pagination.vue';
import {parseDateTime} from '/js/services/dateService';
import {useMessageStore} from '/js/store/messageStore';
import {useUserStore} from '/js/store/userStore';
import {useFriendStore} from '/js/store/friendStore';
import {useI18n} from 'vue-i18n';
import type {Conversation, Message, NewMessage} from 'resources/types/message';
import type {StrippedUser} from 'resources/types/user';

const {t} = useI18n();

const messageStore = useMessageStore();
const userStore = useUserStore();
const friendStore = useFriendStore();

onMounted(() => {
    load();
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
const userToReport = ref<StrippedUser | null>(null)
const conversationToReport = ref<string | null>(null);
const loading = ref(true);
const showReportUserModal = ref(false);

const conversations = computed(() => messageStore.conversations);

const hasConversations = computed(() => !!conversations.value && !!conversations.value[0]);

function markAsRead(conversation: Conversation) {
    if (hasUnreadMessages(conversation)) {
        messageStore.markConversationRead(conversation.id);
        setTimeout(() => {
            conversation.messages.forEach(message => {
                message.read = true;
            })}, 3000);
    }
}

async function load() {
    await messageStore.getConversations();
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
async function sendMessage() {
    if (activeConversation.value == null) return;
    message.value.conversation_id = activeConversation.value.conversation_id;
    message.value.recipient_id = activeConversation.value.recipient.id;
    await messageStore.sendMessage(message.value)
    await messageStore.getConversations();
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
        await messageStore.deleteMessage(message.id);
        await messageStore.getConversations();
    }
}
function addFriend(userId: string) {
    if (userId == null) return;
    friendStore.sendRequest(userId);
}
async function blockUser(user: StrippedUser) {
    if (confirm(t('block-user-confirmation', {user: user.username}))) {
        await userStore.blockUser(user.id)
        await messageStore.getConversations();
        resetConversation();
    }
}
function reportUser(conversation: Conversation) {
    userToReport.value = conversation.recipient;
    conversationToReport.value = conversation.conversation_id;
    showReportUserModal.value = true;
}
function closeReportUserModal() {
    showReportUserModal.value = false;
    userToReport.value = null;
    conversationToReport.value = '';
}
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