<template>
    <div class="maintenance-banner" v-if="banners.length">
        <div class="message flex" v-for="banner in banners">
            <span class="mt-auto mb-auto">{{ banner.message }}</span>
            <button class="ml-auto clear" @click="dismissBanner(banner.id)">×</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import {useMainStore} from '/js/store/store';
import { ref } from 'vue';
import { MaintenanceBannerMessage } from 'resources/types/admin';
import { computed } from 'vue';
import { useUserStore } from '../store/userStore';

const mainStore = useMainStore();
const userStore = useUserStore();
const user = userStore.user;
const banners = ref<MaintenanceBannerMessage[]>([]);

onMounted(async() => {
    if (!user) return;
    banners.value = await mainStore.getCurrentMaintenanceBanners();
    const dismissedBannersJSON = localStorage.getItem('dismissedBanners' + user.id);
    if (dismissedBannersJSON) banners.value = banners.value.filter(item => !JSON.parse(dismissedBannersJSON).includes(item.id));
});

const dismissBanner = (id: number) => {
    if (!user) return;
    const dismissedBannersJSON = localStorage.getItem('dismissedBanners' + user.id);
    let dismissedBannersArr: number[] = [];
    if (dismissedBannersJSON) dismissedBannersArr = JSON.parse(dismissedBannersJSON);
    dismissedBannersArr.push(id);
    localStorage.setItem('dismissedBanners' + user.id, JSON.stringify(dismissedBannersArr));
    banners.value = banners.value.filter(item => !dismissedBannersArr.includes(item.id));
}
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