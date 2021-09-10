<template>
    <div>
        <div v-if="rewardsModalVisible">
            <transition name="modal-fade">
                <div class="modal-backdrop">
                    <div class="modal">
                        <information-block></information-block>

                        <div class="form-title">
                            <h3>Change reward type</h3>
                        </div>
                        <div class="form-group">
                            <label for="name">Rewards type</label>
                            <p class="silent">How would you like to be rewarded for completing your tasks?</p>
                            <b-form-radio-group checked="NONE">
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
                        </div>
                        <div class="form-group">
                            <button @click="nextModal()" class="long-button">Next</button>
                            <button @click="cancel()" class="long-button">Cancel</button>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        
        <div v-if="characterOptionsModalVisible">
            <transition name="modal-fade">
                <div class="modal-backdrop">
                    <div class="modal">
                        <information-block></information-block>

                        <div class="form-title">
                            <h3>Character setup</h3>
                        </div>
                        <div class="form-group">
                            <label for="character_name">Activate an old character or make a new one?</label>
                            <b-form-radio-group checked="NONE">
                                <b-form-radio type="radio" class="input-override" v-model="user.keepCharacter" v-for="character in characters" :key="character.id" :value="character.id">
                                    <p class="radio-label">Activate: {{character.name}}</p>
                                </b-form-radio>
                                <b-form-radio type="radio" class="input-override" v-model="user.keepCharacter" value="NEW">
                                    <p class="radio-label">Make new character</p>
                                </b-form-radio>
                            </b-form-radio-group>
                        </div>
                        
                        <div class="form-group">
                            <button @click="confirmCharacterOptions()" class="long-button">Next</button>
                            <button @click="cancel()" class="long-button">Cancel</button>
                        </div>
                    </div>
                </div>
            </transition>
        </div>

        <div v-if="newCharacterModalVisible">
            <transition name="modal-fade">
                <div class="modal-backdrop">
                    <div class="modal">
                        <information-block></information-block>

                        <div class="form-title">
                            <h3>Set up new character</h3>
                        </div>
                        <div class="form-group">
                            <label for="character_name">Character name</label>
                            <p class="silent">You can change the name later through your settings.</p>
                            <input 
                                type="text" 
                                id="character_name" 
                                name="character_name" 
                                placeholder="Character name" 
                                v-model="user.character_name" />
                        </div>
                        <div class="form-group">
                            <button @click="confirmNewRewardsType()" class="long-button">Next</button>
                            <button @click="cancel()" class="long-button">Cancel</button>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
        
    </div>
</template>

<script>
import InformationBlock from '../InformationBlock.vue';
import { mapGetters } from 'vuex';
    export default {
        components: {
            InformationBlock,
        },
        props: {
            rewardsType: String,
        },
        mounted () {
            this.rewards = this.rewardsType;
        },
        data() {
            return {
                user: {
                    rewards: "NONE",
                    existingCharacter: {},
                    keepCharacter: false,
                },
                rewardsModalVisible: true,
                newCharacterModalVisible: false,
                characterOptionsModalVisible: false,
                hasCharacter: false,
            }
        },
        methods: {
            nextModal() {
                if(this.user.rewards == 'CHARACTER'){
                    this.$store.dispatch('character/fetchAllCharacters').then(response => {
                        this.hasCharacter = !!this.characters.length;//Object.entries(this.character).length;
                        this.rewardsModalVisible = false;
                        if(!this.hasCharacter){
                            this.newCharacterModalVisible = true;
                        } else {
                            this.characterOptionsModalVisible = true;
                        }
                    });
                } else {
                    this.confirmNewRewardsType();
                }
            },
            confirmCharacterOptions(){
                if(this.user.keepCharacter == 'NEW'){
                    this.characterOptionsModalVisible = false;
                    this.newCharacterModalVisible = true;
                } else {
                    this.confirmNewRewardsType();
                }
            },
            checkCharacterInput(){
                if(this.user.rewards == "CHARACTER" && this.user.keepCharacter == 'NEW' && !this.user.character_name){
                    this.$store.commit('setResponseMessage', {message: ["No character name given."]});
                    this.$store.commit('setStatus', 'error');
                    return false;
                } else {
                    this.$store.dispatch('clearInformationBlock');
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