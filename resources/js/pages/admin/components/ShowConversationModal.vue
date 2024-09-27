<template>
    <div>
        <Loading v-if="loading" />
        <template v-if="!loading && conversation">
            <ShowMessage v-for="message in conversation.messages" :key="message.id"
                         :message="message" />
        </template>
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref} from 'vue';
import ShowMessage from './ShowMessage.vue';
import {ReportedConversation} from 'resources/types/message';
import axios from 'axios';

const props = defineProps({
    conversationId: {
        type: Number,
        required: true,
    },
});

onMounted(async() => {
    const {data} = await axios.get(`/admin/conversation/${props.conversationId}`);
    conversation.value = data.data;
    loading.value = false;
});

const loading = ref(true);

const conversation = ref<ReportedConversation | null>(null);
</script>