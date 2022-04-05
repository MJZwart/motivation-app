<template>
    <div>
        <h4>{{ $t('reward-settings') }}</h4>
        <Loading v-if="loading" />

        <div v-else>
            <h5>{{ $t('manage-rewards') }}</h5>
            <div>
                <BTable
                    :items="rewardItems"
                    :fields="rewardFields">
                    <template #active="item">
                        {{ item.item.active ? 'Yes' : 'No' }}
                    </template>
                    <template #actions="item">
                        <b-icon-pencil-square :id="'edit-item-' + item.index" class="icon" @click="showEditReward(item.item)" />
                        <b-tooltip :target="'edit-item-' + item.index">
                            {{ $t('change-name') }}
                        </b-tooltip>
                        <b-icon-play-circle 
                            v-if="!item.item.active" 
                            :id="'activate-item-' + item.index" 
                            class="icon" 
                            @click="activateReward(item.item)" />
                        <b-tooltip v-if="!item.item.active" :target="'activate-item-' + item.index">
                            {{ $t('activate') }}
                        </b-tooltip>
                        <b-icon-trash
                            v-if="!item.item.active" 
                            :id="'delete-item-' + item.index"
                            class="icon small red"
                            @click="deleteItem(item.item)" />
                        <b-tooltip v-if="!item.item.active"  :target="'delete-item-' + item.index">
                            {{ $t('delete') }}
                        </b-tooltip>
                    </template>
                </BTable>
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

        
        <BModal :show="showEditRewardNameModal" :footer="false" :title="$t('edit-reward-name')" @close="closeEditReward">
            <EditRewardObjectName :rewardObj="rewardToEdit" :type="rewardType" @close="closeEditReward" />
        </BModal>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import {REWARD_TYPES, REWARD_FIELDS} from '../../../constants/rewardConstants';
import BaseFormError from '../../BaseFormError.vue';
import Loading from '../../Loading.vue';
import EditRewardObjectName from '../../modals/EditRewardObjectName.vue';
import BTable from '../../bootstrap/BTable.vue';
import BModal from '../../bootstrap/BModal.vue';
export default {
    components: {BaseFormError, Loading, EditRewardObjectName, BModal, BTable},
    data() {
        return {
            rewardSetting: {
                rewards: null,
                keepOldInstance: null,
            },
            rewardTypes: REWARD_TYPES,
            rewardFields: REWARD_FIELDS,
            loading: true,
            rewardToEdit: null,
            rewardType: null,
            showEditRewardNameModal: false,
        }
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
            characters: 'reward/getCharacters',
            villages: 'reward/getVillages',
        }),
        rewardTypeName() {
            return this.rewardSetting.rewards == 'VILLAGE' ? this.$t('village-name') : this.$t('character-name');
        },
        isNewInstance() {
            if (this.rewardSetting.rewards == 'NONE') return false;
            return this.rewardSetting.keepOldInstance == 'NEW';
        },
        characterOptions() {
            let options = [];
            for (const character of this.characters) {
                options.push({
                    value: character.id, 
                    text: 'Activate ' + character.name + this.displayActive(character), 
                    disabled: character.active});
            }
            options.push({text: 'Make a new character', value: 'NEW'});
            return options;
        },
        villageOptions() {
            let options = [];
            for (const village of this.villages) {
                options.push({
                    value: village.id, 
                    text: 'Activate ' + village.name + this.displayActive(village), 
                    disabled: village.active});
            }
            options.push({text: 'Make a new village', value: 'NEW'});
            return options;
        },
        rewardItems() {
            let rewardItems = [];
            for (const character of this.characters) {
                character.type = 'Character';
                rewardItems.push(character);
            }
            for (const village of this.villages) {
                village.type = 'Village';
                rewardItems.push(village);
            }
            return rewardItems;
        },
    },
    methods: {
        confirmRewardsSettings() {
            this.$store.dispatch('user/changeRewardType', this.rewardSetting).then(() => {
                this.rewardSetting.keepOldInstance = null;
                this.rewardSetting.new_object_name = null;
                this.load();
            });
        },
        showEditReward(instance) {
            this.$store.dispatch('clearErrors');
            this.rewardToEdit = instance;
            this.rewardType = instance.type.toUpperCase()
            this.showEditRewardNameModal = true;
        },
        closeEditReward() {
            this.showEditRewardNameModal = false;
            this.load();
        },
        activateReward(instance) {
            this.$store.dispatch('reward/activateInstance', instance).then(() => {
                this.load();
            });
        },
        isActiveInstance(instance) {
            return instance.active;
        },
        displayActive(instance) {
            return instance.active ? ' (' + this.$t('currently-active') + ')' : ''; 
        },
        deleteItem(instance) {
            if (confirm(this.$t('confirm-delete-instance', {name: instance.name, type: instance.type.toLowerCase()}))) {
                this.$store.dispatch('reward/deleteInstance', instance).then(() => {
                    this.load();
                });
            }
        },
        load() {
            this.$store.dispatch('clearErrors');
            this.$store.dispatch('reward/fetchAllRewardInstances').then(() => {
                this.rewardSetting.rewards = this.user.rewards;
                this.loading = false;
            });
        },
    },
    mounted() {
        this.load();
    },
}
</script>