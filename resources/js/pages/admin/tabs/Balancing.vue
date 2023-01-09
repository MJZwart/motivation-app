<template>
    <div>
        <div class="tabs tabs-horizontal">
            <button :class="activeTab('ExperiencePoints')" class="tab-item" @click="switchTab('ExperiencePoints')">
                {{ $t('exp-points') }}
            </button>
            <button :class="activeTab('CharExpGain')" class="tab-item" @click="switchTab('CharExpGain')">
                {{ $t('char-exp-gain') }}
            </button>
            <button :class="activeTab('VillExpGain')" class="tab-item" @click="switchTab('VillExpGain')">
                {{ $t('vill-exp-gain') }}
            </button>
        </div>
        <keep-alive>
            <component :is="currentTabComponent" />
        </keep-alive>
    </div>
</template>

<script setup lang="ts">
import {ref, shallowRef} from 'vue';
import ExperiencePoints from './../components/ExperiencePointsTab.vue';
import CharExpGain from './../components/CharExpGainTab.vue';
import VillExpGain from './../components/VillExpGainTab.vue';

const componentNames = {
    ExperiencePoints: ExperiencePoints,
    CharExpGain: CharExpGain,
    VillExpGain: VillExpGain,
};
const activeComponent = ref('ExperiencePoints');

const currentTabComponent = shallowRef(componentNames[activeComponent.value]);
function activeTab(key: string) {
    if (key == activeComponent.value) return 'active-tab';
    return 'tab';
}
function switchTab(key: string) {
    currentTabComponent.value = componentNames[key];
    activeComponent.value = key;
}
</script>

<style lang="scss">
.points-col {
    width: 10rem;
    input {
        width: 8rem !important;
    }
}
table.balancing-table .data-cell {
    height: 2.5rem;
    padding: 0.48rem;
    padding-left: 0.81rem;
}
table.balancing-table td {
    .form-control {
        width: 3rem;
    }
}
</style>
