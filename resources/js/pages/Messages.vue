<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <h3>{{ $t('messages') }}</h3>
            <b-container class="message-page">
                <b-row>
                    <b-col class="conversations">
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
                    </b-col>
                    <b-col v-if="activeConversation" cols="8" class="m-1">
                        <h5 class="d-flex">{{ $t('conversation-with') }}&nbsp;
                            <router-link :to="{ name: 'profile', params: { id: activeConversation.recipient.id}}">
                                {{activeConversation.recipient.username}}
                            </router-link>
                            <span class="ml-auto">
                                <b-dropdown no-caret right variant="link">
                                    <template #button-content>
                                        <b-icon-three-dots-vertical class="icon" />
                                    </template>
                                    <b-dropdown-item :to="{ name: 'profile', params: { id: activeConversation.recipient.id}}">
                                        {{ $t('go-to-profile') }}
                                    </b-dropdown-item>
                                    <b-dropdown-item @click="addFriend(activeConversation.recipient)">
                                        {{ $t('add-friend') }}
                                    </b-dropdown-item>
                                    <b-dropdown-item @click="blockUser(activeConversation.recipient)">
                                        {{ $t('block-user') }}
                                    </b-dropdown-item>
                                    <b-dropdown-item @click="reportUser(activeConversation)">
                                        {{ $t('report') }}
                                    </b-dropdown-item>
                                </b-dropdown>
                            </span>
                        </h5>
                        <div class="new-message mb-3">
                            <b-form @submit.prevent="sendMessage">
                                <b-form-group>
                                    <b-form-textarea 
                                        id="message" 
                                        v-model="message.message"
                                        name="message" 
                                        rows=3
                                        :placeholder="$t('type-your-reply')" />
                                    <base-form-error name="message" /> 
                                </b-form-group>
                                <b-button type="submit" block>{{ $t('send-reply') }}</b-button>
                            </b-form>
                        </div>
                        <message v-for="message in activeConversation.messages" :key="message.id"
                                 :message="message" @deleteMessage="deleteMessage"
                        />
                        
                    </b-col>
                </b-row>
            </b-container>
            
            <b-modal id="report-user" :footer="false" :header="false">
                <ReportUser :user="userToReport" :conversation_id="conversationToReport" @close="closeReportUserModal" />
            </b-modal>
        </div>
    </div>
</template>

<script>
import BaseFormError from '../components/BaseFormError.vue';
import {mapGetters} from 'vuex';
import Message from '../components/small/Message.vue';
import ReportUser from '../components/modals/ReportUser.vue';
import Loading from '../components/Loading.vue';

export default {
    components: {
        BaseFormError,
        Message,
        ReportUser,
        Loading,
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
            this.$bvModal.show('report-user');
        },
        closeReportUserModal() {
            this.$bvModal.hide('report-user');
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