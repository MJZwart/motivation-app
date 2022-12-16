<template>
    <FaIcon :icon="getRankIcon()" />
</template>

<script setup lang="ts">
import {Rank} from 'resources/types/group';

const props = defineProps<{role: Rank}>();

function getRankIcon() {
    if (props.role.owner) return 'star';
    if (props.role.can_manage_members || props.role.can_edit) return 'angles-down';
    else return 'angle-down'
}
</script>