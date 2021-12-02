<template>
    <div>
        <b-modal id="change-reward-type" title="Change reward type" hide-footer>
            <b-form-group
                label="How would you like to be rewarded for completing your tasks?"
                label-for="rewards">
                <b-form-radio-group checked="NONE" name="rewards" stacked>
                    <b-form-radio type="radio" class="input-override" v-model="user.rewards" value="NONE">
                        <p class="radio-label">Nothing, just let me complete tasks.</p>
                    </b-form-radio>
                    <b-form-radio type="radio" class="input-override" v-model="user.rewards" value="CHARACTER">
                        <p class="radio-label">RPG Character (Gain experience and level up your character)</p>
                    </b-form-radio>
                    <b-form-radio type="radio" class="input-override" v-model="user.rewards" value="VILLAGE" disabled>
                        <p class="radio-label disabled">Village (coming soon)</p>
                    </b-form-radio>
                </b-form-radio-group>
            </b-form-group>
            <b-button @click="confirmRewardsType()" class="long-button">Next</b-button>
            <b-button @click="cancel()" class="long-button">Cancel</b-button>
        </b-modal>
        
        <b-modal id="character-options" title="Character options" hide-footer>
            <b-form-group
                label="Activate an old character or make a new one?"
                label-for="character-option">
                <b-form-radio-group name="character-option" stacked>
                    <b-form-radio type="radio" class="input-override" v-model="user.keepCharacter" v-for="character in characters" :key="character.id" :value="character.id">
                        <p class="radio-label">Activate: {{character.name}}</p>
                    </b-form-radio>
                    <b-form-radio type="radio" class="input-override" v-model="user.keepCharacter" value="NEW">
                        <p class="radio-label">Make new character</p>
                    </b-form-radio>
                </b-form-radio-group>
            </b-form-group>
            <b-button @click="confirmCharacterOptions()" class="long-button">Confirm</b-button>
            <b-button @click="cancel()" class="long-button">Cancel</b-button>
        </b-modal>

        <b-modal id="new-character" title="Set up new character" hide-footer>
            <b-form-group
                    label="Character name"
                    label-for="character_name">
                <p class="silent">You can change the name later through your settings.</p>
                <input 
                    type="text" 
                    id="character_name" 
                    name="character_name" 
                    placeholder="Character name" 
                    v-model="user.character_name" />
            </b-form-group>
            <b-button @click="confirmNewRewardsType()" class="long-button">Confirm</b-button>
            <b-button @click="cancel()" class="long-button">Cancel</b-button>
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
                //TODO For future options such as village, change this to an if-else or switch
                if(this.user.rewards == 'CHARACTER'){
                    this.$store.dispatch('character/fetchAllCharacters').then(response => {
                        this.hasCharacter = !!this.characters.length;//Object.entries(this.character).length;
                        this.$bvModal.hide('change-reward-type');
                        if(!this.hasCharacter){
                            this.$bvModal.show('new-character');
                        } else {
                            this.$bvModal.show('character-options');
                        }
                    });
                } else {
                    this.confirmNewRewardsType();
                }
            },
            confirmCharacterOptions(){
                if(this.user.keepCharacter == 'NEW'){
                    this.$bvModal.hide('character-options');
                    this.$bvModal.show('new-character');
                } else {
                    this.confirmNewRewardsType();
                }
            },
            checkCharacterInput(){
                if(this.user.rewards == "CHARACTER" && this.user.keepCharacter == 'NEW' && !this.user.character_name){
                    this.$store.commit('setErrorMessages', {message: ["No character name given."]});
                    return false;
                } else {
                    this.$store.dispatch('clearErrors');
                    return true;
                }
            },
            confirmNewRewardsType() {
                if(this.checkCharacterInput()){
                    this.$store.dispatch('user/changeRewardType', this.user).then(response => {
                        this.cancel();
                    });
                }
            },
            cancel(){
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