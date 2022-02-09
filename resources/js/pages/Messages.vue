<template>
    <div v-if="this.conversations.length != 0">
        <h3>Messages</h3>
        <div class="conversations">
            <h5>Conversations</h5>
            <div v-for="(conversation, index) in conversations" :key="conversation.id">
                <p>Conversation with: {{conversation.recipient.username}}</p>
                <p><strong>
                       {{conversation.last_message.sent_by_user ? 'You' : conversation.last_message.sender.username}}: </strong>
                    {{conversation.last_message.message}}
                </p>
                <button @click="switchActiveConversation(index)">Switch</button>
                <hr />
            </div>
        </div>
        <div v-if="activeConversation" class="messages">
            <h5>Messages with {{activeConversation.recipient.username}}</h5>
            <div v-for="message in activeConversation.messages" :key="message.id">
                <p>{{message.message}}</p>
            </div>
            <div class="new-message">
                <b-form @submit.prevent="sendMessage">
                    <b-form-group
                        label="Message" 
                        label-for="message">
                        <b-form-textarea 
                            id="message" 
                            v-model="message.message"
                            name="message" 
                            rows=3
                            placeholder="Type your message"  />
                        <base-form-error name="message" /> 
                    </b-form-group>
                    <b-button type="submit" block>Send message</b-button>
                </b-form>
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
    },
}
</script>