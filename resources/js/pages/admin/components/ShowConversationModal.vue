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
import {useAdminStore} from '/js/store/adminStore';
import {ReportedConversation} from 'resources/types/message';
const adminStore = useAdminStore();

const props = defineProps({
    conversationId: {
        type: String,
        required: true,
    },
});

onMounted(async() => {
    conversation.value = await adminStore.fetchConversation(parseInt(props.conversationId));
    loading.value = false;
});

const loading = ref(true);

const conversation = ref<ReportedConversation | null>(null);
</script>