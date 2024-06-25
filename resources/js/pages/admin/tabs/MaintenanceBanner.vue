<template>
    <div>
        <h3>{{ $t('maintenance-banners') }}</h3>

        <div>
            <h5>{{ $t('add-new-maintenance-banner') }}</h5>
            
            <div class="form-group">
                {{$t('message')}}
                <textarea v-model="newMaintenanceBanner.message" />
            </div>

            <div class="form-group flex-row w-100 gap-2">
                <div class="w-50">
                    {{$t('starts-date')}}
                    <Datepicker 
                        v-model="newMaintenanceBanner.starts_at" 
                        autoApply :locale="currentLang"
                    />
                </div>
                <div class="w-50">
                    {{$t('end-date')}}
                    <Datepicker 
                        v-model="newMaintenanceBanner.ends_at" 
                        autoApply :locale="currentLang"
                    />
                </div>
            </div>
            <SubmitButton @click="createNewMaintenanceBanner" />
        </div>
        
        <!-- TODO Does sort work like this? -->
        <Table 
            :items="maintenanceBanners" 
            :fields="MAINTENANCE_BANNER_FIELDS" 
            :options="['table-striped', 'page-wide']" 
            class="font-sm" 
            sort="created_at"
            :sortAsc="false"
        >
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
import { currentLang } from '/js/services/languageService';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';

const adminStore = useAdminStore();

const maintenanceBanners = ref<MaintenanceBannerMessage[]>([]);

const MAINTENANCE_BANNER_FIELDS = [
    {
        label: 'message',
        key: 'message',
    },
    {
        label: 'starts_at',
        key: 'starts_at',
    },
    {
        label: 'ends_at',
        key: 'ends_at',
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

const newMaintenanceBanner = ref<NewMaintenanceBannerMessage>(getEmptyMaintenanceBanner());

const createNewMaintenanceBanner = async () => {
    maintenanceBanners.value = await adminStore.submitNewMaintenanceBanner(newMaintenanceBanner.value);
}
</script>