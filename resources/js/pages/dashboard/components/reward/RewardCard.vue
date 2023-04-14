<template>
    <div v-if="reward">
        <div class="content-block p-3">
            <div class="d-flex">
                <h3>{{ reward.name }}</h3>
                <span class="ml-auto"><Tutorial v-if="tutorial" class="ml-auto" :tutorial="rewardType" /></span>
            </div>
            <div class="compact">
                <p>{{ $t('level') }}: {{reward.level}}</p>
                <p>{{ $t('coins') }}: <Coins :coins=reward.coins /></p>
                <p>{{ $t('experience') }}: {{reward.experience}}
                    <ProgressBar class="level-bar" :value="reward.experience" :max="reward.level_exp_needed" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('economy') : $t('strength') }}: {{reward.a}}
                    <ProgressBar :value="reward.a_exp" :max="reward.a_exp_needed" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('labour') : $t('endurance') }}: {{reward.b}}
                    <ProgressBar :value="reward.b_exp" :max="reward.b_exp_needed" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('craft') : $t('agility') }}: {{reward.c}}
                    <ProgressBar :value="reward.c_exp" :max="reward.c_exp_needed" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('art') : $t('intelligence') }}: {{reward.d}}
                    <ProgressBar :value="reward.d_exp" :max="reward.d_exp_needed" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('community') : $t('charisma') }}: {{reward.e}}
                    <ProgressBar :value="reward.e_exp" :max="reward.e_exp_needed" />
                </p>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
import ProgressBar from '/js/components/global/ProgressBar.vue';
import Coins from './Coins.vue';
import {PropType} from 'vue';
import {Reward} from 'resources/types/reward';

defineProps({
    reward: {
        type: Object as PropType<Reward>,
        required: true,
    },
    rewardType: {
        type: String,
        required: true,
    },
    tutorial: {
        type: Boolean,
        required: false,
        default: true,
    },
});
</script>

<style lang="scss">
.level-bar{
    height:0.7rem !important;
    margin-bottom:3px !important;
}
.compact > p {
    margin-bottom: 0.5rem;
}
</style>