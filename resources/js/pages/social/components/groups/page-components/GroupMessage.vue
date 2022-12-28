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
                    <FaIcon 
                        icon="trash"
                        class="icon small red message-icon"
                        @click="deleteMessage()" />
                </Tooltip>
                <Tooltip v-if="message.user.id !== userId" :text="$t('report-user')">
                    <FaIcon 
                        :icon="['far', 'flag']"
                        class="icon small red message-icon"
                        @click="reportMessage()" />
                </Tooltip>
            </span>
        </p>
        <Modal :show="showReportUserModal" :header="false" @close="closeReportUserModal">
            <ReportUser :user="message.user" :group_id="groupId" @close="closeReportUserModal" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import ReportUser from '/js/pages/messages/components/ReportUser.vue';
import type {GroupMessage} from 'resources/types/group';
import {ref} from 'vue';
import {parseDateTime} from '/js/services/dateService';

const props = defineProps<{message: GroupMessage, canDelete: boolean, userId: number, groupId: number}>();
const emit = defineEmits(['deleteMessage'])

const hover = ref(false);

function deleteMessage() {
    emit('deleteMessage', props.message);
}

const showReportUserModal = ref(false);
function reportMessage() {
    showReportUserModal.value = true;
}
function closeReportUserModal() {
    showReportUserModal.value = false;
}
</script>

<style lang="scss" scoped>
.hover {
    background-color: var(--hover);
}
.message-icon {
    margin-bottom: -1px;
}
.group-message {
    padding: 0.05rem 0.5rem 0.05rem 0.5rem;
    border-radius: 0.25rem;
    p {
        margin: 0.8rem 0.5rem 0.8rem 0.5rem;
    }
}
</style>