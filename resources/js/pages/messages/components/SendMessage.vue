<template>
    <div>
        <h5>{{ sendMessageTitle }}</h5>
        <form @submit.prevent="sendMessage">
            <div class="form-group">
                <label for="message">{{$t('message')}}</label>
                <Textarea 
                    id="message" 
                    v-model="message.message"
                    name="message" 
                    :rows=3
                    :placeholder="$t('send-message-placeholder')"  />
                <BaseFormError name="message" /> 
            </div>
            <button type="submit" class="block">{{ $t('send-message') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
        </form>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import {useMessageStore} from '/js/store/messageStore';
const messageStore = useMessageStore();

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    conversation: {
        type: Object,
        required: false,
    },
});
const emit = defineEmits(['close']);

const message = ref({
    message: '',
});

function close() {
    emit('close');
}
async function sendMessage() {
    message.value.recipient_id = props.user.id;
    if (props.conversation) {
        message.value.conversation_id = props.conversation.conversation_id;
    }
    await messageStore.sendMessage(message.value)
    emit('close');
}
const sendMessageTitle = computed(() => props.user.username ?
    `Send message to ${props.user.username}` :
    `Send message to User ${props.user.id}`);
</script>
