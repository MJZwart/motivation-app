<template>
    <div v-if="message" class="break-word"
         @mouseover="showActionButtons = true"
         @mouseleave="showActionButtons = false">
        <p class="mb-0">{{getSender}} {{message.message}}</p>
        <p class="silent d-flex">
            {{message.created_at}}
            <span v-if="showActionButtons" class="ml-auto"> 
                <FaIcon 
                    icon="trash"
                    class="icon small red"
                    @click="deleteMessage()" />
            </span>
        </p>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope

const props = defineProps({
    message: {
        type: Object,
        required: true,
    },
});
const emit = defineEmits(['deleteMessage'])

const showActionButtons = ref(false);

const getSender = computed(() => props.message.sent_by_user ? t('you')+': ' : props.message.sender.username + ': ');

function deleteMessage() {
    emit('deleteMessage', props.message);
}
</script>
