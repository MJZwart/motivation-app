<template>
    <div :class="topLevelClass">
        <div :class="responsiveClass">
            <slot />    
        </div>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';

onMounted(() => window.addEventListener('resize', handleResize));
const windowWidth = ref(window.innerWidth);

function handleResize() {
    windowWidth.value = window.innerWidth;
}

const responsiveClass = computed(() => windowWidth.value < 450 ? 'tabs tabs-horizontal responsive' : 'tabs');
const topLevelClass = computed(() => windowWidth.value < 450 ? 'scroll-x' : 'col-2');
</script>

<style lang="scss">
.scroll-x {
    overflow-y: hidden;
    overflow-x: auto;
}
.tabs-horizontal.responsive {
    width: 89vw;
    margin-bottom: 0.5rem;
    .tab-item {
        margin-left: 5px;
    }
}
</style>