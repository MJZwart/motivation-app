<template>
    <div>
        <h4>{{ $t('reward-settings') }}</h4>
        <Loading v-if="loading" />

        <div v-else>
            <h5>{{ $t('manage-rewards') }}</h5>
            <div>
                <Table :items="rewardItems" :fields="rewardFields">
                    <template #rewardType="row">
                        {{ capitalizeOnlyFirst(row.item.rewardType) }}
                    </template>
                    <template #active="row">
                        {{ row.item.active ? 'Yes' : 'No' }}
                    </template>
                    <template #actions="row">
                        <Tooltip :text="$t('change-name')">
                            <Icon :icon="EDIT" class="edit-icon" @click="showEditReward(row.item)" />
                        </Tooltip>
                        <Tooltip v-if="!row.item.active" :text="$t('activate')">
                            <Icon :icon="ACTIVATE" class="acivate-icon" @click="activateReward(row.item)" />
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
                        :id="type.value"
                        v-model="rewardSetting.rewards"
                        name="rewards"
                        type="radio"
                        :value="type.value"
                    />
                    <label :for="type.value">{{ $t(type.text) }}</label>
                </div>
                <BaseFormError name="rewards" />
                <hr />
            </div>

            <!-- If the user clicks 'Character' -->
            <div v-if="rewardSetting.rewards == 'CHARACTER'" class="form-group">
                <label for="character-option">{{ $t('activate-or-new') }}</label>
                <div v-for="(option, index) in characterOptions" :key="index">
                    <input
                        :id="option.value + 'char'"
                        v-model="rewardSetting.keepOldInstance"
                        name="character-option"
                        type="radio"
                        :value="option.value"
                    />
                    <label :for="option.value + 'char'">{{ option.text }}</label>
                    <BaseFormError name="keepOldInstance" />
                </div>
                <hr />
            </div>

            <!-- Or if the user clicks 'Village' -->
            <div v-if="rewardSetting.rewards == 'VILLAGE'" class="form-group">
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
            <SimpleInput
                v-if="isNewInstance"
                id="new-object-name"
                v-model="rewardSetting.new_object_name"
                type="text"
                name="new_object_name"
                :label="rewardTypeName"
                :placeholder="rewardTypeName"
            />
            <button class="block" @click="confirmRewardsSettings()">{{ $t('save-settings') }}</button>
        </div>

        <Modal :show="showEditRewardNameModal" :title="$t('edit-reward-name')" @close="closeEditReward">
            <EditRewardObjectName
                v-if="rewardToEdit"
                :rewardObj="rewardToEdit"
                :type="rewardToEdit.rewardType"
                @close="closeEditReward"
            />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {onMounted, ref, computed} from 'vue';
import {REWARD_TYPES, REWARD_FIELDS} from '/js/constants/rewardConstants';
import EditRewardObjectName from '../components/EditRewardObjectName.vue';
import Table from '/js/components/global/Table.vue';
import {useUserStore} from '/js/store/userStore';
import {useRewardStore} from '/js/store/rewardStore';
import {useMainStore} from '/js/store/store';
import {useI18n} from 'vue-i18n';
import {capitalizeOnlyFirst} from '/js/services/stringService';
import type {Reward, ChangeReward} from 'resources/types/reward';
import {EDIT, ACTIVATE, TRASH} from '/js/constants/iconConstants';

const userStore = useUserStore();
const rewardStore = useRewardStore();
const mainStore = useMainStore();
const {t} = useI18n();

onMounted(() => load());

const rewardSetting = ref<ChangeReward>({
    rewards: 'NONE',
    keepOldInstance: null,
});

const rewardTypes = REWARD_TYPES;
const rewardFields = REWARD_FIELDS;
const loading = ref(true);
const rewardToEdit = ref<Reward | null>(null);
const showEditRewardNameModal = ref(false);
const user = computed(() => userStore.user);
const characters = computed(() => rewardStore.characters);
const villages = computed(() => rewardStore.villages);

const rewardTypeName = computed(() =>
    rewardSetting.value.rewards == 'VILLAGE' ? t('village-name') : t('character-name'),
);
const isNewInstance = computed(() => {
    if (rewardSetting.value.rewards == 'NONE') return false;
    return rewardSetting.value.keepOldInstance == 'NEW';
});

// TODO This may be combined a bit
const characterOptions = computed(() => {
    let options = [];
    if (characters.value) {
        for (const character of characters.value) {
            options.push({
                value: character.id,
                text: t('activate') + ' ' + character.name + displayActive(character),
                disabled: character.active,
            });
        }
    }
    options.push({text: t('make-new-character'), value: 'NEW'});
    return options;
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
const rewardItems = computed(() => {
    let rewardItems = [] as Reward[];
    if (characters.value) rewardItems = rewardItems.concat(characters.value);
    if (villages.value) rewardItems = rewardItems.concat(villages.value);
    return rewardItems;
});
async function confirmRewardsSettings() {
    await userStore.changeRewardType(rewardSetting.value);
    rewardSetting.value.keepOldInstance = null;
    rewardSetting.value.new_object_name = null;
    load();
}
function showEditReward(instance: Reward) {
    if (instance === null) return;
    mainStore.clearErrors();
    rewardToEdit.value = instance;
    rewardToEdit.value.rewardType = instance.rewardType.toUpperCase();
    showEditRewardNameModal.value = true;
}
function closeEditReward() {
    showEditRewardNameModal.value = false;
    load();
}
async function activateReward(instance: Reward) {
    await rewardStore.activateInstance(instance);
    load();
}
function displayActive(instance: Reward) {
    return instance.active ? ' (' + t('currently-active') + ')' : '';
}
async function deleteItem(instance: Reward) {
    if (confirm(t('confirm-delete-instance', {name: instance.name, type: instance.rewardType.toLowerCase()}))) {
        await rewardStore.deleteInstance(instance);
        load();
    }
}
async function load() {
    mainStore.clearErrors();
    await rewardStore.fetchAllRewardInstances();
    rewardSetting.value.rewards = user.value?.rewards ?? '';
    loading.value = false;
}
</script>
