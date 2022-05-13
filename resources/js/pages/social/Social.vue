<template>
    <div class="row">
        <div class="tabs col-2">
            <button 
                :class="activeTab('Groups')" 
                class="tab-item"
                @click="switchTab('Groups')">
                {{ $t('groups') }}
            </button>
            <button 
                :class="activeTab('Friends')" 
                class="tab-item"
                @click="switchTab('Friends')">
                {{ $t('friends') }}
            </button>
            <button 
                :class="activeTab('Blocklist')" 
                class="tab-item"
                @click="switchTab('Blocklist')">
                {{ $t('blocklist') }}
            </button>
        </div>
        <KeepAlive class="tab-content col-10">
            <component :is="currentTabComponent" :key="tabKey" />
        </KeepAlive>
    </div>
</template>

<script setup>
import Groups from './tabs/Groups.vue';
import Friends from './tabs/Friends.vue';
import Blocklist from './tabs/Blocklist.vue';
import {shallowRef, ref, onMounted} from 'vue';

onMounted(async() => {
    if (window.location.hash)
        tabKey.value = window.location.hash.slice(1);
    else 
        tabKey.value = 'Groups';
    currentTabComponent.value = tabs[tabKey.value];
});

const tabs = {
    'Groups': Groups,
    'Friends': Friends,
    'Blocklist': Blocklist,
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
