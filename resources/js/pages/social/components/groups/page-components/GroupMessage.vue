<template>
    <div v-if="message" class="break-word group-message"
         :class="{hover: hover}"
         @mouseover="hover = true"
         @mouseleave="hover = false"
    >
        <p>{{message.message}}</p>
        <p class="silent d-flex">
            <router-link class="silent clear-link" :to="{name: 'profile', params: {id: message.user.id}}">
                {{message.user.username}}
            </router-link> - {{parseDateTime(message.created_at)}}
            <span v-if="hover" class="ml-auto"> 
                <Tooltip v-if="canDelete" :text="$t('delete-message')">
                    <Icon 
                        :icon="TRASH"
                        class="delete-icon red message-icon"
                        @click="deleteMessage()" />
                </Tooltip>
                <Tooltip v-if="message.user.id !== userId" :text="$t('report-user')">
                    <Icon 
                        :icon="REPORT"
                        class="report-icon red message-icon"
                        @click="reportMessage()" />
                </Tooltip>
            </span>
        </p>
    </div>
</template>

<script setup lang="ts">
import ReportUser from '/js/pages/messages/components/ReportUser.vue';
import type {GroupMessage} from 'resources/types/group';
import {ref} from 'vue';
import {parseDateTime} from '/js/services/dateService';
import {REPORT, TRASH} from '/js/constants/iconConstants';
import {showModal} from '/js/components/modal/modalService';

const props = defineProps<{message: GroupMessage, canDelete: boolean, userId: number, groupId: number}>();
const emit = defineEmits(['deleteMessage'])

const hover = ref(false);

function deleteMessage() {
    emit('deleteMessage', props.message);
}

function reportMessage() {
    showModal({user: props.message.user, group_id: props.message.group_id}, ReportUser, 'report-user');
}
</script>

<style lang="scss">
.group-message.hover {
    background-color: var(--background-darker);
}
.message-icon {
    margin-bottom: -7px;
}
.group-message {
    padding: 0.05rem 0.5rem 0.05rem 0.5rem;
    border-radius: 0.25rem;
    p {
        margin: 0.8rem 0.5rem 0.8rem 0.5rem;
    }
}
</style>