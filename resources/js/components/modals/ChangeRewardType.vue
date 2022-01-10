<template>
    <div>
        <!-- Will show when the user clicks the button to change reward type -->
        <b-modal id="change-reward-type" :title="$t('change-reward-type')" hide-footer>
            <b-form-group
                :label="$t('which-reward-type')"
                label-for="rewards">
                <b-form-radio-group checked="NONE" name="rewards" stacked>
                    <b-form-radio v-model="user.rewards" type="radio" class="input-override" value="NONE">
                        <p class="radio-label">{{ $t('no-rewards') }}</p>
                    </b-form-radio>
                    <b-form-radio v-model="user.rewards" type="radio" class="input-override" value="CHARACTER">
                        <p class="radio-label">{{ $t('character-reward') }}</p>
                    </b-form-radio>
                    <b-form-radio v-model="user.rewards" type="radio" class="input-override" value="VILLAGE">
                        <p class="radio-label">{{ $t('village-reward') }}</p>
                    </b-form-radio>
                    <base-form-error name="rewards" /> 
                </b-form-radio-group>
            </b-form-group>
            <b-button class="long-button" @click="confirmRewardsType()">{{ $t('next') }}</b-button>
            <b-button class="long-button" @click="cancel()">{{ $t('cancel') }}</b-button>
        </b-modal>
        
        <!-- Will show when the user picks 'character' as a reward type -->
        <b-modal id="character-options" :title="$t('character-options')" hide-footer>
            <b-form-group
                :label="$t('activate-or-new')"
                label-for="character-option">
                <b-form-radio-group name="character-option" stacked>
                    <b-form-radio 
                        v-for="character in characters" :key="character.id" 
                        v-model="user.keepOldInstance"
                        type="radio" 
                        class="input-override" 
                        :value="character.id">
                        <p class="radio-label">{{ $t('activate') }}: {{character.name}}</p>
                    </b-form-radio>
                    <b-form-radio v-model="user.keepOldInstance" type="radio" class="input-override" value="NEW">
                        <p class="radio-label">{{ $t('make-new-character') }}</p>
                    </b-form-radio>
                    <base-form-error name="character-option" /> 
                </b-form-radio-group>
            </b-form-group>
            <b-button class="long-button" @click="confirmCharacterOptions()">{{ $t('confirm') }}</b-button>
            <b-button class="long-button" @click="cancel()">{{ $t('cancel') }}</b-button>
        </b-modal>

        <!-- Will show when the user chooses a village -->
        <b-modal id="village-options" :title="$t('village-options')" hide-footer>
            <b-form-group
                :label="$t('activate-or-new-village')"
                label-for="village-option">
                <b-form-radio-group name="village-option" stacked>
                    <b-form-radio 
                        v-for="village in villages" :key="village.id" 
                        v-model="user.keepOldInstance"
                        type="radio" 
                        class="input-override" 
                        :value="village.id">
                        <p class="radio-label">{{ $t('activate') }}: {{village.name}}</p>
                    </b-form-radio>
                    <b-form-radio v-model="user.keepOldInstance" type="radio" class="input-override" value="NEW">
                        <p class="radio-label">{{ $t('make-new-village') }}</p>
                    </b-form-radio>
                    <base-form-error name="village-option" /> 
                </b-form-radio-group>
            </b-form-group>
            <b-button class="long-button" @click="confirmVillageOptions()">{{ $t('confirm') }}</b-button>
            <b-button class="long-button" @click="cancel()">{{ $t('cancel') }}</b-button>
        </b-modal>

        <!-- Will show when the user chooses to set up a new character or village object -->
        <b-modal id="new-object-name" :title="setUpNewObject" hide-footer>
            <b-form-group
                :label="rewardTypeName"
                label-for="new-object-name">
                <p class="silent">{{ $t('change-name-later') }}</p>
                <b-form-input 
                    id="new-object-name" 
                    v-model="user.new_object_name"
                    type="text" 
                    name="new-object-name" 
                    :placeholder="rewardTypeName"   />
                <base-form-error name="new-object-name" /> 
            </b-form-group>
            <b-button class="long-button" @click="confirmNewRewardsType()">{{ $t('confirm') }}</b-button>
            <b-button class="long-button" @click="cancel()">{{ $t('cancel') }}</b-button>
        </b-modal>
    </div>
</template>

<script>
import BaseFormError from '../BaseFormError.vue';
import {mapGetters} from 'vuex';
import Vue from 'vue';
export default {
    components: {
        BaseFormError,
    },
    props: {
        rewardsType: {
            type: String,
            required: true,
        },
    },
    mounted () {
        this.rewards = Vue.util.extend({}, this.rewardsType);
        this.$bvModal.show('change-reward-type');
    },
    data() {
        return {
            rewards: {},
            /** @type {import('../../types/user').User} */
            user: {
                rewards: 'NONE',
                existingCharacter: {},
                keepOldInstance: 'NEW',
                new_object_name: '',
            },
        }
    },
    methods: {
        /** When the user selects a reward type, if character selected, check for existing characters
         * If characters exist, show these as options to activate or make a new character. 
         * Show/hide modals accordingly
         */
        confirmRewardsType() {
            if (this.user.rewards == 'CHARACTER') {
                this.$store.dispatch('reward/fetchAllCharacters').then(() => {
                    const hasCharacter = !!this.characters.length;
                    this.$bvModal.hide('change-reward-type');
                    if (!hasCharacter) {
                        this.$bvModal.show('new-object-name');
                    } else {
                        this.$bvModal.show('character-options');
                    }
                });
            } else if (this.user.rewards == 'VILLAGE') {
                this.$store.dispatch('reward/fetchAllVillages').then(() => {
                    const hasVillages = !!this.villages.length;
                    this.$bvModal.hide('change-reward-type');
                    if (!hasVillages) {
                        this.$bvModal.show('new-object-name');
                    } else {
                        this.$bvModal.show('village-options');
                    }
                });
            } else {
                this.confirmNewRewardsType();
            }
        },
        /** When the user selects whether to create a new character or activate an existing one
         */
        confirmCharacterOptions() {
            if (this.user.keepOldInstance == 'NEW') {
                this.$bvModal.hide('character-options');
                this.$bvModal.show('new-object-name');
            } else {
                this.confirmNewRewardsType();
            }
        },
        /** When the user selects whether to create a new village or activate an existing one
         */
        confirmVillageOptions() { //TODO can be one method
            if (this.user.keepOldInstance == 'NEW') {
                this.$bvModal.hide('village-options');
                this.$bvModal.show('new-object-name');
            } else {
                this.confirmNewRewardsType();
            }
        },
        /** Check if the user has set a character name if they have chosen to make a new character
         */
        // eslint-disable-next-line complexity
        checkNewInstanceInput() {
            const nameGiven = this.user.keepOldInstance == 'NEW' && !this.user.new_object_name;
            if (this.user.rewards == 'CHARACTER' && nameGiven) {
                this.sendErrorMessage('No character name given.');
                return false;
            } else if (this.user.rewards == 'VILLAGE' && nameGiven) {
                this.sendErrorMessage('No village name given');
                return false;
            } else {
                this.$store.dispatch('clearErrors');
                return true;
            }
        },
        sendErrorMessage(message) {
            this.$store.commit('setErrorMessages', {message: [message]});
        },
        /** After checking the input, send the call to the server, save the settings and close the modal
         */
        confirmNewRewardsType() {
            if (this.checkNewInstanceInput()) {
                this.$store.dispatch('user/changeRewardType', this.user).then(() => {
                    this.cancel();
                });
            }
        },
        cancel() {
            this.$emit('close');
        },
    },
    computed: {
        ...mapGetters({
            characters: 'reward/getCharacters',
            villages: 'reward/getVillages',
        }),
        rewardTypeName() {
            return this.user.rewards == 'VILLAGE' ? this.$t('village-name') : this.$t('character-name');
        },
        setUpNewObject() {
            return this.user.rewards == 'VILLAGE' ? this.$t('set-up-new-village') : this.$t('set-up-new-character');
        },
    },
}
</script>