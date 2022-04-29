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
        </div>
        <KeepAlive class="col-10">
            <component :is="currentTabComponent" :key="activeComponent.value" />
        </KeepAlive>
    </div>
</template>

<script setup>
import Groups from '../components/tabs/social/Groups.vue';
import Friends from '../components/tabs/social/Friends.vue';
import {shallowRef, ref} from 'vue';

const componentNames = {
    'Groups': Groups,
    'Friends': Friends,
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
