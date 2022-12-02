<template>
    <ResponsiveTabs :tabs="tabs" />
</template>

<script setup lang="ts">
import ProfileSettings from './tabs/ProfileSettings.vue';
import RewardSettings from './tabs/RewardSettings.vue';
import AccountSettings from './tabs/AccountSettings.vue';
import ResponsiveTabs from '/js/components/global/tabs/ResponsiveTabs.vue';
const tabs = [
    {name: 'account-settings', component: AccountSettings},
    {name: 'reward-settings', component: RewardSettings},
    {name: 'profile-settings', component: ProfileSettings},
];
</script>
