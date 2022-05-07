<template>
    <div>
        <Table
            :items="user.reports"
            :fields="reportedUserDetailsFields"
            class="reported-user-details-table"
            :options="['table-striped', 'page-wide']"
        >
            <template #actions>
                <Tooltip :text="$t('message-reported-user')">
                    <FaIcon 
                        icon="envelope"
                        class="icon"
                        @click="sendMessageToReportedUser()" />
                </Tooltip>
            </template>
            <template #conversation="row">
                <div v-if="row.item.conversation">
                    {{ $t('yes')}}
                    <Tooltip :text="$t('show-conversation')">
                        <FaIcon 
                            icon="magnifying-glass" 
                            class="icon"
                            @click="showConversation(row.item.conversation)" />
                    </Tooltip>
                </div>
                <div v-else>
                    {{ $t('no')}}
                </div>
            </template>
        </Table>

        <Modal :show="showSendMessageModal" :footer="false" :title="$t('send-message')" @close="closeSendMessageToReportedUser">
            <SendMessage :user="user" @close="closeSendMessageToReportedUser"/>
        </Modal>
        <Modal :show="showConversationModal"
               :footer="false" 
               :title="$t('see-conversation-reporter', {reportedUser: user.username})" 
               @close="closeShowConversation">
            <ShowConversationModal :conversationId="conversationToShow" @close="closeShowConversation"/>
        </Modal>
    </div>
</template>


<script setup>
import {ref} from 'vue';
import {REPORTED_USER_DETAILS_FIELDS} from '/js/constants/reportUserConstants.js';
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