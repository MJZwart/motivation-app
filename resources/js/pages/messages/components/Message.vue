<template>
    <div class="break-word"
         :class="{hover: hover}"
         @mouseover="hover = true"
         @mouseleave="hover = false"
    >
        <p class="mb-0">{{getSender}} {{message.message}}</p>
        <p class="silent d-flex">
            {{parseDateTime(message.created_at)}}
            <span v-if="hover" class="ml-auto"> 
                <Icon 
                    :icon="TRASH"
                    class="delete-icon red message-icon"
                    @click="deleteMessage()" />
            </span>
        </p>
    </div>
</template>

<script setup lang="ts">
import {computed, ref} from 'vue';
import {parseDateTime} from '/js/services/dateService';
import {useI18n} from 'vue-i18n'
import type {Message} from 'resources/types/message';
import {TRASH} from '/js/constants/iconConstants';
const {t} = useI18n() // use as global scope

const props = defineProps<{message: Message}>();
const emit = defineEmits(['deleteMessage'])

const hover = ref(false);

const getSender = computed(() => props.message.sent_by_user ? t('you')+': ' : props.message.sender?.username + ': ');

function deleteMessage() {
    emit('deleteMessage', props.message);
}
</script>

<style lang="scss" scoped>
.message-icon {
    margin-bottom: -7px;
}
.hover {
    background-color: var(--hover);
}
</style>