<template>
    <div>
        <h4>{{ $t('reward-settings') }}</h4>
        <Loading v-if="loading" />

        <div v-else>
            <h5>{{ $t('manage-rewards') }}</h5>
            <div>
                <Table
                    :items="rewardItems"
                    :fields="rewardFields">
                    <template #active="item">
                        {{ item.item.active ? 'Yes' : 'No' }}
                    </template>
                    <template #actions="item">
                        <Tooltip :text="$t('change-name')">
                            <FaIcon 
                                :icon="['far', 'pen-to-square']"
                                class="icon"
                                @click="showEditReward(item.item)" />
                        </Tooltip>
                        <Tooltip v-if="!item.item.active"  :text="$t('activate')">
                            <FaIcon 
                                :icon="['far', 'play-circle']"
                                class="icon"
                                @click="activateReward(item.item)" />
                        </Tooltip>
                        <Tooltip v-if="!item.item.active"  :text="$t('delete')">
                            <FaIcon 
                                icon="trash"
                                class="icon small red"
                                @click="deleteItem(item.item)" />
                        </Tooltip>
                    </template>
                </Table>
            </div>
            
            <h5>{{ $t('change-reward-settings') }}</h5>
            <!-- Pick a reward type -->
            <div class="form-group">
                <label for="rewards">{{$t('which-reward-type')}}</label>
                <div v-for="(type, index) in rewardTypes" :key="index">
                    <input :id="type.value" v-model="rewardSetting.rewards" name="rewards" type="radio" :value="type.value" />
                    <label :for="type.value">{{type.text}}</label>
                </div>
                <base-form-error name="rewards" /> 
                <hr />
            </div>
        
            <!-- If the user clicks 'Character' -->
            <div v-if="rewardSetting.rewards == 'CHARACTER'" class="form-group">
                <label for="character-option">{{$t('activate-or-new')}}</label>
                <div v-for="(option, index) in characterOptions" :key="index">
                    <input 
                        :id="option.value + 'char'" 
                        v-model="rewardSetting.keepOldInstance" 
                        name="character-option" 
                        type="radio" 
                        :value="option.value" />
                    <label :for="option.value + 'char'">{{option.text}}</label>
                    <base-form-error name="keepOldInstance" /> 
                </div>
                <hr />
            </div>

            <!-- Or if the user clicks 'Village' -->
            <div v-if="rewardSetting.rewards == 'VILLAGE'" class="form-group">
                <label for="village-option">{{$t('activate-or-new-village')}}</label>
                <div v-for="(option, index) in villageOptions" :key="index">
                    <input 
                        :id="option.value + 'vill'" 
                        v-model="rewardSetting.keepOldInstance" 
                        name="village-option" 
                        type="radio" 
                        :value="option.value" />
                    <label :for="option.value + 'vill'">{{option.text}}</label>
                    <base-form-error name="keepOldInstance" /> 
                </div>
                <hr />
            </div>

            <!-- If the user wants to create a new instance -->
            <div v-if="isNewInstance" class="form-group">
                <label for="new-object-name">{{rewardTypeName}}</label>
                <p class="silent">{{ $t('change-name-later') }}</p>
                <input 
                    id="new-object-name" 
                    v-model="rewardSetting.new_object_name"
                    type="text" 
                    name="new-object-name" 
                    :placeholder="rewardTypeName"   />
                <base-form-error name="new_object_name" /> 
            </div>
            <button class="block" @click="confirmRewardsSettings()">{{ $t('save-settings') }}</button>
        </div>

        
        <Modal :show="showEditRewardNameModal" :footer="false" :title="$t('edit-reward-name')" @close="closeEditReward">
            <EditRewardObjectName :rewardObj="rewardToEdit" :type="rewardType" @close="closeEditReward" />
        </Modal>
    </div>
</template>

<script setup>
import {onMounted, ref, computed} from 'vue';
import {REWARD_TYPES, REWARD_FIELDS} from '/js/constants/rewardConstants';
import EditRewardObjectName from '../components/EditRewardObjectName.vue';
import Table from '/js/components/global/Table.vue';
import {useUserStore} from '/js/store/userStore';
const userStore = useUserStore();
import {useRewardStore} from '/js/store/rewardStore';
const rewardStore = useRewardStore();
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope

onMounted(() => load());

const rewardSetting = ref({
    rewards: null,
    keepOldInstance: null,
})
const rewardTypes = REWARD_TYPES;
const rewardFields = REWARD_FIELDS;
const loading = ref(true);
const rewardToEdit = ref(null);
const rewardType = ref(null);
const showEditRewardNameModal = ref(false);
const user = computed(() => userStore.user);
const characters = computed(() => rewardStore.characters);
const villages = computed(() => rewardStore.villages);

const rewardTypeName = computed(() => 
    rewardSetting.value.rewards == 'VILLAGE' ? t('village-name') : t('character-name'));
const isNewInstance = computed(() => {
    if (rewardSetting.value.rewards == 'NONE') return false;
    return rewardSetting.value.keepOldInstance == 'NEW';
});
const characterOptions = computed(() => {
    let options = [];
    for (const character of characters.value) {
        options.push({
            value: character.id, 
            text: 'Activate ' + character.name + displayActive(character), 
            disabled: character.active});
    }
    options.push({text: 'Make a new character', value: 'NEW'});
    return options;
});
const villageOptions = computed(() => {
    let options = [];
    for (const village of villages.value) {
        options.push({
            value: village.id, 
            text: 'Activate ' + village.name + displayActive(village), 
            disabled: village.active});
    }
    options.push({text: 'Make a new village', value: 'NEW'});
    return options;
});
const rewardItems = computed(() => {
    let rewardItems = [];
    for (const character of characters.value) {
        character.type = 'Character';
        rewardItems.push(character);
    }
    for (const village of villages.value) {
        village.type = 'Village';
        rewardItems.push(village);
    }
    return rewardItems;
});
async function confirmRewardsSettings() {
    await userStore.changeRewardType(rewardSetting.value)
    rewardSetting.value.keepOldInstance = null;
    rewardSetting.value.new_object_name = null;
    load();
}
function showEditReward(instance) {
    mainStore.clearErrors();
    rewardToEdit.value = instance;
    rewardType.value = instance.type.toUpperCase()
    showEditRewardNameModal.value = true;
}
function closeEditReward() {
    showEditRewardNameModal.value = false;
    load();
}
async function activateReward(instance) {
    await rewardStore.activateInstance(instance)
    load();
}
function displayActive(instance) {
    return instance.active ? ' (' + t('currently-active') + ')' : ''; 
}
async function deleteItem(instance) {
    if (confirm(t('confirm-delete-instance', {name: instance.name, type: instance.type.toLowerCase()}))) {
        await rewardStore.deleteInstance(instance);
        load();
    }
}
async function load() {
    mainStore.clearErrors();
    await rewardStore.fetchAllRewardInstances();
    rewardSetting.value.rewards = user.value.rewards;
    loading.value = false;
}
</script>