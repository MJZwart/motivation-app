<template>
    <div>
        Reward settings
        <Loading v-if="loading" />

        <div v-else>
            <b-form-group
                :label="$t('which-reward-type')"
                label-for="rewards">
                <b-form-radio-group :checked="rewardSetting.currentRewardType" 
                                    name="rewards" 
                                    stacked
                                    :options="rewardTypes"
                >
                    <base-form-error name="rewards" /> 
                </b-form-radio-group>
            </b-form-group>
            <!-- <b-button class="long-button" @click="confirmRewardsType()">{{ $t('next') }}</b-button>
            <b-button class="long-button" @click="cancel()">{{ $t('cancel') }}</b-button> -->
        
            <b-form-group
                v-if="rewardSetting.currentRewardType == 'CHARACTER'"
                :label="$t('activate-or-new')"
                label-for="character-option">
                <b-form-radio-group name="character-option" stacked>
                    <b-form-radio 
                        v-for="character in characters" :key="character.id" 
                        v-model="rewardSetting.keepOldInstance"
                        type="radio" 
                        class="input-override" 
                        :value="character.id">
                        <p class="radio-label">{{ $t('activate') }}: {{character.name}}</p>
                    </b-form-radio>
                    <b-form-radio v-model="rewardSetting.keepOldInstance" type="radio" class="input-override" value="NEW">
                        <p class="radio-label">{{ $t('make-new-character') }}</p>
                    </b-form-radio>
                    <base-form-error name="character-option" /> 
                </b-form-radio-group>
            </b-form-group>
            <b-button class="long-button" @click="confirmCharacterOptions()">{{ $t('confirm') }}</b-button>
            <b-button class="long-button" @click="cancel()">{{ $t('cancel') }}</b-button>

            <b-form-group
                :label="$t('activate-or-new-village')"
                label-for="village-option">
                <b-form-radio-group name="village-option" stacked>
                    <b-form-radio 
                        v-for="village in villages" :key="village.id" 
                        v-model="rewardSetting.keepOldInstance"
                        type="radio" 
                        class="input-override" 
                        :value="village.id">
                        <p class="radio-label">{{ $t('activate') }}: {{village.name}}</p>
                    </b-form-radio>
                    <b-form-radio v-model="rewardSetting.keepOldInstance" type="radio" class="input-override" value="NEW">
                        <p class="radio-label">{{ $t('make-new-village') }}</p>
                    </b-form-radio>
                    <base-form-error name="village-option" /> 
                </b-form-radio-group>
            </b-form-group>
            <b-button class="long-button" @click="confirmVillageOptions()">{{ $t('confirm') }}</b-button>
            <b-button class="long-button" @click="cancel()">{{ $t('cancel') }}</b-button>

            <b-form-group
                :label="rewardTypeName"
                label-for="new-object-name">
                <p class="silent">{{ $t('change-name-later') }}</p>
                <b-form-input 
                    id="new-object-name" 
                    v-model="rewardSetting.new_object_name"
                    type="text" 
                    name="new-object-name" 
                    :placeholder="rewardTypeName"   />
                <base-form-error name="new-object-name" /> 
            </b-form-group>
            <b-button class="long-button" @click="confirmNewRewardsType()">{{ $t('confirm') }}</b-button>
            <b-button class="long-button" @click="cancel()">{{ $t('cancel') }}</b-button> 
        </div>


        
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import {REWARD_TYPES} from '../../../constants/rewardConstants';
import BaseFormError from '../../BaseFormError.vue';
import Loading from '../../Loading.vue';
export default {
    components: {BaseFormError, Loading},
    props: {
        user: {
            /** @type import('resources/types/user').User */
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            rewardSetting: {
                currentRewardType: null,
            },
            rewardTypes: REWARD_TYPES,
            loading: true,
        }
    },
    computed: {
        ...mapGetters({
            characters: 'reward/getCharacters',
            villages: 'reward/getVillages',
        }),
        rewardTypeName() {
            return this.user.rewards == 'VILLAGE' ? this.$t('village-name') : this.$t('character-name');
        },
    },
    mounted() {
        this.$store.dispatch('reward/fetchAllRewardInstances').then(() => {
            this.rewardSetting.currentRewardType = this.user.rewards;
            this.loading = false;
        });
    },
}
</script>