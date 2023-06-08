<template>
    <div v-if="settings.length">
        <h3>{{ $t('settings') }}</h3>
        <table>
            <thead>
                <th>{{$t('setting-key-heading')}}</th>
                <th>{{$t('setting-value-heading')}}</th>
            </thead>
            <tbody>
                <tr v-for="(setting, index) in settings" :key="index">
                    <td>{{ $t(setting.key) }}</td>
                    <td><input v-model="setting.value" class="setting-value" /></td>
                </tr>
            </tbody>
        </table>
        <SubmitButton @click="saveSettings" />
    </div>
</template>

<script setup lang="ts">
import type {GlobalSetting} from 'resources/types/admin';
import {onMounted, ref} from 'vue';
import {useAdminStore} from '/js/store/adminStore';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';

const adminStore = useAdminStore();

const settings = ref<GlobalSetting[]>([]);

onMounted(async() => {
    settings.value = await adminStore.getGlobalSettings();
});

async function saveSettings() {
    settings.value = await adminStore.saveGlobalSettings(settings.value);
}
</script>

<style lang="scss" scoped>
.setting-key {
    display: inline-block;
    margin-right: 1rem;
    width: 15rem;
}
.setting-value, .setting-value-header {
    display: inline-block;
}
.setting-key-header {
    display: inline-block;
    width: 15rem;
}
</style>