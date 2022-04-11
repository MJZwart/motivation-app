<template>
    <div class="w-80 center row">
        <div class="tabs col-2">
            <button
                :class="activeTab('AccountSettings')" 
                class="tab-item"
                @click="currentTabComponent = 'AccountSettings'">
                {{$t('account-settings')}}
            </button>
            <button 
                :class="activeTab('ProfileSettings')" 
                class="tab-item"
                @click="currentTabComponent = 'ProfileSettings'">
                {{$t('profile-settings')}}
            </button>
            <button 
                :class="activeTab('RewardSettings')" 
                class="tab-item"
                @click="currentTabComponent = 'RewardSettings'">
                {{$t('reward-settings')}}
            </button>
        </div>
        <keep-alive class="col-10">
            <component :is="currentTabComponent" />
        </keep-alive>
    </div>
</template>


<script>
import ProfileSettings from '../components/tabs/settings/ProfileSettings.vue';
import RewardSettings from '../components/tabs/settings/RewardSettings.vue';
import AccountSettings from '../components/tabs/settings/AccountSettings.vue';
export default {
    components: {ProfileSettings, RewardSettings, AccountSettings},
    data() {
        return {
            currentTabComponent: 'AccountSettings',
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