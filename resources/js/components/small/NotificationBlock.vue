<template>
    <div>
        <div :class="{unread: !notification.read}">
            <span class="card-title d-flex">
                {{notification.title}}
                <b-icon-trash class="ml-auto red pointer" @click="deleteNotification()" />
            </span>
            <div class="side-border bottom-border">
                <p>{{notification.text}}</p>
                <p>{{ $t('received-on') }}: {{notification.created_at}}</p>
            </div>
        </div>
        <br />
    </div>
</template>


<script>
export default {
    props: {
        notification: {
            /** @type {import('resources/types/notification').Notification} */
            type: Object,
            required: true,
        },
    },
    methods: {
        deleteNotification() {
            if (confirm(this.$t('delete-notification-confirmation'))) {
                this.$store.dispatch('notification/deleteNotification', this.notification.id);
            }
        },
    },
}
</script>


<style>
.unread {
    background-color:#eeeeee;
    border-radius:5px;
}
</style>