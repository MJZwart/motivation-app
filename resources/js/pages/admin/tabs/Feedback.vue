<template>
    <div>
        <h3>{{ $t('feedback') }}</h3>
        <button @click="archived = !archived">{{archived ? 'Hide archived' : 'Show archived'}}</button>
        <Table
            :items="filteredFeedback"
            :fields="feedbackFields"
            :options="['table-striped', 'page-wide']"
        >
            <template #user="row">
                <router-link v-if="row.item.user" :to="{ name: 'profile', params: { id: row.item.user.id}}">
                    {{row.item.user.username}}
                </router-link>
            </template>
            <template #created_at="row">
                {{parseDateTime(row.item.created_at)}}
            </template>
            <template #actions="row">
                <Tooltip v-if="!row.item.archived" :text="$t('archive')">
                    <FaIcon 
                        icon="lock"
                        class="icon small red"
                        @click="toggleArchiveFeedback(row.item.id)" />
                </Tooltip>
                <Tooltip v-if="row.item.archived" :text="$t('unarchive')">
                    <FaIcon 
                        icon="lock-open"
                        class="icon small green"
                        @click="toggleArchiveFeedback(row.item.id)" />
                </Tooltip>
                <Tooltip :text="$t('message-user')">
                    <FaIcon 
                        v-if="row.item.user"
                        icon="envelope"
                        class="icon small"
                        @click="sendMessageToUser(row.item.user)" />
                </Tooltip>
            </template>
            <template #archived="row">
                {{row.item.archived ? parseDateTime(row.item.updated_at) : ''}}
            </template>
        </Table>
        <Modal 
            :show="showSendMessageModal" 
            :footer="false" 
            :header="false"
            @close="closeSendMessageModal">
            <SendMessage :user="userToMessage" @close="closeSendMessageModal"/>
        </Modal>
    </div>
</template>

<script setup>
import {ref, onMounted, computed} from 'vue';
import Table from '/js/components/global/Table.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {FEEDBACK_FIELDS} from '/js/constants/feedbackConstants.js';
import {parseDateTime} from '/js/services/timezoneService';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

onMounted(async() => {
    feedback.value = await adminStore.getFeedback();
})

const feedback = ref([]);
const filteredFeedback = computed(() => {
    return archived.value ? feedback.value : feedback.value.filter(item => !item.archived);
});
const feedbackFields = ref(FEEDBACK_FIELDS);

const showSendMessageModal = ref(false);
const userToMessage = ref('');

const archived = ref(false);

/**
 * Opens a modal to send a message to user, and sets the
 * userToMessage to feed this user into the modal component.
 * @param {import('resources/types/user').User} user
 */
function sendMessageToUser(user) {
    userToMessage.value = user;
    showSendMessageModal.value = true;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}

/**
 * Sends a request to toggle the archived status of a feedback item
 * @param {Number} feedbackId
 */
async function toggleArchiveFeedback(feedbackId) {
    feedback.value = await adminStore.toggleArchiveFeedback(feedbackId);
}
</script>