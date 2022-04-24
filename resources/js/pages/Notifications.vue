<template>
    <div>
        <h2>{{ $t('notifications') }}</h2>
        <Loading v-if="loading" />
        <div v-else>
            <notification-block v-for="notification in notifications" :key="notification.id" :notification="notification" />
        </div>
    </div>
</template>


<script setup>// @ts-nocheck

import {ref, computed} from 'vue';
import NotificationBlock from '../components/small/NotificationBlock.vue';
import Loading from '../components/Loading.vue';
import {onMounted} from 'vue';
import {useMessageStore} from '@/store/messageStore';

const loading = ref(true);
const messageStore = useMessageStore();
const notifications = computed(() => sortedNotifications());

onMounted(async() => {
    await messageStore.getNotifications();
    loading.value = false;
});

function sortedNotifications(){
    let notifications = messageStore.notifications;
    if (!notifications) return notifications;
    return notifications.sort((a,b) => Date.parse(b.created_at) - Date.parse(a.created_at));
}
</script>