<template>
    <div>
        <h3>{{ $t('send-notification-to-users') }}</h3>
        <div>
            <b-form @submit.prevent="sendNotification">
                <b-form-group
                    :label="$t('title')" 
                    label-for="title">
                    <b-form-input 
                        id="title" 
                        v-model="notification.title" 
                        type="text" 
                        name="title" 
                        :placeholder="$t('title')" />
                </b-form-group>
                <b-form-group
                    :label="$t('text')" 
                    label-for="text">
                    <b-form-textarea 
                        id="text" 
                        v-model="notification.text" 
                        rows="5"
                        type="text" 
                        name="text"/>
                </b-form-group>

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