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
            @submit="sendMessage"
            @cancel="$emit('close')"
        />
    </div>
</template>

<script setup lang="ts">
import type {Conversation, NewMessage} from 'resources/types/message';
import type {Friend} from 'resources/types/friend';
import type {StrippedUser} from 'resources/types/user';
import {ref} from 'vue';
import {useMessageStore} from '/js/store/messageStore';
import FormControls from '/js/components/global/FormControls.vue';
const messageStore = useMessageStore();

const props = defineProps<{
    user: Friend | StrippedUser,
    conversation?: Conversation,
}>();
const emit = defineEmits(['close']);

const message = ref<NewMessage>({
    message: '',
});
async function sendMessage() {
    message.value.recipient_id = props.user.id;
    if (props.conversation) {
        message.value.conversation_id = props.conversation.conversation_id;
    }
    await messageStore.sendMessage(message.value);
    emit('close');
}
</script>
