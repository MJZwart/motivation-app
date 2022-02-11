<template>
    <div v-if="this.conversations.length != 0">
        <h3>Messages</h3>
        <div class="message-page">
            <div class="conversations">
                <h5>Conversations</h5>
                <div v-for="(conversation, index) in conversations" :key="conversation.id" 
                     class="conversation clickable"
                     @click="switchActiveConversation(index)">
                    <p>Conversation with: {{conversation.recipient.username}}</p>
                    <p><strong>{{getSender(conversation.last_message)}}</strong>
                        {{conversation.last_message.message}}
                    </p>
                    <p class="silent">Last message on: {{conversation.updated_at}}</p>
                </div>
            </div>
            <div v-if="activeConversation" class="messages">
                <h5>Messages with {{activeConversation.recipient.username}}</h5>
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
                        <b-button type="submit" block>Send reply</b-button>
                    </b-form>
                </div>
                <div v-for="message in activeConversation.messages" :key="message.id">
                    <p class="mb-0">{{getSender(message)}} {{message.message}}</p>
                    <p class="silent">{{message.created_at}}</p>
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
            });
        },
        switchActiveConversation(key) {
            this.activeConversation = this.conversations[key];
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
            return message.sent_by_user ? 'You: ' : message.sender.username + ': ';
        },
    },
}
</script>

<style lang="scss">
@import '../../assets/scss/variables';
.message-page {
    display:grid;
    grid-template-columns: 1fr 2fr;
}
.conversation {
    border: 1px solid $grey;
    margin: 6px;
}
.messages {
    margin: 3px;
}
.message {

}
.new-message {

}
.clickable { 
    cursor: pointer;
}
</style>