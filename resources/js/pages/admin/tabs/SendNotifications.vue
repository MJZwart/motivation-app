<template>
    <div>
        <h3>{{ $t('send-notification-to-users') }}</h3>
        <div>
            <form @submit.prevent="sendNotification">
                <Input 
                    id="title" 
                    v-model="notification.title" 
                    type="text" 
                    name="title" 
                    :label="$t('title')"
                    :placeholder="$t('title')" />
                <Textarea 
                    id="text" 
                    v-model="notification.text" 
                    :rows="5"
                    :label="$t('text')"
                    name="text"/>

                <button type="submit" class="block">{{ $t('send-notification') }}</button>
            </form>
        </div>
    </div>
</template>


<script setup>
import {ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

/** @type {import('resources/types/notification').Notification} */
const notification = ref({});

/** Sends notification to all members */
function sendNotification() {
    adminStore.sendNotification(notification.value);
}

</script>