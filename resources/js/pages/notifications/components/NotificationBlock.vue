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
                <p v-if="notification.links">
                    <span v-for="(item, index) in notification.links" :key="index" :class="linkClass">
                        <router-link v-if="!item.link.api" :to="item.link.url">{{$t(item.text)}}</router-link>
                        <span v-else>
                            <span @click="prop.notification.link_active ? linkAction(item.link.api, item.link.url) : null">
                                {{$t(item.text)}}
                            </span>
                            <span v-if="index < notification.links.length -1"> / </span>
                        </span>
                    </span>
                </p>
                <p>{{ $t('received-on') }}: {{parseDateTime(notification.created_at)}}</p>
            </slot>
        </Summary>
    </div>
</template>


<script setup>
import Summary from '/js/components/global/Summary.vue';
import {parseDateTime} from '/js/services/dateService';
import {useI18n} from 'vue-i18n';
import {useMessageStore} from '/js/store/messageStore';
import {computed} from 'vue';
import {handleNotificationLink} from '/js/services/notificationLinkService';

const {t} = useI18n();
const messageStore = useMessageStore();

const prop = defineProps({
    notification: {
        /** @type {import('resources/types/notification').Notification} */
        type: Object,
        required: true,
    },
});

/** @type {String} */
const linkClass = computed(() => prop.notification.link_active ? 'notification-link active' : 'notification-link disabled');

/**
 * Deletes the notification
 */
function deleteNotification() {
    if (confirm(t('delete-notification-confirmation'))) {
        messageStore.deleteNotification(prop.notification.id);
    }
}

/**
 * Performs whatever action the link has built in. If the links are set to
 * disable on action, fire the event to disable the link to prevent double action
 * 
 * @param {String} apiType
 * @param {String} linkUrl
 */
async function linkAction(apiType, linkUrl) {
    handleNotificationLink(apiType, linkUrl);
    if (prop.notification.delete_links_on_action)
        await messageStore.deleteNotificationAction(prop.notification.id);
}
</script>


<style lang="scss" scoped>
.notification-link.active {
    cursor: pointer;
}
.notification-link.disabled {
    text-decoration-line: line-through;
    background-color: inherit;
}
</style>