<template>
    <div>        
        <div class="form-group">
            {{$t('message')}}
            <textarea v-model="newMaintenanceBanner.message" />
        </div>

        <div class="form-group flex-row w-100 gap-2">
            <div class="w-50">
                {{$t('start-date')}}
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
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { NewMaintenanceBannerMessage } from 'resources/types/admin';
import { currentLang } from '/js/services/languageService';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import { deepCopy } from '/js/helpers/copy';

const props = defineProps<{form: NewMaintenanceBannerMessage}>();
const emit = defineEmits(['submit']);

const newMaintenanceBanner = ref<NewMaintenanceBannerMessage>(deepCopy(props.form));

const createNewMaintenanceBanner = async () => {
    emit('submit', newMaintenanceBanner.value);
}
</script>