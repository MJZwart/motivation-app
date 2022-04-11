<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="row">
            <div class="tabs col-2">
                <button 
                    :class="activeTab('Achievements')" 
                    class="tab-item"
                    @click="currentTabComponent = 'Achievements'">
                    {{$t('achievements')}}
                </button>
                <button 
                    :class="activeTab('BugReportPanel')" 
                    class="tab-item"
                    @click="currentTabComponent = 'BugReportPanel'">
                    {{$t('bug-reports')}}
                </button>
                <button 
                    :class="activeTab('SendNotifications')" 
                    class="tab-item"
                    @click="currentTabComponent = 'SendNotifications'">
                    {{$t('send-notification')}}
                </button>
                <button 
                    :class="activeTab('Balancing')" 
                    class="tab-item"
                    @click="currentTabComponent = 'Balancing'">
                    {{$t('balancing')}}
                </button>
                <button 
                    :class="activeTab('ReportedUsers')" 
                    class="tab-item"
                    @click="currentTabComponent = 'ReportedUsers'">
                    {{$t('reported-users')}}
                </button>
            </div>
            <keep-alive class="col-10">
                <component :is="currentTabComponent" />
            </keep-alive>
        </div>
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
            currentTabComponent: 'Achievements',
        }
    },
    methods: {
        activeTab(component) {
            if (component == this.currentTabComponent) return 'active-tab';
            return 'tab';
        },
    },
}
</script>