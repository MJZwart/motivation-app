<template>
    <div>
        <Summary class="mb-2">
            <template #header>
                <span class="d-flex">
                    {{notification.title}}
                    <span class="ml-auto">
                        <span v-if="!notification.read">{{ $t('new') }} </span>
                        <FaIcon 
                            icon="trash"
                            class="icon small red"
                            @click="deleteNotification()" />
                    </span>
                </span>
            </template>
            <slot>
                <p>{{notification.text}}</p>
                <p>{{ $t('received-on') }}: {{parseDateTime(notification.created_at)}}</p>
            </slot>
        </Summary>
    </div>
</template>


<script setup>
import Summary from '/js/components/global/Summary.vue';
import {parseDateTime} from '/js/services/timezoneService';
import {useI18n} from 'vue-i18n'
import {useMessageStore} from '/js/store/messageStore';

const {t} = useI18n() // use as global scope
const messageStore = useMessageStore();

const prop = defineProps({
    notification: {
        /** @type {import('resources/types/notification').Notification} */
        type: Object,
        required: true,
    },
});

function deleteNotification() {
    if (confirm(t('delete-notification-confirmation'))) {
        messageStore.deleteNotification(prop.notification.id);
    }
}
</script>


<style>
</style>