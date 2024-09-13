import axios from "axios";
import { MaintenanceBannerMessage } from "resources/types/admin";
import { computed, ref } from "vue";

const userId = ref<number | null>(null);
// TODO Make this computed

export const banners = ref<MaintenanceBannerMessage[]>();
export const dismissedBanners = ref<number[]>([]);

export const visibileBanners = computed(() => {
    if (!banners.value) return [];
    return banners.value.filter((item: MaintenanceBannerMessage )=> !dismissedBanners.value.includes(item.id));
});

export const fetchBanners = async() => {
    const {data} = await axios.get('/maintenance-banner');
    banners.value = data;
}

export const setUserId = (id: number | null) => {
    userId.value = id;
}

export const dismissBanner = (id: number) => {
    fetchDismissedBanners();
    dismissedBanners.value.push(id)
    localStorage.setItem('dismissedBanners' + (userId.value || ''), JSON.stringify(dismissedBanners.value));
}

export const fetchDismissedBanners = () => {
    const dismissedBannersJSON = localStorage.getItem('dismissedBanners' + (userId.value || ''));
    dismissedBanners.value = dismissedBannersJSON ? JSON.parse(dismissedBannersJSON) : [];
}

fetchDismissedBanners();