<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="row">
            <div class="tabs col-2">
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
            </div>
            <KeepAlive class="col-10">
                <component :is="currentTabComponent" :key="activeComponent" />
            </KeepAlive>
        </div>
    </div>
</template>


<script setup>
import Achievements from './tabs/Achievements.vue';
import BugReportPanel from './tabs/BugReportPanel.vue';
import SendNotifications from './tabs/SendNotifications.vue';
import Balancing from './tabs/Balancing.vue';
import ReportedUsers from './tabs/ReportedUsers.vue';
import {shallowRef, onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';

const loading = ref(true);
const adminStore = useAdminStore();

onMounted(async() => {
    await adminStore.getAdminDashboard();
    loading.value = false;
});

const componentNames = {
    'Achievements': Achievements,
    'BugReportPanel': BugReportPanel,
    'SendNotifications': SendNotifications,
    'Balancing': Balancing,
    'ReportedUsers': ReportedUsers,
}
const activeComponent = ref('Achievements');

const currentTabComponent = shallowRef(componentNames[activeComponent.value]);
function activeTab(component) {
    if (component == activeComponent.value) return 'active-tab';
    return 'tab';
}
function switchTab(component) {
    currentTabComponent.value = componentNames[component];
    activeComponent.value = component;
}
</script>