<template>
    <div>
        <b-modal id="change-reward-type" :title="$t('change-reward-type')" hide-footer>
            <b-form-group
                :label="$t('which-reward-type')"
                label-for="rewards">
                <b-form-radio-group checked="NONE" name="rewards" stacked>
                    <b-form-radio type="radio" class="input-override" v-model="user.rewards" value="NONE">
                        <p class="radio-label">{{ $t('no-rewards') }}</p>
                    </b-form-radio>
                    <b-form-radio type="radio" class="input-override" v-model="user.rewards" value="CHARACTER">
                        <p class="radio-label">{{ $t('character-reward') }}</p>
                    </b-form-radio>
                    <b-form-radio type="radio" class="input-override" v-model="user.rewards" value="VILLAGE" disabled>
                        <p class="radio-label disabled">{{ $t('village-reward') }}</p>
                    </b-form-radio>
                </b-form-radio-group>
            </b-form-group>
            <b-button @click="confirmRewardsType()" class="long-button">{{ $t('next') }}</b-button>
            <b-button @click="cancel()" class="long-button">{{ $t('cancel') }}</b-button>
        </b-modal>
        
        <b-modal id="character-options" :title="$t('character-options')" hide-footer>
            <b-form-group
                :label="$t('activate-or-new')"
                label-for="character-option">
                <b-form-radio-group name="character-option" stacked>
                    <b-form-radio type="radio" class="input-override" v-model="user.keepCharacter" v-for="character in characters" :key="character.id" :value="character.id">
                        <p class="radio-label">{{ $t('activate') }}: {{character.name}}</p>
                    </b-form-radio>
                    <b-form-radio type="radio" class="input-override" v-model="user.keepCharacter" value="NEW">
                        <p class="radio-label">{{ $t('make-new-character') }}</p>
                    </b-form-radio>
                </b-form-radio-group>
            </b-form-group>
            <b-button @click="confirmCharacterOptions()" class="long-button">{{ $t('confirm') }}</b-button>
            <b-button @click="cancel()" class="long-button">{{ $t('cancel') }}</b-button>
        </b-modal>

        <b-modal id="new-character" :title="$t('set-up-new-character')" hide-footer>
            <b-form-group
                    :label="$t('character-name')"
                    label-for="character_name">
                <p class="silent">{{ $t('change-name-later') }}</p>
                <input 
                    type="text" 
                    id="character_name" 
                    name="character_name" 
                    :placeholder="$t('character-name')" 
                    v-model="user.character_name" />
            </b-form-group>
            <b-button @click="confirmNewRewardsType()" class="long-button">{{ $t('confirm') }}</b-button>
            <b-button @click="cancel()" class="long-button">{{ $t('cancel') }}</b-button>
        </b-modal>
        
    </div>
</template>

<script>
import BaseFormError from '../BaseFormError.vue';
import { mapGetters } from 'vuex';
    export default {
        components: {
            BaseFormError,
        },
        props: {
            rewardsType: String,
        },
        mounted () {
            this.rewards = this.rewardsType;
            this.$bvModal.show('change-reward-type');
        },
        data() {
            return {
                user: {
                    rewards: "NONE",
                    existingCharacter: {},
                    keepCharacter: false,
                },
                hasCharacter: false,
            }
        },
        methods: {
            confirmRewardsType() {
                console.log('confirmed rewards type');
                //TODO For future options such as village, change this to an if-else or switch
                if(this.user.rewards == 'CHARACTER'){
                    console.log('chose character');
                    this.$store.dispatch('character/fetchAllCharacters').then(() => {
                        this.hasCharacter = !!this.characters.length;//Object.entries(this.character).length;
                        this.$bvModal.hide('change-reward-type');
                        console.log('checking if has character');
                        if(!this.hasCharacter){
                            console.log('has no character');
                            this.$bvModal.show('new-character');
                        } else {
                            console.log('has character');
                            this.$bvModal.show('character-options');
                        }
                    });
                } else {
                    console.log('no rewards');
                    this.confirmNewRewardsType();
                }
            },
            confirmCharacterOptions(){
                console.log('confirming character options');
                if(this.user.keepCharacter == 'NEW'){
                    console.log('wants new character');
                    this.$bvModal.hide('character-options');
                    this.$bvModal.show('new-character');
                } else {
                    this.confirmNewRewardsType();
                }
            },
            checkCharacterInput(){
                console.log('checking character input');
                if(this.user.rewards == "CHARACTER" && this.user.keepCharacter == 'NEW' && !this.user.character_name){
                    this.$store.commit('setErrorMessages', {message: ["No character name given."]});
                    return false;
                } else {
                    this.$store.dispatch('clearErrors');
                    return true;
                }
            },
            confirmNewRewardsType() {
                console.log('confirming new rewards type');
                if(this.checkCharacterInput()){
                    this.$store.dispatch('user/changeRewardType', this.user).then(response => {
                        this.cancel();
                    });
                }
            },
            cancel(){
                console.log('closing');
                this.$emit('close');
            }
        },
        computed: {
            ...mapGetters({
                characters: 'character/getCharacters',
            }),
        },
    }
</script>

<style>
.examples-slot{
    height:500px;
    overflow-y:scroll;
}
.label-override{
    text-transform:none;
    letter-spacing:0;
}
.input-override input{
    width:5%;
}
.input-override label{
    display:inline;
    text-transform:none;
    letter-spacing:0;
    font-weight: 400;
    font-size: 1rem;
}
.radio-label{
    display:inline-block;
}
.disabled {
    text-decoration-line: line-through;
}
.long-button.half{
    width:49%;
}
</style>