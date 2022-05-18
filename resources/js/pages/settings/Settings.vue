<template>
    <div class="w-60-flex center row">
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
        <keep-alive class="tab-content col-10">
            <component :is="currentTabComponent" />
        </keep-alive>
    </div>
</template>


<script setup>
import ProfileSettings from './tabs/ProfileSettings.vue';
import RewardSettings from './tabs/RewardSettings.vue';
import AccountSettings from './tabs/AccountSettings.vue';
import {shallowRef, ref, onMounted} from 'vue';

onMounted(async() => {
    if (window.location.hash)
        tabKey.value = window.location.hash.slice(1);
    else 
        tabKey.value = 'AccountSettings';
    currentTabComponent.value = tabs[tabKey.value];
});

const tabs = {
    'AccountSettings': AccountSettings,
    'RewardSettings': RewardSettings,
    'ProfileSettings': ProfileSettings,
}
const tabKey = ref('');

const currentTabComponent = shallowRef(tabs[0]);
function activeTab(key) {
    if (key == tabKey.value) return 'active-tab';
    return 'tab';
}
function switchTab(key) {
    currentTabComponent.value = tabs[key];
    tabKey.value = key;
    window.location.hash = key;
}
</script>