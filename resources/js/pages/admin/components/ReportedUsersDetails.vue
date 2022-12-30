<template>
    <div v-if="user.suspended">
        <h5>{{ $t('suspensions') }}</h5>
        <div class="row">
            <span class="col header">{{ $t('start-suspension') }}</span>
            <span class="col header">{{ $t('end-suspension') }}</span>
            <span class="col header">{{ $t('reason') }}</span>
            <span class="col fg-1 header">{{ $t('days') }}</span>
            <span class="col fg-1 header">{{ $t('suspended-by') }}</span>
        </div>
        <template v-for="(suspended, index) in user.suspended" :key="index">
            <div class="row">
                <div class="col">
                    {{parseDateTime(suspended.created_at)}} ({{suspended.time_since}})
                </div>
                <div class="col">
                    {{suspended.early_release ? 
                        parseDateTime(suspended.early_release) : parseDateTime(suspended.suspended_until)}} 
                    ({{suspended.suspended_until_time}})
                </div>
                <div class="col">
                    {{suspended.reason}}
                </div>
                <div class="col fg-1">
                    {{suspended.days}}
                </div>
                <div class="col fg-1">
                    {{suspended.admin.username}}
                </div>
            </div>
        </template>
    </div>
    <!-- Reports overview -->
    <div>
        <h5>{{ $t('reports') }}</h5>
        <div class="row">
            <span class="col fg-1 header">{{ $t('reason') }}</span>
            <span class="col header">{{ $t('comment') }}</span>
            <span class="col header">{{ $t('date') }}</span>
            <span class="col header">{{ $t('reported-by') }}</span>
            <span class="col fg-1 header">{{ $t('conversation') }}</span>
            <span class="col fg-1 header">{{ $t('actions') }}</span>
        </div>
        <template v-for="(report, index) in user.reports" :key="index">
            <div class="row">
                <div class="col fg-1">
                    {{parseReason(report.reason)}}
                </div>
                <div class="col">
                    {{report.comment}}
                </div>
                <div class="col">
                    {{parseDateTime(report.reported_date)}}
                </div>
                <div class="col">
                    {{report.reported_by_name}}
                </div>
                <div class="col fg-1">
                    <div v-if="report.conversation">
                        {{ $t('yes')}}
                        <Tooltip :text="$t('show-conversation')">
                            <FaIcon 
                                icon="magnifying-glass" 
                                class="icon"
                                @click.stop.prevent="showConversation(report.conversation)" />
                        </Tooltip>
                    </div>
                    <div v-else-if="report.group_id">
                        {{ $t('group') }}
                        <Tooltip :text="$t('show-group-messages')">
                            <FaIcon 
                                icon="magnifying-glass" 
                                class="icon"
                                @click.stop.prevent="showGroupMessages(report.id)" />
                        </Tooltip>
                    </div>
                    <div v-else>
                        {{ $t('no')}}
                    </div>
                </div>
                <div class="col fg-1">
                    <Tooltip :text="$t('close-report')">
                        <FaIcon 
                            icon="trash"
                            class="icon"
                            @click.stop.prevent="closeReport(report)" />
                    </Tooltip>
                </div>
            </div>
        </template>

        <Modal :show="showConversationModal"
               :title="$t('see-conversation-reporter')" 
               @close="closeShowConversation">
            <ShowConversationModal 
                v-if="conversationIdToShow !== null" 
                :conversationId="parseInt(conversationIdToShow)" 
                @close="closeShowConversation"/>
        </Modal>
        <Modal :show="showGroupMessagesModal"
               :title="$t('group-messages')"
               @close="closeShowGroupMessages"
               @click.stop>
            <ShowGroupMessagesModal v-if="reportToShowId" :reported-user="user" :report-id="reportToShowId" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import type {UserToSuspend} from 'resources/types/user';
import {ref} from 'vue';
import {parseDateTime} from '/js/services/dateService';
import {useMainStore} from '/js/store/store';
import {useAdminStore} from '/js/store/adminStore';
import ShowConversationModal from './ShowConversationModal.vue';
import {useI18n} from 'vue-i18n';
import {ReportedUser} from 'resources/types/admin';
import ShowGroupMessagesModal from './ShowGroupMessagesModal.vue';
const {t} = useI18n();
const mainStore = useMainStore();
const adminStore = useAdminStore();

defineProps<{user: UserToSuspend}>();

function parseReason(reason: string) {
    let string = reason.replaceAll('_', ' ').toLowerCase();
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/** Opens the modal to see a conversation between reported user and reporter */
const conversationIdToShow = ref<string | null>(null);
const showConversationModal = ref(false);
function showConversation(conversationId: string) {
    mainStore.clearErrors();
    conversationIdToShow.value = conversationId;
    showConversationModal.value = true;
}
function closeShowConversation() {
    conversationIdToShow.value = null;
    showConversationModal.value = false;
}
const reportToShowId = ref<number | null>(null);
const showGroupMessagesModal = ref(false);
function showGroupMessages(reportId: number) {
    reportToShowId.value = reportId;
    showGroupMessagesModal.value = true;
}
function closeShowGroupMessages() {
    reportToShowId.value = null;
    showGroupMessagesModal.value = false;
}
function closeReport(report: ReportedUser) {
    if (confirm(t('close-report-confirmation', {report: report.id}))) {
        adminStore.closeReport(report);
    }
}
</script>