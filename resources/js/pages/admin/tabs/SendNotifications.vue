<template>
    <div>
        <h3>{{ $t('send-notification-to-users') }}</h3>
        <div>
            <form @submit.prevent="sendNotification">
                <div class="form-group">
                    <label for="title">{{$t('title')}}</label>
                    <input 
                        id="title" 
                        v-model="notification.title" 
                        type="text" 
                        name="title" 
                        :placeholder="$t('title')" />
                </div>
                <div class="form-group">
                    <label for="text">{{$t('text')}}</label>
                    <textarea 
                        id="text" 
                        v-model="notification.text" 
                        rows="5"
                        type="text" 
                        name="text"/>
                </div>

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