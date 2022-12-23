<template>
    <Icon :icon="getRankIcon()" class="rank-icon iconify non-clickable" />
</template>

<script setup lang="ts">
import {Icon} from '@iconify/vue';
import {Rank} from 'resources/types/group';

const props = defineProps<{rank: Rank}>();

function getRankIcon() {
    if (!props.rank) return '';
    if (props.rank.owner) return 'game-icons:rank-3';
    if (props.rank.member) return 'game-icons:rank-1';
    return 'game-icons:rank-2';
}
</script>