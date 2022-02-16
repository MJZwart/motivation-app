<template>
    <div v-if="activeConversation">
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
                    <h5>{{ $t('conversation-with') }} {{activeConversation.recipient.username}}</h5>
                    <div class="new-message mb-3">
                        <b-form @submit.prevent="sendMessage">
                            <b-form-group>
                                <b-form-textarea 
                                    id="message" 
                                    v-model="message.message"
                                    name="message" 
                                    rows=3
                                    placeholder="Type your reply" />
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
    </div>
</template>

<script>
import BaseFormError from '../components/BaseFormError.vue';
import {mapGetters} from 'vuex';
import Message from '../components/small/Message.vue';

export default {
    components: {
        BaseFormError,
        Message,
    },
    data() {
        return {
            activeConversation: null,
            message: {
                message: '',
            },
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
                this.activeConversation = this.conversations[0];
            });
            
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
                this.load();
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
                    this.load();
                });
            }
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