<template>
    <div>
        <h2>Notifications</h2>
        <notification-block v-for="notification in notifications" :key="notification.id" :notification="notification"></notification-block>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import NotificationBlock from "../components/small/NotificationBlock.vue";

export default {
    components: { NotificationBlock },
    mounted(){
        this.$store.dispatch('notification/getNotifications');
    },
    computed: {
        ...mapGetters({
            notifications: 'notification/getNotifications',
        }),
    },
}
</script>


<style>

</style>