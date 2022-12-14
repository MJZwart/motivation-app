<template>
    <div v-if="message" class="break-word group-message"
         :class="{hover: hover}"
         @mouseover="hover = true"
         @mouseleave="hover = false"
    >
        <p>{{message.message}}</p>
        <p class="silent d-flex">
            <router-link class="silent" :to="{name: 'profile', params: {id: message.user.id}}">
                {{message.user.username}}
            </router-link> - {{parseDateTime(message.created_at)}}
            <span v-if="canDelete && hover" class="ml-auto"> 
                <FaIcon 
                    icon="trash"
                    class="icon small red message-icon"
                    @click="deleteMessage()" />
            </span>
        </p>
    </div>
</template>

<script setup lang="ts">
import type {GroupMessage} from 'resources/types/group';
import {ref} from 'vue';
import {parseDateTime} from '/js/services/dateService';

const props = defineProps<{message: GroupMessage, canDelete: boolean}>();
const emit = defineEmits(['deleteMessage'])

const hover = ref(false);

function deleteMessage() {
    emit('deleteMessage', props.message);
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