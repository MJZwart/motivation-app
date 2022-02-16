<template>
    <div v-if="activeConversation">
        <h3>{{ $t('messages') }}</h3>
        <div class="message-page">
            <div class="conversations">
                <h5>{{ $t('conversations') }}</h5>
                <div v-for="(conversation, index) in conversations" :key="conversation.id" 
                     :class="['conversation', 'clickable', activeConversation.id == conversation.id ? 'active': '']"
                     @click="switchActiveConversation(index)">
                    <span class="d-flex">
                        <h6 class="mt-1 ml-2">{{conversation.recipient.username}}</h6>
                        <span v-if="hasUnreadMessages(conversation)" class="ml-auto"><strong>{{ $t('unread') }}</strong></span>
                    </span>
                    <p><strong>{{getSender(conversation.last_message)}}</strong>
                        {{limitMessage(conversation.last_message.message)}}
                    </p>
                    <p class="silent mb-0">{{ $t('last-message') }}: {{conversation.updated_at}}</p>
                </div>
            </div>
            <div v-if="activeConversation" class="m-1 w-65">
                <h5>{{ $t('conversation-with') }} {{activeConversation.recipient.username}}</h5>
                <div class="new-message mb-3">
                    <b-form @submit.prevent="sendMessage">
                        <b-form-group>
                            <b-form-textarea 
                                id="message" 
                                v-model="message.message"
                                name="message" 
                                rows=3
                                placeholder="Type your reply"  />
                            <base-form-error name="message" /> 
                        </b-form-group>
                        <b-button type="submit" block>{{ $t('send-reply') }}</b-button>
                    </b-form>
                </div>
                <div class="h-75 scroll-y">
                    <div v-for="message in activeConversation.messages" :key="message.id" class="break-word">
                        <p class="mb-0">{{getSender(message)}} {{message.message}}</p>
                        <p class="silent">{{message.created_at}}</p>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</template>

<script>
import BaseFormError from '../components/BaseFormError.vue';
import {mapGetters} from 'vuex';

export default {
    components: {
        BaseFormError,
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
                this.activeConversation = this.conversations[0];
                this.markAsRead(this.conversations[0]);
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
    },
}
</script>

<style lang="scss">
@import '../../assets/scss/variables';
.message-page {
    display:flex;
}
.conversations {
    width:33%;
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