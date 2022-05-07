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
            <component :is="currentTabComponent" :key="activeComponent" />
        </KeepAlive>
    </div>
</template>

<script setup>
import Groups from './tabs/Groups.vue';
import Friends from './tabs/Friends.vue';
import Blocklist from './tabs/Blocklist.vue';
import {shallowRef, ref} from 'vue';

const componentNames = {
    'Groups': Groups,
    'Friends': Friends,
    'Blocklist': Blocklist,
}
const activeComponent = ref('Groups');

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
