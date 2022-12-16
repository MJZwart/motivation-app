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
            <SubmitButton id="send-message-button" class="ml-auto" @click="sendMessage">{{$t('send-message')}}</SubmitButton>
        </div>
        <div v-if="messages && messages[0]" class="group-messages">
            <div v-for="(message, index) in messages" :key="index" class="group-message">
                <GroupMessageComp 
                    :message="message" 
                    :can-delete="canDelete(message)" 
                    :user-id="user!.id" 
                    @delete-message="deleteMessage"
                />
            </div>
        </div>
        <div v-else>
            {{$t('no-messages-group')}}
        </div>
    </div>
</template>

<script setup lang="ts">
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import type {GroupMessage, GroupPage} from 'resources/types/group';
import {computed, onMounted, ref} from 'vue';
import {useGroupStore} from '/js/store/groupStore';
import GroupMessageComp from './GroupMessage.vue';
import {useUserStore} from '/js/store/userStore';

const props = defineProps<{group: GroupPage}>();

const groupStore = useGroupStore();
const userStore = useUserStore();
const loading = ref(true);

const user = computed(() => userStore.user);

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

function canDelete(message: GroupMessage) {
    if (props.group.rank.can_moderate_messages) return true;
    return message.user.id === user.value?.id;
}

async function deleteMessage(message: GroupMessage) {
    messages.value = await groupStore.deleteMessage(props.group.id, message.id);
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