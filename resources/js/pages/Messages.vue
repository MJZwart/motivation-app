<template>
    <div v-if="conversations">
        <h3>Messages</h3>
        <div v-for="conversation in conversations" :key="conversation.id">
            <p>Conversation with: {{conversation.recipient.username}}</p>
            <p><strong>{{conversation.last_message.sent_by_user ? 'You' : conversation.last_message.sender.username}}: </strong>
                {{conversation.last_message.message}}
            </p>
        </div>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';

export default {
    mounted() {
        this.$store.dispatch('message/getConversations');
    },
    computed: {
        ...mapGetters({
            conversations: 'message/getConversations',
        }),
    },
}
</script>