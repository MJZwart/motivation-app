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
@import '../../../assets/scss/variables.scss';
.custom-progress {
    display: flex;
    height: 0.5rem;
    overflow: hidden;
    background-color: $progress-background;
    border-radius: 0.25rem;

    .custom-progress-bar {
        background-color: $primary;
        transition: width 0.6s ease;
    }
}
</style>