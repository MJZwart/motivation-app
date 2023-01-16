<template>
    <Icon :icon="getIcon" class="timeline-icon icon non-clickable" />
</template>

<script setup lang="ts">
import {computed} from 'vue';
import {ARROW_UP, ACHIEVEMENT, GROUP, GAME, POWER} from '/js/constants/iconConstants';

const props = defineProps<{type: string}>();

// eslint-disable-next-line complexity
const getIcon = computed(() => {
    switch (props.type) {
        case 'ACHIEVEMENT':
            return ACHIEVEMENT;
        case 'LEVEL':
            return ARROW_UP;
        case 'GROUP':
            return GROUP;
        case 'CHARACTER':
        case 'VILLAGE':
            return GAME;
        case 'JOIN':
            return POWER;
        default:
            return '';
    }
});
</script>

<style lang="scss" scoped>
.timeline-icon {
    font-size: 120%;
}
</style>