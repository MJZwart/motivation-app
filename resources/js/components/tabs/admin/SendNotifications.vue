<template>
    <div>
        <h3>{{ $t('send-notification-to-users') }}</h3>
        <div>
            <b-form @submit.prevent="sendNotification">
                <div class="form-group">
                    <label for="title">{$t('title'){}}</label>
                    <input 
                        id="title" 
                        v-model="notification.title" 
                        class="form-control"
                        type="text" 
                        name="title" 
                        :placeholder="$t('title')" />
                </div>
                <div class="form-group">
                    <label for="text">{{$t('text')}}</label>
                    <b-form-textarea 
                        id="text" 
                        v-model="notification.text" 
                        rows="5"
                        type="text" 
                        name="text"/>
                </div>

                <b-button type="submit" block>{{ $t('send-notification') }}</b-button>
            </b-form>
        </div>
    </div>
</template>


<script>
export default {
    data() {
        return {
            /** @type {import('resources/types/notification').Notification} */
            notification: {},
        }
    },
    methods: {
        /** Sends notification to all members */
        sendNotification() {
            this.$store.dispatch('admin/sendNotification', this.notification);
        },
    },
    
}
</script>