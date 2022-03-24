<template>
    <div>
        <b-table
            :items="user.reports"
            :fields="reportedUserDetailsFields"
            class="reported-user-details-table"
            striped
        >
            <template #cell(actions)>
                <b-button @click="sendMessageToReportedUser(user.id)">place holder message</b-button>
            </template>
            <template #cell(conversation)="row">
                <p>{{row.item.conversation}}</p>
                <template v-if="row.item.conversation">
                    <b-button @click="showConversation(row.item.conversation, user.id)"> placeholder show conversation</b-button>
                </template>
            </template>
        </b-table>

        <b-modal :id="`send-message-to-reported-user-${user.id}`" hide-footer :title="'placeholer title'">
            <SendMessage :user="user" @close="closeSendMessageToReportedUser(user.id)"/>
        </b-modal>
        <b-modal :id="`show-conversation-${user.id}`"
                 hide-footer :title="`placeholder title conversation ${conversationToShow}:`">
            <AdminShowConversation :conversationId="conversationToShow" @close="closeShowConversation(user.id)"/>
        </b-modal>
    </div>
</template>


<script>
import {REPORTED_USER_DETAILS_FIELDS} from '../../constants/reportedUserConstants.js';
import SendMessage from '../modals/SendMessage.vue';
import AdminShowConversation from '../modals/AdminShowConversation.vue';
export default {
    components: {
        SendMessage,
        AdminShowConversation,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            reportedUserDetailsFields: REPORTED_USER_DETAILS_FIELDS,
            conversationToShow: null,
        }
    },
    methods: {
        sendMessageToReportedUser(id) {
            this.$store.dispatch('clearErrors');
            this.$bvModal.show(`send-message-to-reported-user-${id}`);
        },
        closeSendMessageToReportedUser(id) {
            this.$bvModal.hide(`send-message-to-reported-user-${id}`);
        },
        showConversation(conversationId, id) {
            this.$store.dispatch('clearErrors');
            this.conversationToShow = conversationId;
            this.$bvModal.show(`show-conversation-${id}`);
        },
        closeShowConversation(id) {
            this.conversationToShow = null;
            this.$bvModal.hide(`show-conversation-${id}`);
        },
    },
}
</script>
