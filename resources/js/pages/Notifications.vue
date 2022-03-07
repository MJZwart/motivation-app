<template>
    <div>
        <h2>{{ $t('notifications') }}</h2>
        <Loading v-if="loading" />
        <notification-block v-for="notification in notifications" v-else  :key="notification.id" :notification="notification" />
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import NotificationBlock from '../components/small/NotificationBlock.vue';
import Loading from '../components/Loading.vue';

export default {
    components: {NotificationBlock, Loading},
    data() {
        return {
            loading: true,
        }
    },
    mounted() {
        this.$store.dispatch('notification/getNotifications').then(() => this.loading = false);
    },
    computed: {
        ...mapGetters({
            notifications: 'notification/getNotifications',
        }),
    },
}
</script>