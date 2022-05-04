<template>
    <div>
        <Loading v-if="loading" />
        <template v-else>
            <ShowMessage v-for="message in conversation.messages" :key="message.id"
                         :message="message" />
        </template>
    </div>
</template>

<script setup>
import {onMounted, ref} from 'vue';
import ShowMessage from './ShowMessage.vue';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

const props = defineProps({
    conversationId: {
        type: Number,
        required: true,
    },
});

onMounted(async() => {
    conversation.value = await adminStore.fetchConversation(props.conversationId)
    loading.value = false;
});

const loading = ref(true);

/** @type import('resources/types/message').Conversation | null */
const conversation = ref({});
</script>