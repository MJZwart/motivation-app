<template>
    <div class="w-60-flex center">
        <h2>{{ $t('notifications') }}</h2>
        <Loading v-if="loading" />
        <div v-else>
            <div v-if="notifications && notifications.length > 0">
                <Pagination v-if="notifications" :items="notifications">
                    <template #items="items">
                        <NotificationBlock v-for="(notification, index) in items" :key="index" :notification="notification" />
                    </template>
                </Pagination>
            </div>
            <div v-else>
                <h5>{{$t('no-notifications')}}</h5>
            </div>
        </div>
    </div>
</template>


<script setup>
import Pagination from '/js/components/global/Pagination.vue';
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