<template>
    <div v-if="village">
        <ContentBlock>
            <template #header>
                <div class="d-flex">
                    <h3>{{ village.name }}</h3>
                    <span class="ml-auto"><Tutorial v-if="tutorial" tutorial="village" /></span>
                </div>
            </template>
            <div class="compact">
                <p>{{ $t('level') }}: {{village.level}}</p>
                <p>{{ $t('coins') }}: <Coins :coins=village.coins /></p>
                <p>{{ $t('experience') }}: {{village.experience}}
                    <ProgressBar class="level-bar" :value="village.experience" :max="village.level_exp_needed" />
                </p>
                <p>{{ $t('economy') }}: {{village.economy}}
                    <ProgressBar :value="village.economy_exp" :max="village.economy_exp_needed" />
                </p>
                <p>{{ $t('labour') }}: {{village.labour}}
                    <ProgressBar :value="village.labour_exp" :max="village.labour_exp_needed" />
                </p>
                <p>{{ $t('craft') }}: {{village.craft}}
                    <ProgressBar :value="village.craft_exp" :max="village.craft_exp_needed" />
                </p>
                <p>{{ $t('art') }}: {{village.art}}
                    <ProgressBar :value="village.art_exp" :max="village.art_exp_needed" />
                </p>
                <p>{{ $t('community') }}: {{village.community}}
                    <ProgressBar :value="village.community_exp" :max="village.community_exp_needed" />
                </p>
            </div>
        </ContentBlock>
    </div>
</template>


<script setup lang="ts">
import type {Village} from 'resources/types/village';
import ProgressBar from '/js/components/global/ProgressBar.vue';
import Coins from './Coins.vue';
import {PropType} from 'vue';

defineProps({
    village: {
        type: Object as PropType<Village>,
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