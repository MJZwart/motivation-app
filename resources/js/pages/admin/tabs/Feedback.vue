<template>
    <div>
        <h3>{{ $t('feedback') }}</h3>
        <button @click="archived = !archived">{{ archived ? 'Hide archived' : 'Show archived' }}</button>
        <Table 
            :items="filteredFeedback" 
            :fields="feedbackFields" 
            :options="['table-striped', 'page-wide']" 
            class="font-sm" 
            sort="created_at"
            :sortAsc="false"
        >
            <template #user="row">
                <router-link v-if="row.item.user" :to="{name: 'profile', params: {id: row.item.user.id}}">
                    {{ row.item.user.username }}
                </router-link>
            </template>
            <template #created_at="row">
                {{ parseDateTime(row.item.created_at) }}
            </template>
            <template #actions="row">
                <Tooltip v-if="!row.item.archived" :text="$t('archive')">
                    <Icon :icon="LOCK" class="lock-icon red" @click="toggleArchiveFeedback(row.item.id)" />
                </Tooltip>
                <Tooltip v-if="row.item.archived" :text="$t('unarchive')">
                    <Icon :icon="UNLOCK" class="unlock-icon green" @click="toggleArchiveFeedback(row.item.id)" />
                </Tooltip>
                <Tooltip :text="$t('message-user')">
                    <Icon
                        v-if="row.item.user"
                        :icon="MAIL"
                        class="mail-icon"
                        @click="sendMessageToUser(row.item.user)"
                    />
                </Tooltip>
            </template>
            <template #archived="row">
                {{ row.item.archived ? parseDateTime(row.item.updated_at) : '' }}
            </template>
            <template #diagnostics="row">
                <Diagnostics :diagnostics="row.item.diagnostics"/>
            </template>
        </Table>
        <Modal :show="showSendMessageModal" :header="false" @close="closeSendMessageModal">
            <SendMessage v-if="userToMessage" :user="userToMessage" @close="closeSendMessageModal" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {ref, onMounted, computed} from 'vue';
import Table from '/js/components/global/Table.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import Diagnostics from '/js/components/global/small/Diagnostics.vue';
import {FEEDBACK_FIELDS} from '/js/constants/feedbackConstants.js';
import {parseDateTime} from '/js/services/dateService';
import {useAdminStore} from '/js/store/adminStore';
import type {StrippedUser} from 'resources/types/user';
import type {Feedback} from 'resources/types/feedback';
import {LOCK, MAIL, UNLOCK} from '/js/constants/iconConstants';
const adminStore = useAdminStore();

onMounted(async () => {
    feedback.value = await adminStore.getFeedback();
});

const feedback = ref<Feedback[] | []>([]);
const filteredFeedback = computed(() => {
    return archived.value ? feedback.value : feedback.value.filter(item => !item.archived);
});
const feedbackFields = ref(FEEDBACK_FIELDS);

const showSendMessageModal = ref(false);
const userToMessage = ref<StrippedUser | null>(null);

const archived = ref(false);

/**
 * Opens a modal to send a message to user, and sets the
 * userToMessage to feed this user into the modal component.
 */
function sendMessageToUser(user: StrippedUser) {
    userToMessage.value = user;
    showSendMessageModal.value = true;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}

/**
 * Sends a request to toggle the archived status of a feedback item
 */
async function toggleArchiveFeedback(feedbackId: number) {
    feedback.value = await adminStore.toggleArchiveFeedback(feedbackId);
}
</script>
