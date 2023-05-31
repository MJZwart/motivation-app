<template>
    <div>
        <h3>{{ $t('feedback') }}</h3>
        <button @click="archived = !archived">{{ archived ? 'Hide archived' : 'Show archived' }}</button>
        <Table 
            :items="filteredFeedback" 
            :fields="FEEDBACK_FIELDS" 
            :options="['table-striped', 'page-wide']" 
            class="font-sm" 
            sort="created_at"
            :sortAsc="false"
        >
            <template #username="row">
                <router-link v-if="row.item.user_id" :to="{name: 'profile', params: {id: row.item.user_id}}">
                    {{ row.item.username }}
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
                        v-if="row.item.user_id"
                        :icon="MAIL"
                        class="mail-icon"
                        @click="sendMessageToUser(row.item.user_id, row.item.username)"
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
    </div>
</template>

<script setup lang="ts">
import type {Feedback} from 'resources/types/feedback';
import {ref, onMounted, computed} from 'vue';
import Table from '/js/components/global/Table.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import Diagnostics from '/js/components/global/small/Diagnostics.vue';
import {FEEDBACK_FIELDS} from '/js/constants/feedbackConstants.js';
import {parseDateTime} from '/js/services/dateService';
import {useAdminStore} from '/js/store/adminStore';
import {LOCK, MAIL, UNLOCK} from '/js/constants/iconConstants';
import {showModal} from '/js/components/modal/modalService';
const adminStore = useAdminStore();

onMounted(async () => {
    feedback.value = await adminStore.getFeedback();
});

const feedback = ref<Feedback[] | []>([]);
const filteredFeedback = computed(() => {
    return archived.value ? feedback.value : feedback.value.filter(item => !item.archived);
});

const archived = ref(false);

/**
 * Opens a modal to send a message to user
 */
function sendMessageToUser(user_id: number, username: string) {
    showModal({user: {username: username, id: user_id}}, SendMessage, 'send-message');
}

/**
 * Sends a request to toggle the archived status of a feedback item
 */
async function toggleArchiveFeedback(feedbackId: number) {
    feedback.value = await adminStore.toggleArchiveFeedback(feedbackId);
}
</script>
