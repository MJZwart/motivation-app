<template>
    <div>
        <div class="tabs tabs-horizontal">
            <button
                :class="activeTab('ExperiencePoints')" 
                class="tab-item"
                @click="currentTabComponent = 'ExperiencePoints'">
                {{$t('exp-points')}}
            </button>
            <button 
                :class="activeTab('CharExpGain')" 
                class="tab-item"
                @click="currentTabComponent = 'CharExpGain'">
                {{$t('char-exp-gain')}}
            </button>
            <button 
                :class="activeTab('VillExpGain')" 
                class="tab-item"
                @click="currentTabComponent = 'VillExpGain'">
                {{$t('vill-exp-gain')}}
            </button>
        </div>
        <keep-alive>
            <component :is="currentTabComponent" />
        </keep-alive>
    </div>
</template>

<script>
import GeneralFormError from '../../GeneralFormError.vue';
import BaseFormError from '../../BaseFormError.vue';
import ExperiencePoints from './components/ExperiencePointsTab.vue';
import CharExpGain from './components/CharExpGainTab.vue';
import VillExpGain from './components/VillExpGainTab.vue';

export default {
    components: {GeneralFormError, BaseFormError, ExperiencePoints, CharExpGain, VillExpGain},
    data() {
        return {
            newLevel: {},
            currentTabComponent: 'ExperiencePoints',
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

<style lang="scss">
.points-col {
    width:10rem;
    input {
        width:8rem !important;
    }
}
table.balancing-table .data-cell {
    height:2.5rem;
    padding:0.48rem;
    padding-left: 0.81rem;
}
table.balancing-table td{
    .form-control {
        width:3rem;
    }
}
</style>