<template>
    <div>
        <h3>{{ $t('admin-bug-panel') }}</h3>
        <div>
            <h4>{{ "this is an admin bug panel" }}</h4>

        </div>
    </div>
</template>


<script>
export default {
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
    },
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