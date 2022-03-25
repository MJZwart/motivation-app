<template>
    <div>
        <Loading v-if="loading" />
        <b-tabs v-else card pills vertical>
            <b-tab title="Achievements">
                <achievements />
            </b-tab>
            <b-tab title="Bug reports">
                <bug-report-panel />
            </b-tab>
            <b-tab title="Send notification">
                <send-notifications />
            </b-tab>
            <b-tab active title="Balancing">
                <balancing />
            </b-tab>
            <b-tab title="Reported Users">
                <reported-users />
            </b-tab>
        </b-tabs>
    </div>
</template>


<script>
import Achievements from '../components/tabs/admin/Achievements.vue';
import BugReportPanel from '../components/tabs/admin/BugReportPanel.vue';
import SendNotifications from '../components/tabs/admin/SendNotifications.vue';
import Balancing from '../components/tabs/admin/Balancing.vue';
import Loading from '../components/Loading.vue';
import ReportedUsers from '../components/tabs/admin/ReportedUsers.vue';

export default {
    components: {
        Achievements, BugReportPanel, SendNotifications, Balancing, ReportedUsers, Loading,
    },
    mounted() {
        this.$store.dispatch('admin/getAdminDashboard').then(() => this.loading = false);
    },
    data() {
        return {
            loading: true,
        }
    },

}
</script>