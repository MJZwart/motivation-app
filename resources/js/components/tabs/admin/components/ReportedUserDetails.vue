<template>
    <div>
        <BTable
            :items="user.reports"
            :fields="reportedUserDetailsFields"
            class="reported-user-details-table"
            :options="['striped']"
        >
            <template #actions>
                <b-button @click="sendMessageToReportedUser()">place holder message</b-button>
            </template>
            <template #conversation="row">
                <p>{{row.item.conversation}}</p>
                <template v-if="row.item.conversation">
                    <b-button @click="showConversation(row.item.conversation)"> placeholder show conversation</b-button>
                </template>
            </template>
        </BTable>

        <b-modal :id="`send-message-to-reported-user-${index}`" hide-footer :title="'placeholer title'">
            <SendMessage :user="user" @close="closeSendMessageToReportedUser()"/>
        </b-modal>
        <b-modal :id="`show-conversation-${index}`"
                 hide-footer :title="`placeholder title conversation ${conversationToShow}:`">
            <ShowConversationModal :conversationId="conversationToShow" @close="closeShowConversation()"/>
        </b-modal>
    </div>
</template>


<script>
import {REPORTED_USER_DETAILS_FIELDS} from '../../../../constants/reportedUserConstants.js';
import SendMessage from '../../../modals/SendMessage.vue';
import ShowConversationModal from './ShowConversationModal.vue';
import BTable from '../../../bootstrap/BTable.vue';
export default {
    components: {
        SendMessage,
        ShowConversationModal,
        BTable,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
        index: {
            type: Number,
            reqired: true,
        },
    },
    data() {
        return {
            reportedUserDetailsFields: REPORTED_USER_DETAILS_FIELDS,
            conversationToShow: null,
        }
    },
    methods: {
        sendMessageToReportedUser() {
            this.$store.dispatch('clearErrors');
            this.$bvModal.show(`send-message-to-reported-user-${this.index}`);
        },
        closeSendMessageToReportedUser() {
            this.$bvModal.hide(`send-message-to-reported-user-${this.index}`);
        },
        showConversation(conversationId) {
            this.$store.dispatch('clearErrors');
            this.conversationToShow = conversationId;
            this.$bvModal.show(`show-conversation-${this.index}`);
        },
        closeShowConversation() {
            this.conversationToShow = null;
            this.$bvModal.hide(`show-conversation-${this.index}`);
        },
    },
}
</script>