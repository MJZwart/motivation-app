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
import {useUserStore} from '/js/store/userStore';
import {socketConnected} from '/js/services/websocketService';
import { getUnread } from '/js/services/messageService';
import axios from 'axios';

const loading = ref(true);
const notifications = ref<Notification[]>([]);
const userStore = useUserStore();

const user = computed(() => userStore.user);

const active = ref(false);

onMounted(async() => {
    notifications.value = await getNotifications();
    active.value = true;
    loading.value = false;
});

async function deleteNotification(notificationId: number) {
    const {data} = await axios.delete(`/notifications/${notificationId}`);
    notifications.value = data.data.notifications;
}
async function deleteNotificationAction(notificationId: number) {
    const {data} = await axios.put(`/notifications/${notificationId}/disable-action`);
    notifications.value = data.data;
}

async function getNotifications() {
    const {data} = await axios.get('/notifications');
    getUnread();
    return data.data;
}

/*
Websockets
*/
function listenToNewNotifcations() {
    if (!user.value) return;
    window.Echo.private(`unread.${user.value.id}`)
        .listen('NewNotification', async () => {
            if (active.value) notifications.value = await getNotifications();
        });
}

watchEffect(() => {
    if (socketConnected.value) listenToNewNotifcations()
});

onBeforeUnmount(() => {
    active.value = false;
});
</script>