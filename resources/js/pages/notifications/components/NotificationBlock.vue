<template>
    <div>
        <Summary class="mb-2">
            <template #header>
                <span class="d-flex">
                    {{notification.title}}
                    <span class="ml-auto">
                        <span v-if="!notification.read">
                            <Icon :icon="NEW" class="non-clickable" />
                        </span>
                        <Icon 
                            :icon="TRASH"
                            class="delete-icon red"
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


<script setup lang="ts">
import Summary from '/js/components/global/Summary.vue';
import {parseDateTime} from '/js/services/dateService';
import {useI18n} from 'vue-i18n';
import {computed, PropType} from 'vue';
import {handleNotificationLink} from '/js/services/notificationLinkService';
import {Notification} from 'resources/types/notification';
import {NEW, TRASH} from '/js/constants/iconConstants';

const {t} = useI18n();

const prop = defineProps({
    notification: {
        type: Object as PropType<Notification>,
        required: true,
    },
});

const emit = defineEmits(['delete', 'deleteAction']);

const linkClass = computed<string>(() => 
    prop.notification.link_active ? 'notification-link active' : 'notification-link disabled');

/**
 * Deletes the notification
 */
function deleteNotification() {
    if (confirm(t('delete-notification-confirmation'))) {
        emit('delete', prop.notification.id);
    }
}

/**
 * Performs whatever action the link has built in. If the links are set to
 * disable on action, fire the event to disable the link to prevent double action
 */
async function linkAction(apiType: string, linkUrl: string) {
    const actionSucceeded = await handleNotificationLink(apiType, linkUrl);
    if (actionSucceeded && prop.notification.delete_links_on_action)
        emit('deleteAction', prop.notification.id);
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