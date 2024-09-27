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
                    <td>
                        <input v-model="setting.value" class="setting-value" :class="{invalid: hasError(`${index}.value`)}" />
                        <BaseFormError :name="index + '.value'" />
                    </td>
                </tr>
            </tbody>
        </table>
        <SubmitButton @click="saveSettings" />
    </div>
</template>

<script setup lang="ts">
import type {GlobalSetting} from 'resources/types/admin';
import {onMounted, ref} from 'vue';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {hasError} from '/js/services/errorService';
import axios from 'axios';

const settings = ref<GlobalSetting[]>([]);

onMounted(async() => {
    const {data} = await axios.get('/admin/settings');
    settings.value = data.data;
});

async function saveSettings() {
    const {data} = await axios.post('/admin/settings', settings.value);
    settings.value = data.data;
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