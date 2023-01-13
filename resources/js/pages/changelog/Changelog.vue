<template>
    <div class="w-50-flex center">
        <h1>{{ $t('changelog') }}</h1>
        <div v-for="(version, index) in sortedVersions" :key="index" class="version">
            <div class="version-header" @click="expand(version.name)">
                <h2 class="d-flex">
                    v{{ version.name }}
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
import {ARROW_DOWN, ARROW_UP} from '/js/constants/iconConstants';
import {Icon} from '@iconify/vue';
import {computed, ref} from 'vue';
import {sortValues} from '/js/services/sortService';

const versions = import.meta.glob('./versions/*.*.md', {eager: true});

const converted = computed(() => {
    // Disabled because typing here is unnecessary
    // eslint-disable-next-line @typescript-eslint/no-explicit-any 
    return Object.entries(versions).map((version: Record<string, any>) => ({
        name: version[1].default.__name,
        component: version[1].default,
    }));
});
const sortedVersions = computed(() => {
    return sortValues(converted.value, 'name', 'desc');
})

const expanded = ref<string | null>(sortedVersions.value[0].name);

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