<template>
    <div>
        <Loading v-if="loading" />
        <BTabs v-else class="tab-card" card vertical :options="['pills']">
            <BTab title="Achievements">
                <achievements />
            </BTab>
            <BTab title="Bug reports">
                <bug-report-panel />
            </BTab>
            <BTab title="Send notification">
                <send-notifications />
            </BTab>
            <BTab title="Balancing">
                <balancing />
            </BTab>
            <BTab title="Reported Users">
                <reported-users />
            </BTab>
        </BTabs>
    </div>
</template>


<script>
import Achievements from '../components/tabs/admin/Achievements.vue';
import BugReportPanel from '../components/tabs/admin/BugReportPanel.vue';
import SendNotifications from '../components/tabs/admin/SendNotifications.vue';
import Balancing from '../components/tabs/admin/Balancing.vue';
import Loading from '../components/Loading.vue';
import ReportedUsers from '../components/tabs/admin/ReportedUsers.vue';
import BTabs from '../components/bootstrap/BTabs.vue';
import BTab from '../components/bootstrap/BTab.vue';

export default {
    components: {
        Achievements, BugReportPanel, SendNotifications, Balancing, ReportedUsers, Loading, BTab, BTabs,
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