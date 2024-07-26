<template>
    <div>
        <h3>{{ $t('maintenance-banners') }}</h3>

        <h5>{{ $t('add-new-maintenance-banner') }}</h5>

        <EditMaintenanceBanner :form="getEmptyMaintenanceBanner()" @submit="createNewMaintenanceBanner" />

        <div class="mb-3 mt-1">
            <SimpleCheckbox v-model="hideExpiredBanners" />{{ $t('hide-expired-banners') }}
        </div>
        
        <Table 
            :items="filteredMaintenanceBanners" 
            :fields="MAINTENANCE_BANNER_FIELDS" 
            :options="['table-striped', 'page-wide']" 
            class="font-sm" 
            sort="created_at"
            :sortAsc="false"
        >
            <template #message="row">
                <span :class="{ expired: isExpired(row.item) }">
                    {{ row.item.message }}
                </span>
            </template>
            <template #starts_at="row">
                <span :class="{ expired: isExpired(row.item) }">
                    {{ parseDateTime(row.item.starts_at, true, true) }}
                </span>
            </template>
            <template #ends_at="row">
                <span :class="{ expired: isExpired(row.item) }">
                    {{ parseDateTime(row.item.ends_at, true, true) }}
                </span>
            </template>
            <template #actions="row">
                <Icon :icon="EDIT" @click="editBanner(row.item)" />
                <Icon :icon="TRASH" class="red" @click="deleteBanner(row.item)" />
            </template>

            <template #empty>
                {{ $t('no-maintenance-banners') }}
            </template>
        </Table>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useAdminStore } from '/js/store/adminStore';
import { ref } from 'vue';
import { MaintenanceBannerMessage, NewMaintenanceBannerMessage } from 'resources/types/admin';
import Table from '/js/components/global/Table.vue';
import { EDIT, TRASH } from '/js/constants/iconConstants';
import { confirmModal, formModal } from '/js/components/modal/modalService';
import EditMaintenanceBanner from '../components/EditMaintenanceBanner.vue';
import SimpleCheckbox from '/js/components/global/small/SimpleCheckbox.vue';
import { computed } from 'vue';
import { parseDateTime } from '/js/services/dateService';
import {useI18n} from 'vue-i18n';
const {t} = useI18n();

const adminStore = useAdminStore();

const maintenanceBanners = ref<MaintenanceBannerMessage[]>([]);

const MAINTENANCE_BANNER_FIELDS = [
    {
        label: 'message',
        key: 'message',
    },
    {
        label: 'starts-at',
        key: 'starts_at',
    },
    {
        label: 'ends-at',
        key: 'ends_at',
    },
    {
        label: '',
        key: 'actions',
    },
    
];

const hideExpiredBanners = ref(false);

onMounted(async() => {
    maintenanceBanners.value = await adminStore.getAllMaintenanceBanners();
});

const filteredMaintenanceBanners = computed(() => {
    if (!hideExpiredBanners.value) return maintenanceBanners.value;
    return maintenanceBanners.value.filter(item => new Date(item.ends_at) > new Date())
});

const getEmptyMaintenanceBanner = (): NewMaintenanceBannerMessage => {
    return {
        message: '',
        starts_at: new Date().toDateString(),
        ends_at: new Date().toDateString(),
    }
}

const createNewMaintenanceBanner = async (newBanner: NewMaintenanceBannerMessage) => {
    maintenanceBanners.value = await adminStore.submitNewMaintenanceBanner(newBanner);
}

const editMaintenanceBannerMessage = async(banner: MaintenanceBannerMessage) => {
    maintenanceBanners.value = await adminStore.editMaintenanceBanner(banner);
}

const editBanner = (banner: MaintenanceBannerMessage) => {
    formModal(banner, EditMaintenanceBanner, editMaintenanceBannerMessage, 'add-new-maintenance-banner');
}

const deleteBanner = (banner: MaintenanceBannerMessage) => {
    confirmModal(t('confirm-delete-banner'), 
                 async () => maintenanceBanners.value = await adminStore.deleteMaintenanceBanner(banner.id));
}

const isExpired = (banner: MaintenanceBannerMessage) => {
    return new Date(banner.ends_at) < new Date();
}
</script>

<style lang="scss" scoped>
.expired {
    color: var(--body-text-grey);
    text-decoration: line-through;
}
</style>