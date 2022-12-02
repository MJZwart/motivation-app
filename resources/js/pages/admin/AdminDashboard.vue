<template>
    <Loading v-if="loading" />
    <ResponsiveTabs :tabs="tabs" />
</template>

<script setup lang="ts">
import Achievements from './tabs/Achievements.vue';
import BugReportPanel from './tabs/BugReportPanel.vue';
import SendNotifications from './tabs/SendNotifications.vue';
import Balancing from './tabs/Balancing.vue';
import ReportedUsers from './tabs/ReportedUsers.vue';
import Feedback from './tabs/Feedback.vue';
import SuspendedUsers from './tabs/SuspendedUsers.vue';
import Overview from './tabs/Overview.vue';
import Actions from './tabs/Actions.vue';
import ResponsiveTabs from '/js/components/global/tabs/ResponsiveTabs.vue';
import {onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';

const loading = ref(true);
const adminStore = useAdminStore();

onMounted(async() => {
    await adminStore.getAdminDashboard();
    loading.value = false;
});

const tabs = [
    {name: 'overview', component: Overview},
    {name: 'achievements', component: Achievements},
    {name: 'bug-reports', component: BugReportPanel},
    {name: 'send-notification', component: SendNotifications},
    {name: 'balancing', component: Balancing},
    {name: 'reported-users', component: ReportedUsers},
    {name: 'suspended-users', component: SuspendedUsers},
    {name: 'feedback', component: Feedback},
    {name: 'actions', component: Actions},
];
</script>