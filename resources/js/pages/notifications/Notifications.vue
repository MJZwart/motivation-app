<template>
    <div class="w-60-flex center">
        <h2>{{ $t('notifications') }}</h2>
        <Loading v-if="loading" />
        <div v-else>
            <div v-if="notifications && notifications.length > 0">
                <Pagination v-if="notifications" :items="notifications">
                    <template #items="items">
                        <NotificationBlock 
                            v-for="(notification, index) in items" 
                            :key="index" 
                            :notification="notification" 
                            @delete="deleteNotification"
                            @delete-action="deleteNotificationAction"
                        />
                    </template>
                </Pagination>
            </div>
            <div v-else>
                <h5>{{$t('no-notifications')}}</h5>
            </div>
        </div>
    </div>
</template>


<script lang="ts" setup>
import Pagination from '/js/components/global/Pagination.vue';
import {computed, onBeforeUnmount, ref, watchEffect} from 'vue';
import NotificationBlock from './components/NotificationBlock.vue';
import {onMounted} from 'vue';
import {useMessageStore} from '/js/store/messageStore';
import {useUserStore} from '/js/store/userStore';
import {socketConnected} from '/js/services/websocketService';

const loading = ref(true);
const messageStore = useMessageStore();
const notifications = ref<Notification[]>([]);
const userStore = useUserStore();

const user = computed(() => userStore.user);

const active = ref(false);

onMounted(async() => {
    notifications.value = await messageStore.getNotifications();
    active.value = true;
    loading.value = false;
});

async function deleteNotification(notificationId: number) {
    notifications.value = await messageStore.deleteNotification(notificationId);
}
async function deleteNotificationAction(notificationId: number) {
    notifications.value = await messageStore.deleteNotificationAction(notificationId);
}

/*
Websockets
*/
function listenToNewNotifcations() {
    if (!user.value) return;
    window.Echo.private(`unread.${user.value.id}`)
        .listen('NewNotification', async () => {
            if (active.value) notifications.value = await messageStore.getNotifications();
        });
}

watchEffect(() => {
    if (socketConnected.value) listenToNewNotifcations()
});

onBeforeUnmount(() => {
    active.value = false;
});
</script>