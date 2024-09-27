<template>
    <div>
        <h5>{{ $t('send-message-to', {user: props.user.username}) }}</h5>
        <div class="form-group">
            <label for="message">{{ $t('message') }}</label>
            <SimpleTextarea
                id="message"
                v-model="message.message"
                name="message"
                :rows="3"
                :placeholder="$t('send-message-placeholder')"
            />
            <BaseFormError name="message" />
        </div>    
        <FormControls
            :submit-text="$t('send-message')"
            @submit="prepareAndSendMessage"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import type {Conversation, NewMessage} from 'resources/types/message';
import type {Friend} from 'resources/types/friend';
import type {StrippedUser} from 'resources/types/user';
import {ref} from 'vue';
import FormControls from '/js/components/global/FormControls.vue';
import { sendMessage } from '/js/services/messageService';

const props = defineProps<{
    user: Friend | StrippedUser,
    conversation?: Conversation,
}>();
const emit = defineEmits(['close']);

const message = ref<NewMessage>({
    message: '',
});
async function prepareAndSendMessage() {
    message.value.recipient_id = props.user.id;
    if (props.conversation) {
        message.value.conversation_id = props.conversation.conversation_id;
    }
    await sendMessage(message.value);
    emit('close');
}
</script>
