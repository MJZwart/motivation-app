<template>
    <div>
        <Table
            :items="user.reports"
            :fields="reportedUserDetailsFields"
            class="reported-user-details-table"
            :options="['table-striped']"
        >
            <template #actions>
                <button @click="sendMessageToReportedUser()">place holder message</button>
            </template>
            <template #conversation="row">
                <p>{{row.item.conversation}}</p>
                <template v-if="row.item.conversation">
                    <button @click="showConversation(row.item.conversation)"> placeholder show conversation</button>
                </template>
            </template>
        </Table>

        <Modal :show="showSendMessageModal" :footer="false" :title="'placeholer title'" @close="closeSendMessageToReportedUser">
            <SendMessage :user="user" @close="closeSendMessageToReportedUser"/>
        </Modal>
        <Modal :show="showConversationModal"
               :footer="false" 
               :title="`placeholder title conversation ${conversationToShow}:`" 
               @close="closeShowConversation">
            <ShowConversationModal :conversationId="conversationToShow" @close="closeShowConversation"/>
        </Modal>
    </div>
</template>


<script setup>
import {ref} from 'vue';
import {REPORTED_USER_DETAILS_FIELDS} from '/js/constants/reportedUserConstants.js';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import ShowConversationModal from './ShowConversationModal.vue';
import Table from '/js/components/global/Table.vue';
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

defineProps({
    user: {
        type: Object,
        required: true,
    },
    index: {
        type: Number,
        reqired: true,
    },
});

const reportedUserDetailsFields = REPORTED_USER_DETAILS_FIELDS;
const conversationToShow = ref(null);
const showSendMessageModal = ref(false);
const showConversationModal = ref(false);

function sendMessageToReportedUser() {
    mainStore.clearErrors();
    showSendMessageModal.value = true;
}
function closeSendMessageToReportedUser() {
    showSendMessageModal.value = false;
}
function showConversation(conversationId) {
    mainStore.clearErrors();
    conversationToShow.value = conversationId;
    showConversationModal.value = true;
}
function closeShowConversation() {
    conversationToShow.value = null;
    showConversationModal.value = false;
}
</script>