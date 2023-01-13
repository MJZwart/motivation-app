<template>
    <Icon :icon="getIcon" class="icon non-clickable" />
</template>

<script setup lang="ts">
import {computed} from 'vue';
import {ARROW_UP, ACHIEVEMENT, GROUP, GAME} from '/js/constants/iconConstants';

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
        default:
            return '';
    }
});
</script>