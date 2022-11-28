<template>
    <div class="d-flex flex-wrap">
        <div :class="topLevelClass">
            <div :class="responsiveClass">
                <template v-for="(tab, index) in tabs" :key="index">
                    <button :class="isActive(tab.name)" class="tab-item" @click="selectTab(tab)">
                        {{ $t(tab.name) }}
                    </button>
                </template>
            </div>
        </div>
        
        <KeepAlive class="tab-content col-10">
            <component :is="currentTab.component" :key="currentTab.name" />
        </KeepAlive>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, PropType, ref, shallowRef} from 'vue';
import type {Component} from 'vue';

export type Tab = {
    name: string,
    component: Component,
}

const props = defineProps({
    tabs: {
        type: Array as PropType<Tab[]>,
        required: true,
    },
});

onMounted(async() => {
    if (window.location.hash) currentTab.value.name = window.location.hash.slice(1);
    window.addEventListener('resize', handleResize);
});
const currentTab = shallowRef(props.tabs[0]);

function isActive(tabName: string) {
    return tabName === currentTab.value.name ? 'active-tab':'tab';
}
function selectTab(tab: Tab) {
    currentTab.value = tab;
    window.location.hash = tab.name;
}

/**
 * Automatic resizing
 */
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