<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <h3>{{ $t('messages') }}</h3>
            <div class="container message-page">
                <div v-if="emptyInbox">
                    {{ $t('no-messages') }}
                </div>
                <div v-else class="row">
                    <div class="conversations col">
                        <h5>{{ $t('conversations') }}</h5>
                        <div v-for="(conversation, index) in conversations" :key="conversation.id" 
                             :class="['conversation', 'clickable', activeConversation.id == conversation.id ? 'active': '']"
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
                                <Dropdown>
                                    <section class="option">
                                        <router-link :to="{ name: 'profile', params: { id: activeConversation.recipient.id}}">
                                            {{ $t('go-to-profile') }}
                                        </router-link>
                                    </section>
                                    <section class="option">
                                        <button @click="addFriend(activeConversation.recipient)">
                                            {{ $t('add-friend') }}
                                        </button>
                                    </section>
                                    <section class="option">
                                        <button @click="blockUser(activeConversation.recipient)">
                                            {{ $t('block-user') }}
                                        </button>
                                    </section>
                                    <section class="option">
                                        <button @click="reportUser(activeConversation)">
                                            {{ $t('report') }}
                                        </button>
                                    </section>
                                </Dropdown>
                            </span>
                        </h5>
                        <div class="new-message mb-3">
                            <form @submit.prevent="sendMessage">
                                <Textarea 
                                    id="message" 
                                    v-model="message.message"
                                    name="message" 
                                    :rows=3
                                    :placeholder="$t('type-your-reply')" />
                                <button type="submit" class="block">{{ $t('send-reply') }}</button>
                            </form>
                        </div>
                        <Message v-for="message in activeConversation.messages" :key="message.id"
                                 :message="message" @deleteMessage="deleteMessage"
                        />
                        
                    </div>
                </div>
            </div>
            
            <Modal :show="showReportUserModal" :footer="false" :header="false" @close="closeReportUserModal">
                <ReportUser 
                    :user="userToReport.value" 
                    :conversation_id="conversationToReport.value" 
                    @close="closeReportUserModal" />
            </Modal>
        </div>
    </div>
</template>

<script setup>
import {computed, ref, reactive, onMounted} from 'vue';
import Message from './components/Message.vue';
import ReportUser from './components/ReportUser.vue';
import Dropdown from '/js/components/global/Dropdown.vue';
import {parseDateTime} from '/js/services/dateService';

import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope

import {useMessageStore} from '/js/store/messageStore';
const messageStore = useMessageStore();
import {useUserStore} from '/js/store/userStore';
const userStore = useUserStore();
import {useFriendStore} from '/js/store/friendStore';
const friendStore = useFriendStore();

onMounted(() => {
    load();
});

const activeConversation = ref({});
const message = reactive({
    message: '',
});
const userToReport = reactive({});
const conversationToReport = reactive({});
const loading = ref(true);
const emptyInbox = ref(true);
const showReportUserModal = ref(false);

const conversations = computed(() => messageStore.conversations);

async function load() {
    await messageStore.getConversations();
    resetConversation();
    if (conversations.value && conversations.value[0])
        markAsRead(conversations.value[0]);
    loading.value = false;
}
function resetConversation() {
    if (conversations.value && conversations.value[0]) {
        activeConversation.value = conversations.value[0];
        emptyInbox.value = false;
    } else 
        emptyInbox.value = true;
}
function switchActiveConversation(key) {
    activeConversation.value = conversations.value[key];
    markAsRead(conversations.value[key]);
}
async function sendMessage() {
    message.conversation_id = activeConversation.value.conversation_id;
    message.recipient_id = activeConversation.value.recipient.id;
    await messageStore.sendMessage(message)
    await messageStore.getConversations();
    message.message = '';
    resetConversation();
}
function getSender(message) {
    return message.sent_by_user ? t('you')+': ' : message.sender.username + ': ';
}
// eslint-disable-next-line no-unused-vars
function getConversationClass(conversationId) {
    return ['conversation', 'clickable', activeConversation.value.id == conversationId ? 'active': '']
}
async function markAsRead(conversation) {
    if (hasUnreadMessages(conversation)) {
        messageStore.markConversationRead(conversation.id);
        setTimeout(() => {
            conversation.messages.forEach(message => {
                message.read = true;
            })}, 3000);
    }
}
function hasUnreadMessages(conversation) {
    if (conversations.value && conversations.value[0])
        return conversation.messages.some(message => message.read == false);
    return false;
}
function limitMessage(message) {
    if (message.length > 100) {
        return message.slice(0, 100) + '...';
    } else {
        return message;
    }
    
}
async function deleteMessage(message) {
    if (confirm(t('confirmation-delete-message'))) {
        await messageStore.deleteMessage(message.id)
        resetConversation();
        await messageStore.getConversations();
    }
}
function addFriend(user) {
    friendStore.sendRequest(user.id);
}
async function blockUser(user) {
    if (confirm(t('block-user-confirmation', {user: user.username}))) {
        await userStore.blockUser(user.id)
        await messageStore.getConversations();
        resetConversation();
    }
}
function reportUser(conversation) {
    userToReport.value = conversation.recipient;
    conversationToReport.value = conversation.conversation_id;
    showReportUserModal.value = true;
}
function closeReportUserModal() {
    showReportUserModal.value = false;
    userToReport.value = {};
    conversationToReport.value = '';
}
</script>

<style lang="scss">
@import '../../../assets/scss/variables';
.conversations {
    overflow-wrap: break-word;
    hyphens: auto;
}
.conversation {
    margin: 6px;
    box-shadow: $light-shadow;
    padding: 0.1rem;
}
.conversation:hover {
    box-shadow: $active-shadow;
}
.conversation.active {
    margin: 4px 6px 6px 4px;
    background-color: $white;
    box-shadow: $active-shadow;
}
</style>