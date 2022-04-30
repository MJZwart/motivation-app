<template>
    <div v-if="toasts.length" class="custom-toasts-wrapper">
        <Toast 
            v-for="(toast, index) of toasts" 
            :key="index"
            :toast="toast" />
    </div>
</template>

<script setup>
import Toast from '/js/components/global/Toast.vue';
import {computed} from 'vue';

import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

const toasts = computed(() => mainStore.toasts);
</script>

<style lang="scss" scoped>
.custom-toasts-wrapper {
  width: 600px;
  min-height: 150px;
  display: flex;
  flex-direction: column;
  align-items: center;
  position: fixed;
  right: 1rem;
  bottom: 1rem;
  z-index: 1060;
}
</style>