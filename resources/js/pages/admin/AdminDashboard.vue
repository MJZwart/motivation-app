<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="row">
            <ResponsiveTabs>
                <button 
                    :class="activeTab('Overview')" 
                    class="tab-item"
                    @click="switchTab('Overview')">
                    {{$t('overview')}}
                </button>
                <button 
                    :class="activeTab('Achievements')" 
                    class="tab-item"
                    @click="switchTab('Achievements')">
                    {{$t('achievements')}}
                </button>
                <button 
                    :class="activeTab('BugReportPanel')" 
                    class="tab-item"
                    @click="switchTab('BugReportPanel')">
                    {{$t('bug-reports')}}
                </button>
                <button 
                    :class="activeTab('SendNotifications')" 
                    class="tab-item"
                    @click="switchTab('SendNotifications')">
                    {{$t('send-notification')}}
                </button>
                <button 
                    :class="activeTab('Balancing')" 
                    class="tab-item"
                    @click="switchTab('Balancing')">
                    {{$t('balancing')}}
                </button>
                <button 
                    :class="activeTab('ReportedUsers')" 
                    class="tab-item"
                    @click="switchTab('ReportedUsers')">
                    {{$t('reported-users')}}
                </button>
                <button 
                    :class="activeTab('SuspendedUsers')" 
                    class="tab-item"
                    @click="switchTab('SuspendedUsers')">
                    {{$t('suspended-users')}}
                </button>
                <button 
                    :class="activeTab('Feedback')" 
                    class="tab-item"
                    @click="switchTab('Feedback')">
                    {{$t('feedback')}}
                </button>
                <button 
                    :class="activeTab('Actions')" 
                    class="tab-item"
                    @click="switchTab('Actions')">
                    {{$t('actions')}}
                </button>
            </ResponsiveTabs>
            <div class="tab-content col-10">
                <component :is="currentTabComponent" :key="tabKey" />
            </div>
        </div>
    </div>
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
import ResponsiveTabs from '/js/components/global/ResponsiveTabs.vue';
import {shallowRef, onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';

const loading = ref(true);
const adminStore = useAdminStore();

onMounted(async() => {
    await adminStore.getAdminDashboard();
    if (window.location.hash)
        tabKey.value = window.location.hash.slice(1);
    else 
        tabKey.value = 'Overview';
    currentTabComponent.value = tabs[tabKey.value];
    loading.value = false;
});

const tabs = {
    'Overview': Overview,
    'Achievements': Achievements,
    'BugReportPanel': BugReportPanel,
    'SendNotifications': SendNotifications,
    'Balancing': Balancing,
    'ReportedUsers': ReportedUsers,
    'SuspendedUsers': SuspendedUsers,
    'Feedback': Feedback,
    'Actions': Actions,
}
const tabKey = ref('Overview');

const currentTabComponent = shallowRef(tabs[0]);
function activeTab(key: string) {
    if (key == tabKey.value) return 'active-tab';
    return 'tab';
}
function switchTab(key: string) {
    currentTabComponent.value = tabs[key];
    tabKey.value = key;
    window.location.hash = key;
}
</script>