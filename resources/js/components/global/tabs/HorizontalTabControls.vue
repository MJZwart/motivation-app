<template>
    <div class="tabs tabs-horizontal d-flex">
        <template v-for="(tab, index) in tabs"
                  :key="index">
            <button
                v-if="tab.key"
                type="button"
                class="tab-item m-1"
                :class="{'active-tab': activeTab == tab.key}"
                @click="selectTab(tab.key)"
            >
                {{ $t(tab.key) }}
            </button>
        </template>
        
    </div>
</template>

<script setup lang="ts">
import {ref} from 'vue';

export type TabItem = {
    key: string;
}

const props = defineProps<{tabs: TabItem[], modelValue: string}>();
const emit = defineEmits(['update:modelValue']);

const activeTab = ref(props.tabs[0].key);
function selectTab(tabKey: string) {
    activeTab.value = tabKey;
    emit('update:modelValue', tabKey);
}
</script>