<template>
    <div>
        <b-tabs card pills vertical>
            <b-tab title="Achievements">
                <achievements />
            </b-tab>
            <b-tab title="Bug reports">
                <bug-report-panel />
            </b-tab>
            <b-tab title="Send notification">
                <send-notifications />
            </b-tab>
        </b-tabs>
    </div>
</template>


<script>
import Achievements from '../components/tabs/admin/Achievements.vue';
import BugReportPanel from '../components/tabs/admin/BugReportPanel.vue';
import SendNotifications from '../components/tabs/admin/SendNotifications.vue';

export default {
    components: {
        Achievements, BugReportPanel, SendNotifications,
    },
    mounted() {
        this.$store.dispatch('admin/getAdminDashboard');
    },

}
</script>