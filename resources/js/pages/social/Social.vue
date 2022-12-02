<template>
    <ResponsiveTabs :tabs="tabs" />
</template>

<script setup lang="ts">
import Groups from './tabs/Groups.vue';
import Friends from './tabs/Friends.vue';
import Blocklist from './tabs/Blocklist.vue';
import ResponsiveTabs from '/js/components/global/tabs/ResponsiveTabs.vue';

const tabs = [
    {name: 'groups', component: Groups},
    {name: 'friends', component: Friends},
    {name: 'blocklist', component: Blocklist},
];
</script>
