<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <h3>{{ $t('messages') }}</h3>
            <div class="container message-page">
                <div class="row">
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
                            <p class="silent mb-0">{{ $t('last-message') }}: {{conversation.updated_at}}</p>
                        </div>
                    </div>
                    <div v-if="activeConversation" class="col-8 m-1">
                        <h5 class="d-flex">{{ $t('conversation-with') }}&nbsp;
                            <router-link :to="{ name: 'profile', params: { id: activeConversation.recipient.id}}">
                                {{activeConversation.recipient.username}}
                            </router-link>
                            <span class="ml-auto">
                                <Dropdown>
                                    <section class="option">
                                        <button>
                                            <router-link :to="{ name: 'profile', params: { id: activeConversation.recipient.id}}">
                                                {{ $t('go-to-profile') }}
                                            </router-link>
                                        </button>
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
                                <div class="form-group">
                                    <textarea 
                                        id="message" 
                                        v-model="message.message"
                                        name="message" 
                                        rows=3
                                        :placeholder="$t('type-your-reply')" />
                                    <base-form-error name="message" /> 
                                </div>
                                <b-button type="submit" block>{{ $t('send-reply') }}</b-button>
                            </form>
                        </div>
                        <message v-for="message in activeConversation.messages" :key="message.id"
                                 :message="message" @deleteMessage="deleteMessage"
                        />
                        
                    </div>
                </div>
            </div>
            
            <BModal :show="showReportUserModal" :footer="false" :header="false" @close="closeReportUserModal">
                <ReportUser :user="userToReport" :conversation_id="conversationToReport" @close="closeReportUserModal" />
            </BModal>
        </div>
    </div>
</template>

<script>
import BaseFormError from '../components/BaseFormError.vue';
import {mapGetters} from 'vuex';
import Message from '../components/small/Message.vue';
import ReportUser from '../components/modals/ReportUser.vue';
import Loading from '../components/Loading.vue';
import BModal from '../components/bootstrap/BModal.vue';
import Dropdown from '../components/bootstrap/Dropdown.vue';

export default {
    components: {
        BaseFormError,
        Message,
        ReportUser,
        Loading,
        BModal,
        Dropdown,
    },
    data() {
        return {
            activeConversation: null,
            message: {
                message: '',
            },
            userToReport: {},
            conversationToReport: '',
            loading: true,
            showReportUserModal: false,
        }
    },
    mounted() {
        this.load();
    },
    computed: {
        ...mapGetters({
            conversations: 'message/getConversations',
        }),
    },
    methods: {
        load() {
            this.$store.dispatch('message/getConversations').then(() => {
                this.markAsRead(this.conversations[0]);
                this.resetConversation();
                this.loading = false;
            });
        },
        resetConversation() {
            this.activeConversation = this.conversations[0];
        },
        switchActiveConversation(key) {
            this.activeConversation = this.conversations[key];
            this.markAsRead(this.conversations[key]);
        },
        sendMessage() {
            this.message.conversation_id = this.activeConversation.conversation_id;
            this.message.recipient_id = this.activeConversation.recipient.id;
            this.$store.dispatch('message/sendMessage', this.message).then(() => {
                this.message.message = '';
                this.resetConversation();
            });
        },
        getSender(message) {
            return message.sent_by_user ? this.$t('you')+': ' : message.sender.username + ': ';
        },
        async markAsRead(conversation) {
            if (this.hasUnreadMessages(conversation)) {
                this.$store.dispatch('message/markConversationRead', conversation.id);
                setTimeout(() => {
                    conversation.messages.forEach(message => {
                        message.read = true;
                    })}, 3000);
            }
        },
        hasUnreadMessages(conversation) {
            return conversation.messages.some(message => message.read == false);
        },
        limitMessage(message) {
            if (message.length > 100) {
                return message.slice(0, 100) + '...';
            } else {
                return message;
            }
            
        },
        deleteMessage(message) {
            if (confirm(this.$t('confirmation-delete-message'))) {
                this.$store.dispatch('message/deleteMessage', message.id).then(() => {
                    this.resetConversation();
                });
            }
        },
        addFriend(user) {
            this.$store.dispatch('friend/sendRequest', user.id);
        },
        blockUser(user) {
            if (confirm(this.$t('block-user-confirmation', {user: user.username}))) {
                this.$store.dispatch('user/blockUser', user.id).then(() => {
                    this.resetConversation();
                });
            }
        },
        reportUser(conversation) {
            this.userToReport = conversation.recipient;
            this.conversationToReport = conversation.conversation_id;
            this.showReportUserModal = true;
        },
        closeReportUserModal() {
            this.showReportUserModal = false;
            this.userToReport = {};
            this.conversationToReport = '';
        },
    },
}
</script>

<style lang="scss">
@import '../../assets/scss/variables';
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