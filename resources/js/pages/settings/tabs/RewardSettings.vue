<template>
    <div>
        <h4>{{ $t('reward-settings') }}</h4>
        <Loading v-if="loading" />

        <div v-else>
            <h5>{{ $t('manage-villages') }}</h5>
            <div>
                <Table :items="villages" :fields="rewardFields">
                    <template #active="row">
                        {{ row.item.active ? 'Yes' : 'No' }}
                    </template>
                    <template #actions="row">
                        <Tooltip :text="$t('change-name')">
                            <Icon :icon="EDIT" class="edit-icon" @click="showEditVillage(row.item)" />
                        </Tooltip>
                        <Tooltip v-if="!row.item.active" :text="$t('activate')">
                            <Icon :icon="ACTIVATE" class="acivate-icon" @click="activateVillage(row.item)" />
                        </Tooltip>
                        <Tooltip v-if="!row.item.active" :text="$t('delete')">
                            <Icon :icon="TRASH" class="delete-icon red" @click="deleteItem(row.item)" />
                        </Tooltip>
                    </template>
                </Table>
            </div>

            <h5>{{ $t('change-reward-settings') }}</h5>
            <!-- Pick a reward type -->
            <div class="form-group">
                <label for="rewards">{{ $t('which-reward-type') }}</label>
                <div v-for="(type, index) in rewardTypes" :key="index">
                    <input
                        :id="type.label"
                        v-model="rewardSetting.rewards"
                        name="rewards"
                        type="radio"
                        :value="type.value"
                    />
                    <label :for="type.label">{{ $t(type.text) }}</label>
                </div>
                <BaseFormError name="rewards" />
                <hr />
            </div>

            <!-- If the user clicks 'Village' -->
            <div v-if="rewardSetting.rewards == 1" class="form-group">
                <label for="village-option">{{ $t('activate-or-new-village') }}</label>
                <div v-for="(option, index) in villageOptions" :key="index">
                    <input
                        :id="option.value + 'vill'"
                        v-model="rewardSetting.keepOldInstance"
                        name="village-option"
                        type="radio"
                        :value="option.value"
                    />
                    <label :for="option.value + 'vill'">{{ option.text }}</label>
                    <BaseFormError name="keepOldInstance" />
                </div>
                <hr />
            </div>

            <!-- If the user wants to create a new instance -->
            <p class="silent">{{ $t('change-name-later') }}</p>
            <div v-if="isNewInstance" class="form-group">
                <label for="username">{{ t('village-name') }}</label>
                <span class="d-flex flex-row">
                    <input
                        id="new-village-name" 
                        v-model="rewardSetting.newVillageName" 
                        type="text" 
                        name="newVillageName"
                        :placeholder="$t('village-name')" 
                        :class="{ invalid: hasError('newVillageName') }"
                    />
                    <Tooltip :text="$t('random-name')" placement="top-left" class="dice-button mr-2">
                        <Icon icon="fa-solid:dice" @click="rewardSetting.newVillageName = getRandomVillageName()" />
                    </Tooltip>
                </span>
                <BaseFormError name="newVillageName" />
            </div> 
            <button class="block" @click="confirmRewardsSettings()">{{ $t('save-settings') }}</button>
        </div>
    </div>
</template>

<script setup lang="ts">
import type {Village, ChangeReward} from 'resources/types/village';
import {onMounted, ref, computed} from 'vue';
import {REWARD_TYPES, REWARD_FIELDS} from '/js/constants/rewardConstants';
import EditRewardObjectName from '../components/EditRewardObjectName.vue';
import Table from '/js/components/global/Table.vue';
import {useI18n} from 'vue-i18n';
import {EDIT, ACTIVATE, TRASH} from '/js/constants/iconConstants';
import {formModal} from '/js/components/modal/modalService';
import {clearErrors, hasError} from '/js/services/errorService';
import {Icon} from '@iconify/vue';
import {getRandomVillageName} from '/js/helpers/randomNames';
import axios from 'axios';
import { setUser, user } from '/js/services/userService';
import { successToast } from '/js/services/toastService';

const {t} = useI18n();

onMounted(() => load());

const rewardSetting = ref<ChangeReward>({
    rewards: 0,
    keepOldInstance: null,
    newVillageName: '',
});

const rewardTypes = REWARD_TYPES;
const rewardFields = REWARD_FIELDS;
const loading = ref(true);
const villages = ref<Village[]>([]);

async function load() {
    clearErrors();
    const {data} = await axios.get('/reward/all');
    villages.value = data.villages;
    rewardSetting.value.rewards = user.value?.rewards ?? 0;
    loading.value = false;
}

const isNewInstance = computed(() => {
    if (rewardSetting.value.rewards == 0) return false;
    return rewardSetting.value.keepOldInstance == 'NEW';
});

const villageOptions = computed(() => {
    let options = [];
    if (villages.value) {
        for (const village of villages.value) {
            options.push({
                value: village.id,
                text: t('activate') + ' ' + village.name + displayActive(village),
                disabled: village.active,
            });
        }
    }
    options.push({text: t('make-new-village'), value: 'NEW'});
    return options;
});
async function confirmRewardsSettings() {
    const {data} = await axios.put('/user/settings/rewards', rewardSetting.value);
    setUser(data.data.user);

    rewardSetting.value.keepOldInstance = null;
    rewardSetting.value.newVillageName = null;
    load();
}
function showEditVillage(instance: Village) {
    if (instance === null) return;
    formModal(
        instance,
        EditRewardObjectName,
        submitEditReward,
        'edit-reward-name');
}
async function submitEditReward(rewardObj: Village) {
    await axios.put('/reward/update', rewardObj);
    load();
}
async function activateVillage(instance: Village) {
    const {data} = await axios.put('/reward/activate', instance);
    successToast(data.message);
    setUser(data.data.user);
    load();
}
function displayActive(instance: Village) {
    return instance.active ? ' (' + t('currently-active') + ')' : '';
}
async function deleteItem(instance: Village) {
    if (confirm(t('confirm-delete-instance', {name: instance.name}))) {
        await axios.put('/reward/delete', instance);
        load();
    }
}
</script>
