<template>
    <div>
        <h3>{{ $t('maintenance-banners') }}</h3>

        <h5>{{ $t('add-new-maintenance-banner') }}</h5>

        <EditMaintenanceBanner :form="getEmptyMaintenanceBanner()" @submit="createNewMaintenanceBanner" />
        
        <Table 
            :items="maintenanceBanners" 
            :fields="MAINTENANCE_BANNER_FIELDS" 
            :options="['table-striped', 'page-wide']" 
            class="font-sm" 
            sort="created_at"
            :sortAsc="false"
        >
            <template #actions="row">
                <Icon :icon="EDIT" @click="editBanner(row.item)" />
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
import { EDIT } from '/js/constants/iconConstants';
import { formModal } from '/js/components/modal/modalService';
import EditMaintenanceBanner from '../components/EditMaintenanceBanner.vue';

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

onMounted(async() => {
    maintenanceBanners.value = await adminStore.getAllMaintenanceBanners();
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
</script>