<template>
    <Loading v-if="loading" />
    <div v-else>
        <div class="message-form">
            <SimpleTextarea
                id="message"
                v-model="newMessage.message"
                class="message-input"
                name="message"
                :placeholder="$t('new-message')"
            />
            <SubmitButton class="ml-auto" @click="sendMessage">{{$t('send-message')}}</SubmitButton>
        </div>
        <div v-if="messages && messages[0]" class="group-messages">
            <div v-for="(message, index) in messages" :key="index" class="group-message">
                <GroupMessageComp :message="message" />
            </div>
        </div>
        <div v-else>
            {{$t('no-messages-group')}}
        </div>
    </div>
</template>

<script setup lang="ts">
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import type {Group, GroupMessage} from 'resources/types/group';
import {onMounted, ref} from 'vue';
import {useGroupStore} from '/js/store/groupStore';
import GroupMessageComp from './GroupMessage.vue';

const props = defineProps<{group: Group}>();

const groupStore = useGroupStore();
const loading = ref(true);

const messages = ref<GroupMessage[]>([]);
const newMessage = ref({
    message: '',
});

onMounted(async() => {
    messages.value = await groupStore.getMessages(props.group.id);
    loading.value = false;
});

async function sendMessage() {
    messages.value = await groupStore.postMessage(props.group.id, newMessage.value);
    newMessage.value.message = '';
}
</script>

<style lang="scss" scoped>
.message-form {
    display: flex;
    flex-direction: column;
    .message-input{
        width: 100%;
    }
}
.group-messages {
    background-color: var(--background-darker);
    border-radius: 0.25rem;
}
</style>