<template>
    <div class="custom-progress">
        <div 
            class="custom-progress-bar" 
            role="progressbar" 
            aria-valuemin="0" 
            :aria-valuemax="max" 
            :aria-valuenow="value" 
            :style="widthValue" />
    </div>
</template>

<script setup>
import {computed} from 'vue';
const props = defineProps({
    value: {
        type: Number,
        required: true,
    },
    max: {
        type: Number,
        required: true,
    },
});
const widthValue = computed(() => {
    return {width: (100 * props.value) / props.max + '%'}
});
</script>

<style lang="scss" scoped>
.custom-progress {
    display: flex;
    height: 0.5rem;
    overflow: hidden;
    background-color: var(--progress-background);
    border-radius: 0.5rem;

    .custom-progress-bar {
        background-color: var(--primary);
        transition: width 0.6s ease;
    }
}
</style>