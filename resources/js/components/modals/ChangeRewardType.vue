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
                    <b-form-radio v-model="user.rewards" type="radio" class="input-override" value="VILLAGE" disabled>
                        <p class="radio-label disabled">{{ $t('village-reward') }}</p>
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
                        v-model="user.keepCharacter"
                        type="radio" 
                        class="input-override" 
                        :value="character.id">
                        <p class="radio-label">{{ $t('activate') }}: {{character.name}}</p>
                    </b-form-radio>
                    <b-form-radio v-model="user.keepCharacter" type="radio" class="input-override" value="NEW">
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
                keepCharacter: 'NEW',
                character_name: null,
            },
            hasCharacter: false,
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
                    this.hasCharacter = !!this.characters.length;
                    this.$bvModal.hide('change-reward-type');
                    if (!this.hasCharacter) {
                        this.$bvModal.show('new-character');
                    } else {
                        this.$bvModal.show('character-options');
                    }
                });
            } else {
                this.confirmNewRewardsType();
            }
        },
        /** When the user selects whether to create a new character or activate an existing one
         */
        confirmCharacterOptions() {
            if (this.user.keepCharacter == 'NEW') {
                this.$bvModal.hide('character-options');
                this.$bvModal.show('new-character');
            } else {
                this.confirmNewRewardsType();
            }
        },
        /** Check if the user has set a character name if they have chosen to make a new character
         */
        checkCharacterInput() {
            if (this.user.rewards == 'CHARACTER' && this.user.keepCharacter == 'NEW' && !this.user.character_name) {
                this.$store.commit('setErrorMessages', {message: ['No character name given.']});
                return false;
            } else {
                this.$store.dispatch('clearErrors');
                return true;
            }
        },
        /** After checking the input, send the call to the server, save the settings and close the modal
         */
        confirmNewRewardsType() {
            if (this.checkCharacterInput()) {
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
        }),
    },
}
</script>