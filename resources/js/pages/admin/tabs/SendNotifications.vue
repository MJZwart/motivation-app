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
                    :rows=5
                    :label="$t('text')"
                    name="text"/>
                <button v-if="!addLink" @click="addLink = true">{{$t('add-link')}}</button>
                <div v-if="addLink">
                    <Input 
                        id="link" 
                        v-model="notification.link" 
                        type="text" 
                        name="link" 
                        :label="$t('link')"
                        :placeholder="$t('link')" />
                    <small class="silent mb-1">{{$t('ensure-link-correct')}}</small>
                    <Input 
                        id="link_text" 
                        v-model="notification.link_text" 
                        type="text" 
                        name="link_text" 
                        :label="$t('link-text')"
                        :placeholder="$t('link-text')" />
                    <small class="silent mb-1">{{$t('link-text-details')}}</small>
                        
                    <div class="d-flex">
                        <button class="ml-auto button-red" @click="removeLink">{{$t('remove-link')}}</button>
                    </div>
                </div>
                <button type="submit" class="block">{{ $t('send-notification') }}</button>
            </form>
        </div>
    </div>
</template>


<script setup lang="ts">
import {ref} from 'vue';
import {Notification} from 'resources/types/notification';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

const notification = ref<Notification>(newNotificationInstance());

/** @type {boolean} */
const addLink = ref(false);

/** Sends notification to all members */
function sendNotification() {
    adminStore.sendNotification(notification.value);
}

/** Removes the entered link data and hides the field */
function removeLink() {
    addLink.value = false;
    notification.value.link = null;
    notification.value.link_text = null;
}

function newNotificationInstance() {
    return {
        title: '',
        text: '',
    } as Notification;
}

</script>