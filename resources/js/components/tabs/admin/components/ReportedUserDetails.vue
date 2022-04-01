<template>
    <div>
        <BTable
            :items="user.reports"
            :fields="reportedUserDetailsFields"
            class="reported-user-details-table"
            :options="['table-striped']"
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

        <BModal :show="showSendMessageModal" :footer="false" :title="'placeholer title'" @close="closeSendMessageToReportedUser">
            <SendMessage :user="user" @close="closeSendMessageToReportedUser"/>
        </BModal>
        <BModal :show="showConversationModal"
                :footer="false" 
                :title="`placeholder title conversation ${conversationToShow}:`" 
                @close="closeShowConversation">
            <ShowConversationModal :conversationId="conversationToShow" @close="closeShowConversation"/>
        </BModal>
    </div>
</template>


<script>
import {REPORTED_USER_DETAILS_FIELDS} from '../../../../constants/reportedUserConstants.js';
import SendMessage from '../../../modals/SendMessage.vue';
import ShowConversationModal from './ShowConversationModal.vue';
import BModal from '../../../bootstrap/BModal.vue';
import BTable from '../../../bootstrap/BTable.vue';
export default {
    components: {
        SendMessage,
        ShowConversationModal,
        BModal,
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
            showSendMessageModal: false,
            showConversationModal: false,
        }
    },
    methods: {
        sendMessageToReportedUser() {
            this.$store.dispatch('clearErrors');
            this.showSendMessageModal = true;
        },
        closeSendMessageToReportedUser() {
            this.showSendMessageModal = false;
        },
        showConversation(conversationId) {
            this.$store.dispatch('clearErrors');
            this.conversationToShow = conversationId;
            this.showConversationModal = true;
        },
        closeShowConversation() {
            this.conversationToShow = null;
            this.showConversationModal = false;
        },
    },
}
</script>