<template>
    <div class="maintenance-banner" v-if="visibileBanners.length">
        <div class="message flex" v-for="banner in visibileBanners">
            <span class="mt-auto mb-auto">{{ banner.message }}</span>
            <button class="ml-auto clear" @click="dismissBanner(banner.id)">×</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import {visibileBanners, dismissBanner, fetchBanners} from './maintenanceBannerLogic';

onMounted(async() => {
    fetchBanners();
});
</script>

<style lang="scss" scoped>
.maintenance-banner {
    border-top: 0;
    background-color: #FFA07A;
    border-radius: 0 0 0.5rem 0.5rem;
    padding: 0.2rem 0.75rem;
    color: black;

    button {
        color: black;
        font-size: x-large;
        margin-bottom: -2px;
        margin-top: -4px;
    }
}
</style>