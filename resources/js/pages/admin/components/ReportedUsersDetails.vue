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
                    {{suspended.admin_username}}
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
                            <Icon 
                                :icon="DETAILS" 
                                class="details-icon"
                                @click.stop.prevent="showConversation(report.conversation)" />
                        </Tooltip>
                    </div>
                    <div v-else-if="report.group_id">
                        {{ $t('group') }}
                        <Tooltip :text="$t('show-group-messages')">
                            <Icon 
                                :icon="DETAILS" 
                                class="details-icon"
                                @click.stop.prevent="showGroupMessages(report.id)" />
                        </Tooltip>
                    </div>
                    <div v-else>
                        {{ $t('no')}}
                    </div>
                </div>
                <div class="col fg-1">
                    <Tooltip :text="$t('close-report')">
                        <Icon 
                            :icon="TRASH"
                            class="delete-icon"
                            @click.stop.prevent="closeReport(report)" />
                    </Tooltip>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
import type {UserToSuspend} from 'resources/types/user';
import {parseDateTime} from '/js/services/dateService';
import {useMainStore} from '/js/store/store';
import ShowConversationModal from './ShowConversationModal.vue';
import {useI18n} from 'vue-i18n';
import {ReportedUser} from 'resources/types/admin';
import ShowGroupMessagesModal from './ShowGroupMessagesModal.vue';
import {DETAILS, TRASH} from '/js/constants/iconConstants';
import {showModal} from '/js/components/modal/modalService';
const {t} = useI18n();
const mainStore = useMainStore();

const props = defineProps<{user: UserToSuspend}>();
const emit = defineEmits(['close-report']);

function parseReason(reason: string) {
    let string = reason.replaceAll('_', ' ').toLowerCase();
    return string.charAt(0).toUpperCase() + string.slice(1);
}

/** Opens the modal to see a conversation between reported user and reporter */
function showConversation(conversationId: string) {
    mainStore.clearErrors();
    showModal({conversationId: parseInt(conversationId)}, ShowConversationModal, 'see-conversation-reporter');
}

/** Opens the modal to see the conversation in a group where a user was reported from */
function showGroupMessages(reportId: number) {
    showModal({reportedUser: props.user, reportId: reportId}, ShowGroupMessagesModal, 'group-messages');
}

function closeReport(report: ReportedUser) {
    if (confirm(t('close-report-confirmation', {report: report.id}))) {
        emit('close-report', report);
    }
}
</script>