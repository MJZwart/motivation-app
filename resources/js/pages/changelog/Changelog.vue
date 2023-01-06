<template>
    <div class="w-50-flex center">
        <h1>{{ $t('changelog') }}</h1>
        <div v-for="(version, index) in versions" :key="index" class="version">
            <div class="version-header" @click="expand(version.name)">
                <h2 class="d-flex">
                    {{ version.name }}
                    <Icon class="ml-auto primary-text" :icon="isExpanded(version.name) ? ARROW_UP : ARROW_DOWN" />
                </h2>
            </div>
            <div class="version-doc" :class="{expanded: isExpanded(version.name)}">
                <div class="version-doc-inner">
                    <Component :is="version.component"  />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import v02 from './versions/0.2.md';
import v03 from './versions/0.3.md';
import v04 from './versions/0.4.md';
import v05 from './versions/0.5.md';
import {ARROW_DOWN, ARROW_UP} from '/js/constants/iconConstants';
import {Icon} from '@iconify/vue';
import {ref} from 'vue';
// import v06 from './versions/0.6.md';

const expanded = ref<string | null>('v0.5.0');

const versions = [
    {
        name: 'v0.5.0',
        component: v05,
    },
    {
        name: 'v0.4.0',
        component: v04,
    },
    {
        name: 'v0.3.0',
        component: v03,
    },
    {
        name: 'v0.2.0',
        component: v02,
    },
];

function isExpanded(versionName: string) {
    return expanded.value === versionName;
}
function expand(versionName: string) {
    if (expanded.value === versionName) expanded.value = null;
    else expanded.value = versionName;
}

</script>

<style lang="scss" scoped>
.version {
    background-color: var(--background-2);
    margin-bottom: 1rem;
    border: 1px solid var(--border-color);
    border-radius: 0.25rem;
    .version-header {
        cursor: pointer;
        background-color: var(--primary);
        color: var(--primary-text);
        padding: 0.5rem 1rem 0 1rem;
        border-bottom: 1px solid var(--border-color);
        border-radius: 0.25rem 0.25rem 0 0;
    }
    .version-doc {
        max-height: 0px;
        overflow: hidden;
        transition: max-height 0.3s ease-in-out;
    }
    .version-doc.expanded {
        max-height: 100rem;
        transition: max-height 0.3s ease-in-out;
    }
    .version-doc-inner {
        padding: 1rem;
    }
}
</style>