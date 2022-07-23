<template>
    <div v-if="message" class="break-word">
        <p class="mb-0">{{`${message.sender_username}: ${message.message}`}}</p>
        <p class="silent d-flex">
            {{parseDateTime(message.created_at)}}
        </p>
    </div>
</template>

<script setup lang="ts">
import {PropType} from 'vue';
import {ReportedMessage} from 'resources/types/message';
import {parseDateTime} from '/js/services/dateService';

defineProps({
    message: {
        type: Object as PropType<ReportedMessage>,
        required: true,
    },
});
</script>
