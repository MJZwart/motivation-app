<template>
    <div class="w-80 center row">
        <div class="tabs col-2">
            <button
                :class="activeTab('AccountSettings')" 
                class="tab-item"
                @click="switchTab('AccountSettings')">
                {{$t('account-settings')}}
            </button>
            <button 
                :class="activeTab('ProfileSettings')" 
                class="tab-item"
                @click="switchTab('ProfileSettings')">
                {{$t('profile-settings')}}
            </button>
            <button 
                :class="activeTab('RewardSettings')" 
                class="tab-item"
                @click="switchTab('RewardSettings')">
                {{$t('reward-settings')}}
            </button>
        </div>
        <keep-alive class="col-10">
            <component :is="currentTabComponent" />
        </keep-alive>
    </div>
</template>


<script setup>
import ProfileSettings from '../components/tabs/settings/ProfileSettings.vue';
import RewardSettings from '../components/tabs/settings/RewardSettings.vue';
import AccountSettings from '../components/tabs/settings/AccountSettings.vue';
import {shallowRef, ref} from 'vue';

const componentNames = {
    'AccountSettings': AccountSettings,
    'RewardSettings': RewardSettings,
    'ProfileSettings': ProfileSettings,
}
const activeComponent = ref('AccountSettings');

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