<template>
    <div>
        <h2>{{ $t('notifications') }}</h2>
        <Loading v-if="loading" />
        <div v-else>
            <NotificationBlock v-for="notification in notifications" :key="notification.id" :notification="notification" />
        </div>
    </div>
</template>


<script setup>// @ts-nocheck

import {ref, computed} from 'vue';
import NotificationBlock from './components/NotificationBlock.vue';
import {onMounted} from 'vue';
import {useMessageStore} from '/js/store/messageStore';

const loading = ref(true);
const messageStore = useMessageStore();
const notifications = computed(() => messageStore.notifications);

onMounted(async() => {
    await messageStore.getNotifications();
    loading.value = false;
});
</script>