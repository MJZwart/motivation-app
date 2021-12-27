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

        <!-- Will show when the user chooses to set up a new character -->
        <b-modal id="new-character" :title="$t('set-up-new-character')" hide-footer>
            <b-form-group
                :label="$t('character-name')"
                label-for="character_name">
                <p class="silent">{{ $t('change-name-later') }}</p>
                <b-form-input 
                    id="character_name" 
                    v-model="user.character_name"
                    type="text" 
                    name="character_name" 
                    :placeholder="$t('character-name')"   />
                <base-form-error name="character_name" /> 
            </b-form-group>
            <b-button class="long-button" @click="confirmNewRewardsType()">{{ $t('confirm') }}</b-button>
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

        <!-- Will show when the user chooses to set up a new village -->
        <b-modal id="new-village" :title="$t('set-up-new-village')" hide-footer>
            <b-form-group
                :label="$t('village-name')"
                label-for="village_name">
                <p class="silent">{{ $t('change-name-later') }}</p>
                <b-form-input 
                    id="village_name" 
                    v-model="user.village_name"
                    type="text" 
                    name="village_name" 
                    :placeholder="$t('village-name')"   />
                <base-form-error name="village_name" /> 
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
                keepCharacter: 'NEW',
                keepVillage: 'NEW',
                character_name: '',
                village_name: '',
            },
        }
    },
    methods: {
        /** When the user selects a reward type, if character selected, check for existing characters
         * If characters exist, show these as options to activate or make a new character. 
         * Show/hide modals accordingly
         */
        confirmRewardsType() {
            //TODO For future options such as village, change this to an if-else or switch
            if (this.user.rewards == 'CHARACTER') {
                this.$store.dispatch('character/fetchAllCharacters').then(() => {
                    const hasCharacter = !!this.characters.length;
                    this.$bvModal.hide('change-reward-type');
                    if (!hasCharacter) {
                        this.$bvModal.show('new-character');
                    } else {
                        this.$bvModal.show('character-options');
                    }
                });
            } else if (this.user.rewards == 'VILLAGE') {
                this.$store.dispatch('village/fetchAllVillages').then(() => {
                    const hasVillages = !!this.villages.length;
                    this.$bvModal.hide('change-reward-type');
                    if (!hasVillages) {
                        this.$bvModal.show('new-village');
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
                this.$bvModal.show('new-character');
            } else {
                this.confirmNewRewardsType();
            }
        },
        /** When the user selects whether to create a new village or activate an existing one
         */
        confirmVillageOptions() { //TODO can be one method
            if (this.user.keepOldInstance == 'NEW') {
                this.$bvModal.hide('village-options');
                this.$bvModal.show('new-village');
            } else {
                this.confirmNewRewardsType();
            }
        },
        /** Check if the user has set a character name if they have chosen to make a new character
         */
        // eslint-disable-next-line complexity
        checkNewInstanceInput() {
            console.log('Checking input');
            console.log('user.rewards = ' + this.user.rewards);
            console.log('user.keepOldInstance = ' + this.user.keepOldInstance);
            console.log('user.village_name = ' + this.user.village_name);
            //TODO You can change the name to one global value, rather than two.
            if (this.user.rewards == 'CHARACTER' && this.user.keepOldInstance == 'NEW' && !this.user.character_name) {
                this.sendErrorMessage('No character name given.');
                return false;
            } else if (this.user.rewards == 'VILLAGE' && this.user.keepOldInstance == 'NEW' && !this.user.village_name) {
                this.sendErrorMessage('No village name given');
                return false;
            } else {
                this.$store.dispatch('clearErrors');
                return true;
            }
        },
        sendErrorMessage(message) {
            console.log('sending error ' + message);
            this.$store.commit('setErrorMessages', {message: [message]});
        },
        /** Check if the user has set a village name if they have chosen to make a new village
         */
        checkVillageInput() { //TODO can be one method
            if (this.user.rewards == 'VILLAGE' && this.user.keepVillage == 'NEW' && !this.user.village_name) {
                this.$store.commit('setErrorMessages', {message: ['No village name given.']});
                return false;
            } else {
                this.$store.dispatch('clearErrors');
                return true;
            }
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
            characters: 'character/getCharacters',
            villages: 'village/getVillages',
        }),
    },
}
</script>